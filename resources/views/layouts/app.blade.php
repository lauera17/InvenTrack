<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InvenTrack') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased">

    @php
        $user = auth()->user();
        $role = $user->role ?? 'pegawai';
        $photoPath = $user->profile_photo_path ?? null;
    @endphp

    <div class="min-h-screen">

        <!-- Navbar -->
        <nav class="border-b border-slate-200 bg-white shadow-sm">
            <div class="mx-auto flex max-w-screen-2xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">

                <!-- Logo kiri -->
                <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-900 font-bold text-white">
                        IT
                    </div>

                    <div>
                        <div class="text-lg font-extrabold tracking-tight text-slate-900">
                            InvenTrack
                        </div>
                        <div class="text-xs text-slate-500">
                            Inventory Management
                        </div>
                    </div>
                </a>

                <!-- Menu navigasi -->
                <div class="hidden items-center gap-2 md:flex">
                    <a href="{{ route('dashboard') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-900">
                        Dashboard
                    </a>

                    @if($role === 'admin')
                        <a href="{{ route('admin.items.index') }}"
                           class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-900">
                            Data Barang
                        </a>

                        <a href="{{ route('admin.loans.index') }}"
                           class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-900">
                            Peminjaman
                        </a>

                        <a href="{{ route('admin.reports.index') }}"
                           class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-900">
                            Laporan
                        </a>
                    @else
                        <a href="{{ route('pegawai.items.index') }}"
                           class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-900">
                            Lihat Barang
                        </a>

                        <a href="{{ route('pegawai.loans.index') }}"
                           class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-900">
                            Peminjaman Saya
                        </a>
                    @endif
                </div>

                <!-- Profil pengguna kanan -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-xl p-1.5 hover:bg-slate-100">

                        @if($photoPath)
                            <img
                                src="{{ asset('storage/' . $photoPath) }}"
                                alt="Foto profil"
                                class="h-10 w-10 rounded-full border-2 border-blue-100 object-cover"
                            >
                        @else
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-900">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        <div class="hidden text-left sm:block">
                            <p class="max-w-40 truncate text-sm font-semibold text-slate-800">
                                {{ $user->name ?? 'Pengguna' }}
                            </p>
                            <p class="text-xs capitalize text-slate-500">
                                {{ $role }}
                            </p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Menu untuk layar HP -->
            <div class="flex flex-wrap gap-2 border-t border-slate-100 px-4 py-3 md:hidden">
                <a href="{{ route('dashboard') }}"
                   class="rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-900">
                    Dashboard
                </a>

                @if($role === 'admin')
                    <a href="{{ route('admin.items.index') }}"
                       class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        Data Barang
                    </a>
                    <a href="{{ route('admin.loans.index') }}"
                       class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        Peminjaman
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                       class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        Laporan
                    </a>
                @else
                    <a href="{{ route('pegawai.items.index') }}"
                       class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        Lihat Barang
                    </a>
                    <a href="{{ route('pegawai.loans.index') }}"
                       class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">
                        Peminjaman Saya
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                   class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">
                    Edit Profil
                </a>
            </div>
        </nav>

        <!-- Isi halaman -->
        <main class="mx-auto max-w-screen-2xl px-4 py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

    </div>

</body>
</html>