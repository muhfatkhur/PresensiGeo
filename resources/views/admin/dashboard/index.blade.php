@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Ringkasan Kehadiran Hari Ini')

@section('content')

<div class="flex flex-col min-h-[calc(100vh-8rem)]">
    <div class="flex-1">

        @if(session('success'))
            <div id="alert-success" class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-xl mb-6 flex items-center gap-3 shadow-sm transition-opacity duration-500 opacity-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 sm:px-8 sm:py-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="w-14 h-14 rounded-full flex items-center justify-center shrink-0 shadow-inner {{ ($geofencing && $geofencing->is_open) ? 'bg-emerald-50 text-emerald-500' : 'bg-red-50 text-red-500' }}">
                    @if($geofencing && $geofencing->is_open)
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    @else
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    @endif
                </div>
                <div>
                    <h3 class="font-extrabold text-[#0A1D37] text-lg">Status Gerbang Presensi: <span class="{{ ($geofencing && $geofencing->is_open) ? 'text-emerald-500' : 'text-red-500' }} uppercase">{{ ($geofencing && $geofencing->is_open) ? 'TERBUKA' : 'DITUTUP' }}</span></h3>
                    <p class="text-xs text-slate-400 mt-1">Kontrol akses masuk untuk seluruh siswa dan guru.</p>
                </div>
            </div>
            
            <button type="button" onclick="openModalGerbang()" class="w-full md:w-auto px-6 py-3.5 rounded-xl font-bold text-sm transition-all shadow-lg flex items-center justify-center gap-2 {{ ($geofencing && $geofencing->is_open) ? 'bg-red-50 text-red-600 hover:bg-red-100 shadow-red-500/10' : 'bg-[#0A1D37] text-[#FFD700] hover:bg-[#14325a] shadow-[#0A1D37]/20' }}">
                @if($geofencing && $geofencing->is_open)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Tutup Presensi Sekarang
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    Buka Presensi Sekarang
                @endif
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 text-emerald-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hadir Tepat</p>
                    </div>
                    
                    <h3 class="text-4xl font-extrabold text-[#0A1D37]">{{ ($hadirSiswa ?? 0) + ($hadirGuru ?? 0) }} <span class="text-sm font-medium text-slate-400">Total</span></h3>
                    
                    <div class="mt-4 flex gap-2">
                        <span class="inline-flex items-center bg-emerald-50/50 border border-emerald-100 text-emerald-700 text-[10px] font-extrabold px-2.5 py-1 rounded-lg tracking-wide shadow-sm">
                            {{ $hadirSiswa ?? 0 }} SISWA
                        </span>
                        <span class="inline-flex items-center bg-emerald-50/50 border border-emerald-100 text-emerald-700 text-[10px] font-extrabold px-2.5 py-1 rounded-lg tracking-wide shadow-sm">
                            {{ $hadirGuru ?? 0 }} GURU
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 text-amber-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Terlambat</p>
                    </div>
                    
                    <h3 class="text-4xl font-extrabold text-[#0A1D37]">{{ ($terlambatSiswa ?? 0) + ($terlambatGuru ?? 0) }} <span class="text-sm font-medium text-slate-400">Total</span></h3>
                    
                    <div class="mt-4 flex gap-2">
                        <span class="inline-flex items-center bg-amber-50/50 border border-amber-100 text-amber-700 text-[10px] font-extrabold px-2.5 py-1 rounded-lg tracking-wide shadow-sm">
                            {{ $terlambatSiswa ?? 0 }} SISWA
                        </span>
                        <span class="inline-flex items-center bg-amber-50/50 border border-amber-100 text-amber-700 text-[10px] font-extrabold px-2.5 py-1 rounded-lg tracking-wide shadow-sm">
                            {{ $terlambatGuru ?? 0 }} GURU
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-red-500 rounded-l-[2rem]"></div>
                <div class="absolute -right-6 -top-6 text-red-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <div class="relative z-10 pl-2">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-red-500 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs font-bold text-red-500 uppercase tracking-widest">Tidak Hadir</p>
                    </div>
                    
                    <h3 class="text-4xl font-extrabold text-red-600">{{ ($tidakHadirSiswa ?? 0) + ($tidakHadirGuru ?? 0) }} <span class="text-sm font-medium text-red-400">Total</span></h3>
                    
                    <div class="mt-4 flex gap-2">
                        <span class="inline-flex items-center bg-red-50/50 border border-red-100 text-red-700 text-[10px] font-extrabold px-2.5 py-1 rounded-lg tracking-wide shadow-sm">
                            {{ $tidakHadirSiswa ?? 0 }} SISWA
                        </span>
                        <span class="inline-flex items-center bg-red-50/50 border border-red-100 text-red-700 text-[10px] font-extrabold px-2.5 py-1 rounded-lg tracking-wide shadow-sm">
                            {{ $tidakHadirGuru ?? 0 }} GURU
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-white">
                <h3 class="font-extrabold text-[#0A1D37] text-lg">Aktivitas Presensi Terbaru</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Nama / Keterangan</th>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Waktu Absen</th>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Jarak</th>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentActivities ?? [] as $activity)
                            <tr class="hover:bg-slate-50/80 transition duration-200 group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-[#0A1D37] text-sm group-hover:text-blue-600 transition-colors">{{ $activity['name'] }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold mt-0.5">{{ $activity['keterangan_role'] }}</div>
                                </td>
                                <td class="px-8 py-5 text-slate-500 text-sm font-medium">
                                    {{ $activity['time'] }}
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($activity['distance'] !== '-')
                                        <span class="inline-flex items-center gap-1 {{ $activity['status_radius'] == 'sah' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/50' : 'bg-red-50 text-red-700 border-red-200/50' }} px-3 py-1.5 rounded-full text-[10px] font-extrabold border shadow-sm">
                                            @if($activity['status_radius'] == 'sah')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            @else
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            @endif
                                            {{ $activity['distance'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    @if(in_array(strtoupper($activity['status_kehadiran']), ['TEPAT WAKTU']))
                                        <span class="inline-flex items-center gap-1.5 text-emerald-600 bg-emerald-50 border-emerald-100 text-[10px] font-bold uppercase px-3 py-1.5 rounded-xl border">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $activity['status_kehadiran'] }}
                                        </span>
                                    @elseif(in_array(strtoupper($activity['status_kehadiran']), ['TERLAMBAT']))
                                        <span class="inline-flex items-center gap-1.5 text-amber-600 bg-amber-50 border-amber-100 text-[10px] font-bold uppercase px-3 py-1.5 rounded-xl border">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $activity['status_kehadiran'] }}
                                        </span>
                                    @elseif(in_array(strtoupper($activity['status_kehadiran']), ['IZIN', 'SAKIT']))
                                        <span class="inline-flex items-center gap-1.5 text-blue-600 bg-blue-50 border-blue-100 text-[10px] font-bold uppercase px-3 py-1.5 rounded-xl border">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $activity['status_kehadiran'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-red-600 bg-red-50 border-red-100 text-[10px] font-bold uppercase px-3 py-1.5 rounded-xl border">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $activity['status_kehadiran'] }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-medium text-sm">Belum ada aktivitas presensi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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

<div id="modalGerbang" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModalGerbang()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md w-full scale-95 opacity-0 duration-300" id="modalContentGerbang">
            
            <div class="p-8 text-center">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-lg {{ ($geofencing && $geofencing->is_open) ? 'bg-red-100 shadow-red-100 text-red-500' : 'bg-[#0A1D37] shadow-[#0A1D37]/30 text-[#FFD700]' }}">
                    @if($geofencing && $geofencing->is_open)
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    @else
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    @endif
                </div>
                <h3 class="text-xl font-black text-[#0A1D37] mb-2">
                    {{ ($geofencing && $geofencing->is_open) ? 'Tutup Gerbang Presensi?' : 'Buka Gerbang Presensi?' }}
                </h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">
                    @if($geofencing && $geofencing->is_open)
                        yakin menutup gerbang sekarang? <strong class="text-red-500">Siswa yang belum absen akan otomatis divonis Tidak Hadir (Alpha).</strong>
                    @else
                        Siswa dan guru akan diizinkan kembali untuk melakukan presensi kehadiran dari perangkat mereka.
                    @endif
                </p>
            </div>

            <div class="bg-slate-50 px-6 py-5 flex justify-center gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModalGerbang()" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition w-full">tidak jadi</button>
                <form action="{{ route('admin.geofencing.toggle') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 text-white rounded-xl font-bold text-sm shadow-lg transition flex items-center justify-center gap-2 {{ ($geofencing && $geofencing->is_open) ? 'bg-red-500 hover:bg-red-600 shadow-red-500/30' : 'bg-[#0A1D37] hover:bg-[#14325a] shadow-[#0A1D37]/30 text-[#FFD700]' }}">
                        @if($geofencing && $geofencing->is_open)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Ya, Tutup!
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            Ya, Buka!
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alertBox = document.getElementById('alert-success');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.remove('opacity-100');
                alertBox.classList.add('opacity-0');
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
    });

    const modalGerbang = document.getElementById('modalGerbang');
    const contentGerbang = document.getElementById('modalContentGerbang');

    function openModalGerbang() {
        modalGerbang.classList.remove('hidden');
        setTimeout(() => {
            contentGerbang.classList.remove('scale-95', 'opacity-0');
            contentGerbang.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModalGerbang() {
        contentGerbang.classList.remove('scale-100', 'opacity-100');
        contentGerbang.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modalGerbang.classList.add('hidden'); }, 300);
    }
</script>

@endsection