@forelse($logs as $log)
    @php
        $namaLengkap = 'Tidak Diketahui';
        
        if ($log->presensi && $log->presensi->user) {
            if ($log->presensi->user->role === 'siswa' && $log->presensi->user->siswa) {
                $namaLengkap = $log->presensi->user->siswa->nama;
            } elseif ($log->presensi->user->role === 'guru' && $log->presensi->user->guru) {
                $namaLengkap = $log->presensi->user->guru->nama;
            } else {
                $namaLengkap = $log->presensi->user->name;
            }
        }
    @endphp

    <tr class="hover:bg-slate-50/80 transition duration-200 group baris-log">
        <td class="px-8 py-5">
            <div class="font-bold text-[#0A1D37] text-sm kolom-pencarian">{{ $namaLengkap }}</div>
        </td>
        
        <td class="px-8 py-5">
            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium kolom-pencarian">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                {{ $log->no_wa }}
            </div>
        </td>
        
        <td class="px-8 py-5">
            <button onclick="lihatPesan(`{{ htmlspecialchars($log->pesan) }}`)" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Baca Pesan
            </button>
        </td>
        
        <td class="px-8 py-5">
            @if(in_array(strtolower($log->status), ['sent', 'success', 'berhasil']))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    TERKIRIM
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 text-red-600 border border-red-100 text-[10px] font-black uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    GAGAL
                </span>
            @endif
        </td>
        
        <td class="px-8 py-5 text-slate-500 text-sm font-medium">
            {{ $log->created_at->format('d/m/Y H:i:s') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-8 py-16 text-center">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <p class="text-slate-500 font-extrabold text-base">Log Masih Kosong</p>
            <p class="text-xs text-slate-400 mt-1">Belum ada riwayat notifikasi yang dikirim ke wali murid.</p>
        </td>
    </tr>
@endforelse