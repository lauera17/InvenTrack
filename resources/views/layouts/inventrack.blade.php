<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | InvenTrack</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

<div class="min-h-screen">

    {{-- Overlay untuk sidebar di perangkat mobile --}}
    <div id="sidebarOverlay"
         onclick="toggleSidebar()"
         class="fixed inset-0 z-40 hidden bg-slate-950/50 lg:hidden">
    </div>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 flex w-64
                  -translate-x-full flex-col bg-[#10243e]
                  text-white transition-transform duration-300
                  lg:translate-x-0">

        {{-- LOGO: hanya satu identitas InvenTrack --}}
        <a href="{{ route('dashboard') }}"
           class="flex h-20 shrink-0 items-center gap-3
                  border-b border-white/10 px-6">

            <span class="flex h-10 w-10 items-center justify-center
                         rounded-xl bg-blue-500 text-xl font-black">
                I
            </span>

            <span>
                <span class="block text-lg font-extrabold tracking-wide">
                    InvenTrack
                </span>

                <span class="block text-xs text-slate-400">
                    Inventory Management
                </span>
            </span>
        </a>

        {{-- MENU NAVIGASI --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">

            <p class="mb-3 px-3 text-xs font-bold uppercase
                      tracking-widest text-slate-500">
                Menu Utama
            </p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-4 py-3
                      text-sm font-medium
                      {{ request()->routeIs('dashboard')
                          ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/30'
                          : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                <span>▦</span>
                Dashboard
            </a>

            @if(auth()->user()->role === 'admin')

                {{-- MENU ADMIN --}}

                <a href="{{ route('admin.items.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                          text-sm font-medium
                          {{ request()->routeIs('admin.items.*')
                              ? 'bg-blue-600 text-white'
                              : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span>▣</span>
                    Data Barang
                </a>

                <a href="{{ route('admin.loans.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                          text-sm font-medium
                          {{ request()->routeIs('admin.loans.*')
                              ? 'bg-blue-600 text-white'
                              : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span>⇄</span>
                    Data Peminjaman
                </a>

                <a href="{{ route('admin.returns.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                          text-sm font-medium
                          {{ request()->routeIs('admin.returns.*')
                              ? 'bg-blue-600 text-white'
                              : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span>↩</span>
                    Pengembalian
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                          text-sm font-medium
                          {{ request()->routeIs('admin.reports.*')
                              ? 'bg-blue-600 text-white'
                              : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span>▤</span>
                    Laporan
                </a>

            @else

                {{-- MENU PEGAWAI --}}

                <a href="{{ route('pegawai.items.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                          text-sm font-medium
                          {{ request()->routeIs('pegawai.items.*')
                              ? 'bg-blue-600 text-white'
                              : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span>▣</span>
                    Stok Barang
                </a>

                <a href="{{ route('pegawai.loans.create') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                          text-sm font-medium
                          {{ request()->routeIs('pegawai.loans.create')
                              ? 'bg-blue-600 text-white'
                              : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span>＋</span>
                    Ajukan Peminjaman
                </a>

                <a href="{{ route('pegawai.loans.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3
                          text-sm font-medium
                          {{ request()->routeIs('pegawai.loans.*')
                              && !request()->routeIs('pegawai.loans.create')
                              ? 'bg-blue-600 text-white'
                              : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span>◷</span>
                    Riwayat Peminjaman
                </a>

            @endif

        </nav>

        {{-- TOMBOL KELUAR --}}
        <div class="shrink-0 border-t border-white/10 p-4">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="w-full rounded-xl px-4 py-3
                               text-left text-sm text-slate-300
                               hover:bg-red-500/15 hover:text-red-300">
                    ↪ &nbsp; Keluar dari akun
                </button>
            </form>

        </div>

    </aside>

    {{-- KONTEN UTAMA --}}
    <div class="min-h-screen lg:pl-64">

        {{-- HEADER --}}
        <header class="sticky top-0 z-30 flex h-16
                       items-center justify-between border-b
                       border-slate-200 bg-white/95 px-4
                       backdrop-blur md:px-8">

            <div class="flex items-center gap-3">

                {{-- Tombol sidebar mobile --}}
                <button type="button"
                        onclick="toggleSidebar()"
                        aria-label="Buka atau tutup menu"
                        class="rounded-lg border border-slate-200
                               px-3 py-2 text-slate-600
                               hover:bg-slate-100 lg:hidden">
                    ☰
                </button>

                <div>
                    <h1 class="font-bold text-slate-900">
                        @yield('heading', 'Dashboard')
                    </h1>

                    <p class="hidden text-xs text-slate-500 sm:block">
                        Sistem Manajemen Inventaris Barang
                    </p>
                </div>

            </div>

            {{-- NAMA PENGGUNA HANYA DI HEADER --}}
            <div class="flex items-center gap-3">

                <div class="text-right">
                    <span class="block text-sm font-semibold">
                        {{ auth()->user()->name ?? 'Pengguna' }}
                    </span>

                    <span class="block text-xs text-slate-500">
                        {{ now()->format('d M Y') }}
                    </span>
                </div>

                {{-- Inisial pengguna --}}
                <div class="flex h-10 w-10 shrink-0
                            items-center justify-center rounded-full
                            bg-blue-100 font-bold text-blue-700">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>

            </div>

        </header>

        {{-- ISI HALAMAN --}}
        <main class="mx-auto max-w-[1600px] p-4 md:p-8">

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="mb-5 rounded-xl border border-emerald-200
                            bg-emerald-50 px-4 py-3 text-sm
                            text-emerald-800">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- Pesan error --}}
            @if(session('error'))
                <div class="mb-5 rounded-xl border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Pesan validasi --}}
            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-800">

                    <p class="mb-1 font-bold">
                        Periksa kembali data:
                    </p>

                    <ul class="list-inside list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

{{-- JAVASCRIPT SIDEBAR --}}
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (!sidebar || !overlay) {
            return;
        }

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>

</body>
</html>