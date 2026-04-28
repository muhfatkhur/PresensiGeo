@extends(Auth::user()->role === 'guru' ? 'layouts.guru' : 'layouts.siswa')

@section('title', 'Presensi ' . ucfirst(Auth::user()->role))

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="max-w-md mx-auto bg-slate-50 min-h-screen pb-20">
    <div class="bg-[#0A1D37] rounded-b-[2.5rem] pt-12 pb-24 px-8 relative shadow-lg">
        
        @if(Auth::user()->role === 'siswa')
            <form id="logout-form-siswa" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="document.getElementById('logout-form-siswa').submit();" class="absolute top-6 right-6 text-white/60 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10" title="Keluar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        @endif

        <div class="flex items-center justify-between mb-8">
            <div class="text-white">
                <p class="text-sm text-slate-300 font-medium mb-1">Selamat datang</p>
                <h2 class="text-xl font-extrabold truncate">{{ Auth::user()->name }}</h2>
            </div>
            <div class="w-12 h-12 rounded-full border-2 border-white/20 overflow-hidden bg-slate-800 shrink-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FFD700&color=0A1D37" alt="Avatar" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="absolute -bottom-16 left-8 right-8">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Waktu Saat Ini</p>
                <h1 id="jam-live" class="text-4xl font-black text-[#0A1D37] tracking-tight">--:--:--</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="px-8 mt-24">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl mb-6 text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ $errors->first() }}
            </div>
        @endif

        @if($geofencing && $geofencing->is_open)
            <form action="{{ route('presensi.store') }}" method="POST" id="form-absen">
                @csrf
                <input type="hidden" id="status-input" name="status" value="hadir">
                <input type="hidden" id="lat-input" name="latitude">
                <input type="hidden" id="lng-input" name="longitude">

                <div class="flex bg-slate-200/50 p-1.5 rounded-2xl mb-6 border border-slate-100">
                    <button type="button" onclick="gantiStatus('hadir')" id="btn-hadir" class="flex-1 py-2.5 text-sm font-black rounded-xl bg-white text-[#0A1D37] shadow-sm transition-all">Hadir</button>
                    <button type="button" onclick="gantiStatus('izin')" id="btn-izin" class="flex-1 py-2.5 text-sm font-bold rounded-xl text-slate-400 hover:text-slate-600 transition-all">Izin</button>
                    <button type="button" onclick="gantiStatus('sakit')" id="btn-sakit" class="flex-1 py-2.5 text-sm font-bold rounded-xl text-slate-400 hover:text-slate-600 transition-all">Sakit</button>
                </div>

                <div id="container-hadir" class="bg-white rounded-3xl p-4 shadow-lg shadow-slate-200/50 border border-slate-100 mb-6 transition-all duration-300">
                    <div class="flex items-center justify-between px-2 mb-3">
                        <p class="text-sm font-extrabold text-slate-700">Status Lokasi</p>
                        <span id="status-badge" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-500">Mencari GPS...</span>
                    </div>
                    
                    <div class="rounded-2xl overflow-hidden h-40 relative border border-slate-100 z-0">
                        <div id="map-siswa" class="w-full h-full"></div>
                    </div>

                    <div class="flex items-center justify-between px-2 mt-4">
                        <p class="text-xs font-bold text-slate-400">Jarak ke sekolah:</p>
                        <p id="jarak-teks" class="text-sm font-extrabold text-[#0A1D37]">Menghitung...</p>
                    </div>
                    <p class="text-[10px] text-red-500 text-center mt-2 font-bold">💡 Pastikan GPS / Lokasi HP menyala dan berikan izin akses browser.</p>
                </div>

                <div id="container-alasan" class="hidden bg-white rounded-3xl p-5 shadow-lg shadow-slate-200/50 border border-slate-100 mb-6 transition-all duration-300">
                    <label class="block text-sm font-extrabold text-[#0A1D37] mb-2">Alasan <span id="label-alasan">Izin</span></label>
                    <textarea name="alasan" id="alasan-input" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-sm font-medium text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all placeholder:text-slate-400 resize-none" placeholder="Ketik alasan dengan jelas."></textarea>
                </div>

                <button type="submit" id="btn-absen" class="w-full bg-slate-300 text-slate-500 py-4 rounded-2xl font-black text-lg transition-all shadow-lg flex items-center justify-center gap-3" disabled>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                    Mencari Titik Lokasi
                </button>
            </form>
        @else
            <div class="w-full bg-red-50 border border-red-200 text-red-600 py-4 rounded-2xl font-black text-center text-sm shadow-sm">
                🔒 GERBANG PRESENSI DITUTUP.
            </div>
        @endif
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    setInterval(() => {
        let now = new Date();
        document.getElementById('jam-live').innerText = 
            now.getHours().toString().padStart(2, '0') + ':' + 
            now.getMinutes().toString().padStart(2, '0') + ':' + 
            now.getSeconds().toString().padStart(2, '0');
    }, 1000);

    const schoolLat = {{ $geofencing->latitude ?? 0 }};
    const schoolLng = {{ $geofencing->longitude ?? 0 }};
    const radius = {{ $geofencing->radius_meter ?? 0 }};
    
    let isDalamRadius = false;

    let map = L.map('map-siswa', {
        dragging: false,
        touchZoom: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        boxZoom: false,
        keyboard: false,
        zoomControl: false
    }).setView([schoolLat, schoolLng], 17);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OS'
    }).addTo(map);

    let schoolCircle = L.circle([schoolLat, schoolLng], {
        color: '#FFD700',
        fillColor: '#0A1D37',
        fillOpacity: 0.2,
        radius: radius
    }).addTo(map);

    let userMarker = null;
    let btnAbsen = document.getElementById('btn-absen');
    let latInput = document.getElementById('lat-input');
    let lngInput = document.getElementById('lng-input');
    let statusBadge = document.getElementById('status-badge');
    let jarakTeks = document.getElementById('jarak-teks');
    let statusInput = document.getElementById('status-input');

    function gantiStatus(statusPilihan) {
        statusInput.value = statusPilihan;
        
        let btns = ['btn-hadir', 'btn-izin', 'btn-sakit'];
        btns.forEach(id => {
            let el = document.getElementById(id);
            el.className = "flex-1 py-2.5 text-sm font-bold rounded-xl text-slate-400 hover:text-slate-600 transition-all";
        });
        
        let activeBtn = document.getElementById('btn-' + statusPilihan);
        activeBtn.className = "flex-1 py-2.5 text-sm font-black rounded-xl bg-white text-[#0A1D37] shadow-sm transition-all";

        let containerHadir = document.getElementById('container-hadir');
        let containerAlasan = document.getElementById('container-alasan');
        let labelAlasan = document.getElementById('label-alasan');

        if (statusPilihan === 'hadir') {
            containerHadir.classList.remove('hidden');
            containerAlasan.classList.add('hidden');
            setTimeout(() => { map.invalidateSize(); }, 100);
            updateTombolUI();
        } else {
            containerHadir.classList.add('hidden');
            containerAlasan.classList.remove('hidden');
            labelAlasan.innerText = statusPilihan.charAt(0).toUpperCase() + statusPilihan.slice(1);
            updateTombolUI();
        }
    }

    function updateTombolUI() {
        let currentStatus = statusInput.value;
        
        if (currentStatus === 'hadir') {
            if (isDalamRadius) {
                btnAbsen.disabled = false;
                btnAbsen.className = "w-full bg-[#0A1D37] text-[#FFD700] hover:bg-[#14325a] py-4 rounded-2xl font-black text-lg transition-all shadow-lg shadow-[#0A1D37]/30 flex items-center justify-center gap-3";
                btnAbsen.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Kirim Presensi Sekarang`;
            } else {
                btnAbsen.disabled = true;
                btnAbsen.className = "w-full bg-slate-300 text-slate-500 py-4 rounded-2xl font-black text-lg transition-all flex items-center justify-center gap-3";
                btnAbsen.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Anda Berada di Luar Radius`;
            }
        } else {
            let label = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            btnAbsen.disabled = false;
            btnAbsen.className = "w-full bg-[#0A1D37] text-[#FFD700] hover:bg-[#14325a] py-4 rounded-2xl font-black text-lg transition-all shadow-lg shadow-[#0A1D37]/30 flex items-center justify-center gap-3";
            btnAbsen.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Kirim Pengajuan ${label}`;
        }
    }

    function hitungJarak(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }

    function updatePosisi(lat, lng) {
        latInput.value = lat;
        lngInput.value = lng;

        if(userMarker) {
            userMarker.setLatLng([lat, lng]);
        } else {
            userMarker = L.marker([lat, lng], {draggable: false}).addTo(map);
        }

        let mapBounds = L.latLngBounds([ [schoolLat, schoolLng], [lat, lng] ]);
        map.fitBounds(mapBounds, { padding: [20, 20] });

        let jarak = hitungJarak(schoolLat, schoolLng, lat, lng);
        jarakTeks.innerText = jarak + " Meter";

        if (!document.getElementById('form-absen')) return; 

        isDalamRadius = (jarak <= radius);

        if(isDalamRadius) {
            statusBadge.innerText = "DALAM RADIUS";
            statusBadge.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700";
        } else {
            statusBadge.innerText = "LUAR RADIUS";
            statusBadge.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-700";
        }

        updateTombolUI();
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            updatePosisi(position.coords.latitude, position.coords.longitude);
        }, function(error) {
            statusBadge.innerText = "GPS DITOLAK/MATI";
            statusBadge.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-700";
            jarakTeks.innerText = "Gagal Akses GPS";
            btnAbsen.disabled = true;
            btnAbsen.className = "w-full bg-red-300 text-red-700 py-4 rounded-2xl font-black text-lg transition-all flex items-center justify-center gap-3";
            btnAbsen.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Izinkan GPS Browser!`;
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        });
    } else {
        alert("Browser HP tidak support geolocation. Gunakan HP dengan GPS dan pastikan izin lokasi diaktifkan.");
    }
</script>

@endsection