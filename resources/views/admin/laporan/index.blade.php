@extends('layouts.admin')

@section('title', 'Laporan Presensi')
@section('header', 'Laporan Kehadiran Harian')

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
            <div class="px-8 py-6 border-b border-slate-100 bg-[#0A1D37]">
                <h3 class="font-extrabold text-white text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#FFD700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Laporan Presensi
                </h3>
                <p class="text-xs text-slate-300 mt-1">Cari dan saring data absensi berdasarkan parameter yang spesifik.</p>
            </div>
            
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="p-8">
                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-extrabold text-slate-400 mb-2 uppercase tracking-widest">Cari Nama</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama siswa/guru..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 mb-2 uppercase tracking-widest">Tanggal</label>
                        <input type="text" id="kalender-mewah" name="tanggal" value="{{ $tanggal }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all cursor-pointer placeholder:text-slate-400" placeholder="Pilih Tanggal...">
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 mb-2 uppercase tracking-widest">Role Instansi</label>
                        <select name="role" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                            <option value="semua" {{ request('role') == 'semua' ? 'selected' : '' }}>Semua Role</option>
                            <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Hanya Siswa</option>
                            <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Hanya Guru</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 mb-2 uppercase tracking-widest">Status Absen</label>
                        <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir (Tepat/Telat)</option>
                            <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.laporan.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition">Reset</a>
                    <button type="submit" class="px-5 py-2.5 bg-[#0A1D37] text-[#FFD700] rounded-xl font-bold text-sm hover:bg-[#14325a] shadow-lg shadow-[#0A1D37]/20 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <div>
                    <h3 class="font-extrabold text-[#0A1D37] text-lg">Data Presensi: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Ditemukan {{ $presensis->count() }} data sesuai filter.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">No.</th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest group">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama', 'sort_order' => ($sortBy == 'nama' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-2 hover:text-[#0A1D37] transition-colors">
                                    Profil Lengkap
                                    <span class="flex flex-col text-[8px] leading-none">
                                        <svg class="w-2.5 h-2.5 {{ $sortBy == 'nama' && $sortOrder == 'asc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                        <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy == 'nama' && $sortOrder == 'desc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </span>
                                </a>
                            </th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest group">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'waktu', 'sort_order' => ($sortBy == 'waktu' && $sortOrder == 'desc') ? 'asc' : 'desc']) }}" class="flex items-center gap-2 hover:text-[#0A1D37] transition-colors">
                                    Waktu Absen
                                    <span class="flex flex-col text-[8px] leading-none">
                                        <svg class="w-2.5 h-2.5 {{ $sortBy == 'waktu' && $sortOrder == 'asc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                        <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy == 'waktu' && $sortOrder == 'desc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </span>
                                </a>
                            </th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Titik Lokasi (Buka Maps)</th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center group">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'jarak_meter', 'sort_order' => ($sortBy == 'jarak_meter' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center justify-center gap-2 hover:text-[#0A1D37] transition-colors">
                                    Jarak Pusat
                                    <span class="flex flex-col text-[8px] leading-none">
                                        <svg class="w-2.5 h-2.5 {{ $sortBy == 'jarak_meter' && $sortOrder == 'asc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                        <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy == 'jarak_meter' && $sortOrder == 'desc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </span>
                                </a>
                            </th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest group">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => ($sortBy == 'status' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-2 hover:text-[#0A1D37] transition-colors">
                                    Keterangan Status
                                    <span class="flex flex-col text-[8px] leading-none">
                                        <svg class="w-2.5 h-2.5 {{ $sortBy == 'status' && $sortOrder == 'asc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                        <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy == 'status' && $sortOrder == 'desc' ? 'text-[#0A1D37]' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </span>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        
                        @forelse($presensis as $presensi)
                            @php
                                $namaLengkap = 'Tidak Diketahui';
                                $detailRole = strtoupper($presensi->user->role ?? 'N/A');
                                
                                if ($presensi->user->role === 'siswa' && $presensi->user->siswa) {
                                    $namaLengkap = $presensi->user->siswa->nama;
                                    $detailRole = 'SISWA - KELAS ' . $presensi->user->siswa->kelas;
                                } elseif ($presensi->user->role === 'guru' && $presensi->user->guru) {
                                    $namaLengkap = $presensi->user->guru->nama;
                                    $detailRole = 'GURU - MAPEL ' . ($presensi->user->guru->mata_pelajaran ?? 'UMUM');
                                }
                            @endphp

                            <tr class="hover:bg-slate-50/80 transition duration-200 group">
                                <td class="px-8 py-5 text-center text-sm font-bold text-slate-400">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-8 py-5">
                                    <div class="font-bold text-[#0A1D37] text-sm group-hover:text-blue-600 transition-colors">{{ $namaLengkap }}</div>
                                    <div class="text-[10px] text-slate-400 font-extrabold mt-0.5">{{ $detailRole }}</div>
                                </td>
                                
                                <td class="px-8 py-5 text-slate-500 text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($presensi->waktu)->format('H:i:s') }} WIB
                                    </div>
                                </td>
                                
                                <td class="px-8 py-5 text-center">
                                    @if($presensi->latitude && $presensi->longitude)
                                        <a href="https://maps.google.com/?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 rounded-lg text-xs font-bold transition-all shadow-sm group/btn">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Lihat Map
                                        </a>
                                        <p class="text-[9px] text-slate-400 mt-1 font-mono">{{ $presensi->latitude }}, {{ $presensi->longitude }}</p>
                                    @else
                                        <span class="text-slate-400 font-bold text-xs">-</span>
                                    @endif
                                </td>
                                
                                <td class="px-8 py-5 text-center">
                                    @if($presensi->jarak_meter !== null)
                                        <span class="text-slate-700 font-black text-sm">{{ $presensi->jarak_meter }} <span class="text-xs text-slate-400 font-bold">Meter</span></span>
                                    @else
                                        <span class="text-slate-400 font-bold">-</span>
                                    @endif
                                </td>
                                
                                <td class="px-8 py-5">
                                    @if(in_array(strtolower($presensi->keterangan ?? $presensi->status), ['tepat waktu', 'hadir']))
                                        <span class="inline-flex items-center gap-1.5 text-emerald-600 bg-emerald-50 border-emerald-100 text-[10px] font-black uppercase px-3 py-1.5 rounded-full border tracking-wider">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $presensi->keterangan ?? 'Tepat Waktu' }}
                                        </span>
                                    @elseif(strtolower($presensi->keterangan ?? $presensi->status) == 'terlambat')
                                        <span class="inline-flex items-center gap-1.5 text-amber-600 bg-amber-50 border-amber-100 text-[10px] font-black uppercase px-3 py-1.5 rounded-full border tracking-wider">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            TERLAMBAT
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-blue-600 bg-blue-50 border-blue-100 text-[10px] font-black uppercase px-3 py-1.5 rounded-full border tracking-wider">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $presensi->status }}
                                            @if($presensi->alasan)
                                                <span class="font-normal capitalize ml-1 border-l border-blue-200 pl-1">({{ Str::limit($presensi->alasan, 15) }})</span>
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center">
                                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-extrabold text-base">Data Presensi Kosong</p>
                                    <p class="text-xs text-slate-400 mt-1">tidak ada riwayat absensi yang cocok dengan filter yang dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="mt-auto pt-12 pb-4 text-center">
        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
            © {{ date('Y') }} PresensiGeo - SMK Muhammadiyah 4 Sragen
        </p>
        <p class="text-[10px] text-slate-400/80 font-medium mt-1">
            Versi 1.0.0 
        </p>
    </footer>
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
    });
</script>

@endsection