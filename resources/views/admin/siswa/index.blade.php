@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('header', 'Manajemen Data Siswa')

@section('content')

@php
    function getSortIcon($column, $currentSort, $currentOrder) {
        if ($currentSort !== $column) {
            return '<svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>';
        }
        if ($currentOrder === 'asc') {
            return '<svg class="w-3 h-3 text-[#FFD700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>';
        }
        return '<svg class="w-3 h-3 text-[#FFD700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
    }

    function getSortUrl($column, $currentSort, $currentOrder) {
        $order = ($currentSort === $column && $currentOrder === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery(['sort' => $column, 'order' => $order]);
    }
@endphp

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
            
            <div class="px-8 py-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between bg-white gap-4">
                <div>
                    <h3 class="font-extrabold text-[#0A1D37] text-lg">Daftar Siswa Terdaftar</h3>
                    <p class="text-xs text-slate-400 mt-1">Total: {{ isset($siswas) ? $siswas->count() : 0 }} Siswa</p>
                </div>
                
                <button onclick="openModalTambah()" class="bg-[#0A1D37] text-[#FFD700] px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-[#14325a] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 shadow-lg shadow-[#0A1D37]/20 w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Siswa
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">No</th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest cursor-pointer hover:bg-slate-100 transition group" onclick="window.location='{{ getSortUrl('nama', $sort ?? '', $order ?? '') }}'">
                                <div class="flex items-center gap-2">
                                    Nama Lengkap
                                    {!! getSortIcon('nama', $sort ?? '', $order ?? '') !!}
                                </div>
                            </th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest cursor-pointer hover:bg-slate-100 transition group" onclick="window.location='{{ getSortUrl('email', $sort ?? '', $order ?? '') }}'">
                                <div class="flex items-center gap-2">
                                    Email Login
                                    {!! getSortIcon('email', $sort ?? '', $order ?? '') !!}
                                </div>
                            </th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest cursor-pointer hover:bg-slate-100 transition group" onclick="window.location='{{ getSortUrl('kelas', $sort ?? '', $order ?? '') }}'">
                                <div class="flex items-center gap-2">
                                    Kelas
                                    {!! getSortIcon('kelas', $sort ?? '', $order ?? '') !!}
                                </div>
                            </th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest cursor-pointer hover:bg-slate-100 transition group" onclick="window.location='{{ getSortUrl('no_wa_wali', $sort ?? '', $order ?? '') }}'">
                                <div class="flex items-center gap-2">
                                    Nomor WA Wali
                                    {!! getSortIcon('no_wa_wali', $sort ?? '', $order ?? '') !!}
                                </div>
                            </th>
                            
                            <th class="px-8 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        
                        @forelse($siswas ?? [] as $siswa)
                            <tr class="hover:bg-slate-50/80 transition duration-200 group">
                                <td class="px-8 py-5 text-center">
                                    <span class="text-xs font-bold text-slate-400">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-[#0A1D37] text-sm group-hover:text-blue-600 transition-colors">{{ $siswa->nama }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-slate-500 text-sm font-medium">{{ $siswa->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold">{{ $siswa->kelas }}</span>
                                </td>
                                <td class="px-8 py-5 text-slate-500 text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $siswa->no_wa_wali }}
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button" onclick="openModalEdit('{{ $siswa->id }}', '{{ addslashes($siswa->nama) }}', '{{ $siswa->user->email ?? '' }}', '{{ $siswa->kelas }}', '{{ $siswa->no_wa_wali }}')" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Siswa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        <button type="button" onclick="openModalHapus('{{ $siswa->id }}', '{{ addslashes($siswa->nama) }}')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Siswa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-slate-500 font-bold">Belum ada data siswa.</p>
                                    <p class="text-xs text-slate-400 mt-1">Silakan klik "Tambah Siswa" untuk memasukkan data baru.</p>
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
            &copy; {{ date('Y') }} PresensiGeo - SMK Muhammadiyah 4 Sragen
        </p>
        <p class="text-[10px] text-slate-400/80 font-medium mt-1">
            Versi 1.0.0
        </p>
    </footer>

</div>

<div id="modalTambah" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModalTambah()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full scale-95 opacity-0 duration-300" id="modalContentTambah">
            
            <div class="bg-[#0A1D37] px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#FFD700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Tambah Data Siswa
                </h3>
                <button onclick="closeModalTambah()" class="text-slate-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                <div class="p-6 sm:p-8 space-y-5">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap Siswa *</label>
                        <input type="text" name="nama" required placeholder="Contoh: Ahmad Fauzi" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Email Login *</label>
                            <input type="email" name="email" required placeholder="siswa@smk.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Password *</label>
                            <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Kelas *</label>
                        <select name="kelas" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>-- Pilih Kelas --</option>
                            <option value="X RPL 1">X RPL 1</option>
                            <option value="X RPL 2">X RPL 2</option>
                            <option value="XI RPL 1">XI RPL 1</option>
                            <option value="XII RPL 1">XII RPL 1</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1 uppercase tracking-wide">Nomor WA Wali Murid *</label>
                        <p class="text-[10px] text-slate-400 mb-2">Wajib diawali dengan '62' (Contoh: 6281234567890)</p>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="number" name="no_wa_wali" required placeholder="62812345..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#0A1D37] focus:border-transparent outline-none transition-all">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="closeModalTambah()" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#0A1D37] text-[#FFD700] rounded-xl font-bold text-sm hover:bg-[#14325a] shadow-lg shadow-[#0A1D37]/20 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModalEdit()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full scale-95 opacity-0 duration-300" id="modalContentEdit">
            
            <div class="bg-amber-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Data Siswa
                </h3>
                <button onclick="closeModalEdit()" class="text-amber-100 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="formEditSiswa" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 sm:p-8 space-y-5">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap Siswa *</label>
                        <input type="text" id="edit_nama" name="nama" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Email Login *</label>
                            <input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Password Baru</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tak diubah" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all placeholder:text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2 uppercase tracking-wide">Kelas *</label>
                        <select id="edit_kelas" name="kelas" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                            <option value="X RPL 1">X RPL 1</option>
                            <option value="X RPL 2">X RPL 2</option>
                            <option value="XI RPL 1">XI RPL 1</option>
                            <option value="XII RPL 1">XII RPL 1</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1 uppercase tracking-wide">Nomor WA Wali Murid *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="number" id="edit_wa" name="no_wa_wali" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="closeModalEdit()" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl font-bold text-sm hover:bg-amber-600 shadow-lg shadow-amber-500/30 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalHapus" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModalHapus()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md w-full scale-95 opacity-0 duration-300" id="modalContentHapus">
            
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-lg shadow-red-100">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-[#0A1D37] mb-2">Hapus Data Siswa?</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">
                    Yakin hapus data <strong id="hapus_nama" class="text-red-600"></strong>? Data absen dan akses login siswa ini juga akan dihapus permanen.
                </p>
            </div>

            <div class="bg-slate-50 px-6 py-5 flex justify-center gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModalHapus()" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition w-full">Tidak Jadi</button>
                <form id="formHapusSiswa" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-6 py-3 bg-red-500 text-white rounded-xl font-bold text-sm hover:bg-red-600 shadow-lg shadow-red-500/30 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const baseUrl = "{{ url('/admin/siswa') }}";

    const modalTambah = document.getElementById('modalTambah');
    const contentTambah = document.getElementById('modalContentTambah');

    const modalEdit = document.getElementById('modalEdit');
    const contentEdit = document.getElementById('modalContentEdit');

    const modalHapus = document.getElementById('modalHapus');
    const contentHapus = document.getElementById('modalContentHapus');

    function openModalTambah() {
        modalTambah.classList.remove('hidden');
        setTimeout(() => {
            contentTambah.classList.remove('scale-95', 'opacity-0');
            contentTambah.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModalTambah() {
        contentTambah.classList.remove('scale-100', 'opacity-100');
        contentTambah.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modalTambah.classList.add('hidden'); }, 300);
    }

    function openModalEdit(id, nama, email, kelas, wa) {
        document.getElementById('formEditSiswa').action = baseUrl + '/' + id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_kelas').value = kelas;
        document.getElementById('edit_wa').value = wa;

        modalEdit.classList.remove('hidden');
        setTimeout(() => {
            contentEdit.classList.remove('scale-95', 'opacity-0');
            contentEdit.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModalEdit() {
        contentEdit.classList.remove('scale-100', 'opacity-100');
        contentEdit.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modalEdit.classList.add('hidden'); }, 300);
    }

    function openModalHapus(id, nama) {
        document.getElementById('formHapusSiswa').action = baseUrl + '/' + id;
        document.getElementById('hapus_nama').innerText = nama;

        modalHapus.classList.remove('hidden');
        setTimeout(() => {
            contentHapus.classList.remove('scale-95', 'opacity-0');
            contentHapus.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModalHapus() {
        contentHapus.classList.remove('scale-100', 'opacity-100');
        contentHapus.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modalHapus.classList.add('hidden'); }, 300);
    }
</script>

@endsection