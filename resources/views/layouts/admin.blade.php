<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Presensi SMK Muh 4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F4F7FE] text-slate-800 flex h-screen overflow-hidden">
    
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-72 bg-[#0A1D37] text-white flex flex-col z-50 shadow-2xl transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        
        <div class="h-24 flex items-center px-6 border-b border-white/10 gap-4">
            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border-2 border-[#FFD700] shadow-[0_0_20px_rgba(255,215,0,0.3)] p-1 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
            </div>
            
            <div>
                <h1 class="font-extrabold text-[15px] tracking-wide text-white">Presensi<span class="text-[#FFD700]">Geo</span></h1>
                <p class="text-[9px] font-bold text-blue-300 uppercase tracking-[0.15em] mt-0.5 opacity-80">SMK Muh 4 Sragen</p>
            </div>
            
            <button onclick="toggleSidebar()" class="md:hidden ml-auto text-blue-300 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            <p class="text-[10px] font-extrabold text-blue-400/80 uppercase tracking-[0.2em] mb-4 px-4">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->is('admin') ? 'bg-gradient-to-r from-[#FFD700] to-[#F1C40F] text-[#0A1D37] font-bold shadow-[0_4px_20px_rgba(255,215,0,0.3)]' : 'text-blue-100/70 hover:bg-white/10 hover:text-white font-medium' }} rounded-2xl transition-all duration-300 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->is('admin/siswa*') ? 'bg-gradient-to-r from-[#FFD700] to-[#F1C40F] text-[#0A1D37] font-bold shadow-[0_4px_20px_rgba(255,215,0,0.3)]' : 'text-blue-100/70 hover:bg-white/10 hover:text-white font-medium' }} rounded-2xl transition-all duration-300 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Data Siswa
            </a>

            <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->is('admin/guru*') ? 'bg-gradient-to-r from-[#FFD700] to-[#F1C40F] text-[#0A1D37] font-bold shadow-[0_4px_20px_rgba(255,215,0,0.3)]' : 'text-blue-100/70 hover:bg-white/10 hover:text-white font-medium' }} rounded-2xl transition-all duration-300 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Data Guru
            </a>

            <a href="{{ route('admin.geofencing.index') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->is('admin/geofencing*') ? 'bg-gradient-to-r from-[#FFD700] to-[#F1C40F] text-[#0A1D37] font-bold shadow-[0_4px_20px_rgba(255,215,0,0.3)]' : 'text-blue-100/70 hover:bg-white/10 hover:text-white font-medium' }} rounded-2xl transition-all duration-300 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Setting Lokasi
            </a>

            <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->is('admin/laporan*') ? 'bg-gradient-to-r from-[#FFD700] to-[#F1C40F] text-[#0A1D37] font-bold shadow-[0_4px_20px_rgba(255,215,0,0.3)]' : 'text-blue-100/70 hover:bg-white/10 hover:text-white font-medium' }} rounded-2xl transition-all duration-300 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan Presensi
            </a>

            <a href="{{ route('admin.log-wa.index') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->is('admin/log-wa*') ? 'bg-gradient-to-r from-[#FFD700] to-[#F1C40F] text-[#0A1D37] font-bold shadow-[0_4px_20px_rgba(255,215,0,0.3)]' : 'text-blue-100/70 hover:bg-white/10 hover:text-white font-medium' }} rounded-2xl transition-all duration-300 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Log WhatsApp
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-6 lg:px-10 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-xl font-extrabold text-[#0A1D37]">@yield('header')</h2>
            </div>

            <div class="flex items-center gap-5">
                <div class="hidden sm:block text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Selamat Datang,</p>
                    <p class="text-sm font-extrabold text-[#0A1D37]">Administrator</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-slate-100 border-2 border-slate-200 overflow-hidden hidden sm:flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                
                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-red-600 hover:text-white transition-all duration-300 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>