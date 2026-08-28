@extends('layouts.app')

@section('title', 'Admin Control — Manajemen Pengguna')
@section('heading', 'Admin Control — Manajemen Akun & Wilayah')

@section('content')
<div class="space-y-6">

    {{-- Top Action Bar & Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akun administrator dan operator per area kecamatan.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-lg text-xs shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Tambah Akun Baru
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="admin-card p-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                       class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
            </div>

            <div>
                <select name="role" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
                    <option value="">-- Semua Peran --</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="user_area" {{ request('role') === 'user_area' ? 'selected' : '' }}>Operator Area</option>
                </select>
            </div>

            <div>
                <select name="kecamatan_id" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-900">
                    <option value="">-- Semua Wilayah Kecamatan --</option>
                    @foreach($kecamatans as $k)
                        <option value="{{ $k->id }}" {{ request('kecamatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded text-xs transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role', 'kecamatan_id']))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded text-xs transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/80 border-b border-slate-200 uppercase tracking-wider text-[10px] font-bold text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Pengguna</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Peran (Role)</th>
                        <th class="px-4 py-3">Wilayah Tugas</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 font-bold text-slate-900">
                                {{ $user->name }}
                                @if(auth()->id() === $user->id)
                                    <span class="ml-1 text-[10px] font-bold text-blue-900 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200">Anda</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        ADMINISTRATOR
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        OPERATOR AREA
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($user->isAdmin())
                                    <span class="text-slate-400 font-semibold italic">Semua Wilayah (Kota Medan)</span>
                                @elseif($user->kecamatan)
                                    <span class="font-bold text-slate-800">📍 {{ $user->kecamatan->nama }}</span>
                                @else
                                    <span class="text-red-500 font-semibold">Belum Ditentukan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="font-bold text-blue-900 hover:text-blue-700 underline">
                                    Edit
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-bold text-red-600 hover:text-red-800 underline">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
