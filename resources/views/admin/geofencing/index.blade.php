@extends('layouts.admin')

@section('title', 'Pengaturan Geofencing')
@section('header', 'Setting Lokasi Presensi')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="flex flex-col min-h-[calc(100vh-8rem)]">
    <div class="flex-1">

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-xl mb-6 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl mb-6 shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-extrabold text-[#0A1D37] text-lg">Pengaturan Koordinat & Radius</h3>
                    <p class="text-xs text-slate-400 mt-1">Klik pada peta, geser pin, atau paste koordinat untuk menetapkan titik pusat.</p>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('admin.geofencing.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1 space-y-6">
                            
                            <div>
                                <label for="latitude" class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Latitude</label>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $geofencing->latitude ?? '-7.4268') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all" required>
                                <p class="text-[10px] text-slate-400 mt-1 font-bold"> copas koordinat gmaps disini</p>
                            </div>

                            <div>
                                <label for="longitude" class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $geofencing->longitude ?? '111.0222') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all" required>
                            </div>

                            <button type="button" id="btn-lokasi" class="w-full bg-blue-50 text-blue-600 px-5 py-3 rounded-xl font-bold text-sm hover:bg-blue-100 border border-blue-200 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Deteksi Lokasi Saya Saat Ini
                            </button>

                            <div>
                                <label for="radius_meter" class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Radius (Meter)</label>
                                <div class="relative">
                                    <input type="number" id="radius_meter" name="radius_meter" value="{{ old('radius_meter', $geofencing->radius_meter ?? 50) }}" class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all" min="10" max="1000" required>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span class="text-xs font-bold text-slate-400">M</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="batas_tepat_waktu" class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Jam Tutup Gerbang</label>
                                <input type="time" id="batas_tepat_waktu" name="batas_tepat_waktu" value="{{ old('batas_tepat_waktu', $geofencing->batas_tepat_waktu ?? '07:00') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all" required>
                            </div>

                            <button type="submit" class="w-full bg-[#0A1D37] text-[#FFD700] px-5 py-3.5 rounded-xl font-bold text-sm hover:bg-[#14325a] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 shadow-lg shadow-[#0A1D37]/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Pengaturan
                            </button>

                        </div>

                        <div class="lg:col-span-2">
                            <div class="bg-slate-50 p-2 rounded-2xl border border-slate-200 h-full min-h-[400px] relative z-0">
                                <div id="map" class="w-full h-full rounded-xl" style="min-height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <footer class="mt-auto pt-12 pb-4 text-center">
        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
            &copy; {{ date('Y') }} PresensiGeo - SMK Muhammadiyah 4 Sragen
        </p>
        <p class="text-[10px] text-slate-400/80 font-medium mt-1">
            Versi 1.0.0 
        </p>
    </footer>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let latInput = document.getElementById('latitude');
        let lngInput = document.getElementById('longitude');
        let radiusInput = document.getElementById('radius_meter');
        let btnLokasi = document.getElementById('btn-lokasi');

        let currentLat = parseFloat(latInput.value) || -7.4268;
        let currentLng = parseFloat(lngInput.value) || 111.0222;
        let currentRadius = parseFloat(radiusInput.value) || 50;

        let map = L.map('map').setView([currentLat, currentLng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([currentLat, currentLng], {
            draggable: true
        }).addTo(map);
        
        let circle = L.circle([currentLat, currentLng], {
            color: '#FFD700',
            fillColor: '#0A1D37',
            fillOpacity: 0.2,
            radius: currentRadius
        }).addTo(map);

        function updateMapElements(lat, lng, rad) {
            if (isNaN(lat) || isNaN(lng)) return;
            
            latInput.value = lat.toFixed(8);
            lngInput.value = lng.toFixed(8);
            
            let newLatLng = new L.LatLng(lat, lng);
            marker.setLatLng(newLatLng);
            circle.setLatLng(newLatLng);
            circle.setRadius(rad);
            map.panTo(newLatLng);
        }

        map.on('click', function(e) {
            updateMapElements(e.latlng.lat, e.latlng.lng, parseFloat(radiusInput.value));
        });

        marker.on('dragend', function(e) {
            let position = marker.getLatLng();
            updateMapElements(position.lat, position.lng, parseFloat(radiusInput.value));
        });

        latInput.addEventListener('paste', function(e) {
            let pasteData = (e.clipboardData || window.clipboardData).getData('text');
            
            if(pasteData.includes(',')) {
                e.preventDefault(); 
                let coords = pasteData.split(',');
                let parsedLat = parseFloat(coords[0].trim());
                let parsedLng = parseFloat(coords[1].trim());

                if(!isNaN(parsedLat) && !isNaN(parsedLng)) {
                    updateMapElements(parsedLat, parsedLng, parseFloat(radiusInput.value) || 50);
                }
            }
        });

        latInput.addEventListener('input', function() {
            let val = parseFloat(this.value);
            if(!isNaN(val)) updateMapElements(val, parseFloat(lngInput.value) || 0, parseFloat(radiusInput.value));
        });

        lngInput.addEventListener('input', function() {
            let val = parseFloat(this.value);
            if(!isNaN(val)) updateMapElements(parseFloat(latInput.value) || 0, val, parseFloat(radiusInput.value));
        });

        radiusInput.addEventListener('input', function(e) {
            let newRad = parseFloat(e.target.value);
            if (!isNaN(newRad) && newRad > 0) {
                circle.setRadius(newRad);
            }
        });

        btnLokasi.addEventListener('click', function() {
            let originalText = this.innerHTML;
            this.innerHTML = 'Mencari Satelit...';
            this.disabled = true;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;
                    
                    updateMapElements(lat, lng, parseFloat(radiusInput.value));
                    map.setZoom(18);

                    btnLokasi.innerHTML = originalText;
                    btnLokasi.disabled = false;
                }, function(error) {
                    alert('Gagal ambil lokasi! pastikan gps dan izin lokasi sudah aktif.');
                    btnLokasi.innerHTML = originalText;
                    btnLokasi.disabled = false;
                }, {
                    enableHighAccuracy: true
                });
            } else {
                alert('Browser tidak support GPS!');
                btnLokasi.innerHTML = originalText;
                btnLokasi.disabled = false;
            }
        });
    });
</script>

@endsection