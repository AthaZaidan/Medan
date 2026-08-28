<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard QPI') — Pemko Medan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/40 z-40 lg:hidden"></div>

    {{-- Left Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transform transition-transform duration-150 lg:translate-x-0 flex flex-col">

        {{-- App Header --}}
        <div class="h-14 px-5 border-b border-slate-200 flex items-center gap-3 bg-white">
            <img src="{{ asset('images/logo-pemko-medan.jpg') }}" alt="Logo Pemko Medan" class="w-8 h-8 object-contain flex-shrink-0">
            <div class="min-w-0">
                <div class="font-bold text-slate-900 text-xs tracking-tight truncate">PEMKO MEDAN</div>
                <div class="text-[11px] text-slate-500 font-medium leading-none">QPI Kewilyahan 2026</div>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-3 py-4 space-y-1">
            <div class="px-2 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                Navigasi Utama
            </div>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded text-xs font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-blue-900 font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('input.progress') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded text-xs font-semibold transition-colors {{ request()->routeIs('input.*') ? 'bg-slate-100 text-blue-900 font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
                Input Data
            </a>

            <div class="px-2 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider pt-3">
                Pengaturan
            </div>

            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded text-xs font-semibold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-100 text-blue-900 font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Admin Control
                </a>
            @endif

            <a href="{{ route('parameter.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded text-xs font-semibold transition-colors {{ request()->routeIs('parameter.*') ? 'bg-slate-100 text-blue-900 font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 12h8.25"/>
                </svg>
                Parameter
            </a>

            <a href="{{ route('panduan.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded text-xs font-semibold transition-colors {{ request()->routeIs('panduan.*') ? 'bg-slate-100 text-blue-900 font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25"/>
                </svg>
                Panduan
            </a>
        </nav>

        {{-- Footer --}}
        <div class="p-3.5 border-t border-slate-200 text-center">
            <span class="text-[10px] text-slate-400 font-medium">Kota Medan © 2026</span>
        </div>
    </aside>

    {{-- Main Container --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">
        {{-- Top Bar Header --}}
        <header class="h-14 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-slate-900">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <h1 class="text-sm font-bold text-slate-900">@yield('heading', 'Dashboard Utama')</h1>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                        class="no-print hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.6 0-1.104-.467-1.12-1.066L5.88 18m11.78 0H6.34"/>
                    </svg>
                    Cetak
                </button>

                @auth
                    <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-slate-500 mt-0.5">
                                @if(auth()->user()->isAdmin())
                                    <span class="text-purple-700 font-bold">Admin</span>
                                @elseif(auth()->user()->kecamatan)
                                    <span class="text-blue-800 font-semibold">📍 {{ auth()->user()->kecamatan->nama }}</span>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Keluar dari sistem" class="px-2 py-1 bg-slate-100 hover:bg-red-50 hover:text-red-600 rounded text-xs font-semibold text-slate-700 transition-colors border border-slate-300">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </header>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="mx-6 mt-4 p-3 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 p-3 rounded bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Content --}}
        <main class="p-6 flex-1 space-y-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
