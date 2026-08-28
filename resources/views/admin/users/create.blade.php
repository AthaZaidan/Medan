@extends('layouts.app')

@section('title', 'Tambah Akun Baru — Admin Control')
@section('heading', 'Admin Control — Tambah Pengguna Baru')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Back Link --}}
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-blue-900 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
        Kembali ke Daftar Pengguna
    </a>

    <div class="admin-card p-6 space-y-6" x-data="{ role: '{{ old('role', 'user_area') }}' }">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="text-base font-bold text-slate-900">Form Tambah Akun Pengguna</h2>
            <p class="text-xs text-slate-500 mt-0.5">Buat akun untuk Administrator atau Operator Kecamatan.</p>
        </div>

        @if($errors->any())
            <div class="p-3 rounded bg-red-50 border border-red-200 text-red-700 text-xs font-semibold space-y-1">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       placeholder="contoh: Operator Medan Tembung"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       placeholder="contoh: tembung@medan.go.id"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Password</label>
                <input type="password" name="password" id="password" required
                       placeholder="Minimal 6 karakter"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Peran (Role)</label>
                <select name="role" id="role" x-model="role" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
                    <option value="user_area">Operator Area Kecamatan (Hanya Akses 1 Kecamatan)</option>
                    <option value="admin">Administrator System (Akses Seluruh Kota Medan & Admin Control)</option>
                </select>
            </div>

            <div x-show="role === 'user_area'" x-transition>
                <label for="kecamatan_id" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">
                    Wilayah Kecamatan Penugasan <span class="text-red-500">*</span>
                </label>
                <select name="kecamatan_id" id="kecamatan_id"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
                    <option value="">-- Pilih Wilayah Kecamatan --</option>
                    @foreach($kecamatans as $k)
                        <option value="{{ $k->id }}" {{ old('kecamatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 mt-1">User ini hanya dapat menginput & mengubah data kuesioner pada kecamatan ini saja.</p>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded text-xs shadow-xs transition-colors">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
