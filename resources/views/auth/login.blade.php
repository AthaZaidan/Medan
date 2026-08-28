<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Pemko Medan QPI 2026</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white flex flex-col justify-center items-center p-4 font-sans text-slate-900">

    <div class="w-full max-w-sm space-y-6 my-auto">
        
        {{-- Minimalist Header --}}
        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo-pemko-medan.jpg') }}" alt="Logo Pemko Medan" class="w-14 h-14 object-contain mx-auto">
            <div>
                <h1 class="text-lg font-extrabold tracking-wide text-slate-900 uppercase">PEMERINTAH KOTA MEDAN</h1>
                <p class="text-xs font-semibold text-slate-500">Sistem Evaluasi QPI Kewilyahan 2026</p>
            </div>
        </div>

        {{-- Minimalist Card Form --}}
        <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-extrabold text-slate-900">Masuk ke Akun</h2>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan email dan password Anda.</p>
            </div>

            @if(session('success'))
                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-900 text-xs font-bold space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-1.5">
                            <span class="text-red-500">•</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-[11px] font-extrabold text-slate-700 mb-1 uppercase tracking-wider">
                        Alamat Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@medan.go.id"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-extrabold text-slate-700 mb-1 uppercase tracking-wider">
                        Password
                    </label>
                    <input type="password" name="password" id="password" required
                           placeholder="••••••••"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent transition-all">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-900 rounded border-slate-300 focus:ring-blue-900">
                        <span class="text-xs font-semibold text-slate-600">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2.5 px-4 bg-blue-900 hover:bg-blue-800 text-white font-extrabold rounded-xl shadow-xs transition-all text-xs flex items-center justify-center gap-2 mt-2">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Minimalist Footer --}}
        <div class="text-center">
            <p class="text-[11px] font-medium text-slate-400">© 2026 Pemerintah Kota Medan</p>
        </div>

    </div>

</body>
</html>
