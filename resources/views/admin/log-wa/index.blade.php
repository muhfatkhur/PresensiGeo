@extends('layouts.admin')

@section('title', 'Log WhatsApp')
@section('header', 'Riwayat Notifikasi WA')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar { 
        font-family: inherit !important; 
        border: 1px solid #e2e8f0 !important; 
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important; 
        border-radius: 1rem !important; 
        z-index: 9999 !important; 
    }
    .flatpickr-day.selected, .flatpickr-day.selected:hover { 
        background: #0A1D37 !important; 
        border-color: #0A1D37 !important; 
    }
</style>

<div class="flex flex-col min-h-[calc(100vh-8rem)]">
    <div class="flex-1">

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-100 bg-[#0A1D37] flex justify-between items-center">
                <div>
                    <h3 class="font-extrabold text-white text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#FFD700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Filter Riwayat Log WA
                    </h3>
                    <p class="text-xs text-slate-300 mt-1">Pilih tanggal untuk melihat riwayat pesan WhatsApp terdahulu.</p>
                </div>
            </div>
            
            <form method="GET" action="{{ route('admin.log-wa.index') }}" class="p-8 flex flex-col sm:flex-row gap-5 items-end">
                <div class="w-full sm:w-1/3">
                    <label class="block text-[10px] font-extrabold text-slate-400 mb-2 uppercase tracking-widest">Tanggal Log</label>
                    <input type="text" id="kalender-mewah" name="tanggal" value="{{ $tanggal }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all cursor-pointer placeholder:text-slate-400" placeholder="Pilih Tanggal...">
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-[#0A1D37] text-[#FFD700] rounded-xl font-bold text-sm hover:bg-[#14325a] shadow-lg shadow-[#0A1D37]/20 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Tampilkan Riwayat
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="font-extrabold text-[#0A1D37] text-lg flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        Log Pengiriman: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Sistem otomatis memperbarui data log setiap 5 detik.</p>
                </div>
                
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <span id="indikator-koneksi" class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-emerald-100">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Realtime Aktif
                    </span>

                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" id="live-search" placeholder="Cari nama / nomor..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Penerima / Nama</th>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Nomor Tujuan</th>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Isi Pesan WA</th>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Status Kirim</th>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Waktu Eksekusi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-log-body" class="divide-y divide-slate-100">
                        @include('admin.log-wa.partials.table', ['logs' => $logs])
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <footer class="mt-auto pt-4 pb-4 text-center">
        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
            © {{ date('Y') }} PresensiGeo - SMK Muhammadiyah 4 Sragen
        </p>
    </footer>
</div>

<div id="modalPesan" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModalPesan()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full scale-95 opacity-0 duration-300" id="modalContentPesan">
            
            <div class="bg-emerald-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Detail Pesan WhatsApp
                </h3>
                <button onclick="tutupModalPesan()" class="text-emerald-100 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 sm:p-8">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <pre id="isiPesanFull" class="text-sm text-slate-700 font-mono whitespace-pre-wrap font-medium"></pre>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100">
                <button type="button" onclick="tutupModalPesan()" class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition">Tutup Detail</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        flatpickr("#kalender-mewah", {
            locale: "id", 
            dateFormat: "Y-m-d", 
            altInput: true, 
            altFormat: "d F Y", 
            defaultDate: "{{ $tanggal }}",
            disableMobile: "true" 
        });

        const searchInput = document.getElementById('live-search');
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('.baris-log');

            rows.forEach(row => {
                let text = '';
                const cols = row.querySelectorAll('.kolom-pencarian');
                cols.forEach(col => {
                    text += col.textContent.toLowerCase() + ' ';
                });

                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        setInterval(function() {
            const currentSelectedDate = document.getElementById('kalender-mewah').value;
            
            fetch(`{{ route('admin.log-wa.index') }}?tanggal=${currentSelectedDate}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('tabel-log-body').innerHTML = data.html;
                searchInput.dispatchEvent(new Event('input')); 
            })
            .catch(error => {
                const indikator = document.getElementById('indikator-koneksi');
                indikator.classList.remove('bg-emerald-50', 'text-emerald-600', 'border-emerald-100');
                indikator.classList.add('bg-red-50', 'text-red-600', 'border-red-100');
                indikator.innerHTML = 'Koneksi Terputus';
            });
        }, 5000);
    });

    const modalPesan = document.getElementById('modalPesan');
    const contentPesan = document.getElementById('modalContentPesan');

    function lihatPesan(pesan) {
        document.getElementById('isiPesanFull').textContent = pesan;
        modalPesan.classList.remove('hidden');
        setTimeout(() => {
            contentPesan.classList.remove('scale-95', 'opacity-0');
            contentPesan.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function tutupModalPesan() {
        contentPesan.classList.remove('scale-100', 'opacity-100');
        contentPesan.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modalPesan.classList.add('hidden'); }, 300);
    }
</script>

@endsection