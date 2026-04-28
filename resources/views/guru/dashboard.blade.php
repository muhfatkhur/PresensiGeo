@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <div class="mb-8 mt-6 text-center sm:text-left">
            <h2 class="text-2xl font-black text-[#0A1D37]">Pantau Presensi Siswa</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">
                Data absensi real-time hari ini: 
                <span class="font-bold text-[#0A1D37]">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</span>
            </p>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="px-6 py-5 bg-[#0A1D37] border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="font-extrabold text-white text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#FFD700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Laporan Absensi Siswa
                </h3>
                
                <form method="GET" action="{{ route('guru.dashboard') }}" class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-48">
                        <select name="kelas" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-2.5 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:bg-white focus:text-[#0A1D37] focus:ring-2 focus:ring-[#FFD700] focus:border-transparent outline-none transition-all appearance-none cursor-pointer font-bold">
                            <option value="semua" class="text-slate-800" {{ empty($kelasFilter) || $kelasFilter == 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                            @foreach($daftarKelas as $kelas)
                                <option value="{{ $kelas }}" class="text-slate-800" {{ $kelasFilter == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-white/70">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Siswa & Kelas</th>
                            <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Waktu</th>
                            <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Jarak</th>
                            <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($presensis as $presensi)
                            <tr class="hover:bg-slate-50/80 transition duration-200 group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#0A1D37] text-sm group-hover:text-blue-600 transition-colors">{{ $presensi->user->siswa->nama ?? ($presensi->user->name ?? 'N/A') }}</div>
                                    <div class="text-[10px] text-slate-400 font-extrabold mt-0.5 uppercase">KELAS {{ $presensi->user->siswa->kelas ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($presensi->waktu)->format('H:i') }} WIB
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($presensi->jarak_meter !== null)
                                        <span class="text-slate-700 font-black text-sm">{{ $presensi->jarak_meter }} <span class="text-[10px] text-slate-400 font-bold">m</span></span>
                                    @else
                                        <span class="text-slate-400 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusRaw = strtolower($presensi->keterangan ?? $presensi->status);
                                        $badgeClass = 'bg-blue-50 text-blue-600 border-blue-100';
                                        
                                        if (in_array($statusRaw, ['tepat waktu', 'hadir'])) {
                                            $badgeClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                        } elseif ($statusRaw == 'terlambat') {
                                            $badgeClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                        } elseif (in_array($statusRaw, ['alpha', 'tidak hadir'])) {
                                            $badgeClass = 'bg-red-50 text-red-600 border-red-100';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full border {{ $badgeClass }} text-[10px] font-black uppercase tracking-wider">
                                        {{ $presensi->keterangan ?? strtoupper($presensi->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-extrabold text-base">Belum Ada Absensi</p>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada siswa @if($kelasFilter && $kelasFilter != 'semua') di kelas {{ $kelasFilter }} @endif yang absen hari ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <footer class="mt-auto py-6 text-center border-t border-slate-100">
        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">
            © {{ date('Y') }} PresensiGeo • Dashboard Monitoring Guru
        </p>
    </footer>
@endsection