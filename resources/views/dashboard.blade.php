@extends('layouts.inventrack')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <p class="text-sm text-slate-500">
            {{ now()->translatedFormat('l, d F Y') }}
        </p>
        <h2 class="mt-1 text-2xl font-extrabold text-slate-900 md:text-3xl">
            Halo, {{ $user->name }}! 👋
        </h2>
        <p class="mt-2 text-sm text-slate-500">
            Berikut ringkasan aktivitas inventaris hari ini.
        </p>
    </div>

    @if(!$isAdmin)
        <a href="{{ route('pegawai.loans.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-200 hover:bg-blue-700">
            + Ajukan Peminjaman
        </a>
    @else
        <a href="{{ route('admin.items.index') }}"
           class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-200 hover:bg-blue-700">
            + Kelola Barang
        </a>
    @endif
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 p-5 text-white shadow-lg shadow-blue-200">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-blue-100">Total Jenis Barang</span>
            <span class="rounded-xl bg-white/20 p-3 text-xl">▣</span>
        </div>
        <p class="mt-5 text-3xl font-extrabold">{{ number_format($stats['total']) }}</p>
        <p class="mt-2 text-xs text-blue-100">Jenis barang terdaftar</p>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-slate-500">Stok Tersedia</span>
            <span class="rounded-xl bg-emerald-50 p-3 text-xl text-emerald-600">▤</span>
        </div>
        <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ number_format($stats['available']) }}</p>
        <p class="mt-2 text-xs text-slate-400">Total unit yang tercatat tersedia</p>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-slate-500">Menunggu Persetujuan</span>
            <span class="rounded-xl bg-amber-50 p-3 text-xl text-amber-600">◷</span>
        </div>
        <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ number_format($stats['pending']) }}</p>
        <p class="mt-2 text-xs text-slate-400">Pengajuan peminjaman</p>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-slate-500">Transaksi Aktif</span>
            <span class="rounded-xl bg-violet-50 p-3 text-xl text-violet-600">⇄</span>
        </div>
        <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ number_format($stats['active']) }}</p>
        <p class="mt-2 text-xs text-slate-400">Dalam proses peminjaman</p>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-5">
    <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm xl:col-span-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900">Statistik Peminjaman</h3>
                <p class="mt-1 text-xs text-slate-500">Ilustrasi aktivitas enam bulan</p>
            </div>
            <span class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700">
                6 Bulan
            </span>
        </div>

        <div class="mt-6 flex h-56 items-end justify-around gap-3 border-b border-l border-slate-100 px-3">
            @foreach([35,55,44,68,52,80] as $height)
                <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                    <div class="w-full max-w-10 rounded-t-lg bg-blue-500 hover:bg-blue-700"
                         style="height: {{ $height }}%"></div>
                </div>
            @endforeach
        </div>
        <div class="mt-3 flex justify-around text-xs text-slate-400">
            @foreach(['Mei','Jun','Jul','Agu','Sep','Okt'] as $month)
                <span>{{ $month }}</span>
            @endforeach
        </div>
        <p class="mt-4 text-xs text-slate-400">
            Grafik di atas masih ilustrasi. Data grafik aktual akan kita hubungkan pada tahap laporan.
        </p>
    </section>

    <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm xl:col-span-2">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-slate-900">Peminjaman Terbaru</h3>
            @if($isAdmin)
                <a href="{{ route('admin.loans.index') }}"
                   class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Lihat semua →
                </a>
            @else
                <a href="{{ route('pegawai.loans.index') }}"
                   class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Riwayat →
                </a>
            @endif
        </div>

        <div class="mt-5 space-y-4">
            @forelse($loans as $loan)
                <div class="flex items-start gap-3 border-b border-slate-100 pb-4 last:border-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 font-bold text-blue-600">
                        {{ strtoupper(substr($loan->user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ $loan->user->name }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $loan->items->pluck('item.name')->filter()->join(', ') ?: 'Belum ada barang' }}
                        </p>
                        <p class="mt-2 text-xs text-slate-400">
                            {{ $loan->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold
                        {{ $loan->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $loan->status === 'approved' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $loan->status === 'borrowed' ? 'bg-violet-100 text-violet-700' : '' }}
                        {{ $loan->status === 'return_requested' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $loan->status === 'returned' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $loan->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                    </span>
                </div>
            @empty
                <div class="rounded-xl bg-slate-50 px-4 py-10 text-center">
                    <div class="text-3xl">📋</div>
                    <p class="mt-3 text-sm font-semibold text-slate-700">
                        Belum ada transaksi
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        Transaksi akan tampil setelah ada pengajuan.
                    </p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 p-5">
    <div class="flex gap-3">
        <span class="text-xl">💡</span>
        <div>
            <h3 class="font-bold text-blue-900">Tips mengelola inventaris</h3>
            <p class="mt-1 text-sm leading-6 text-blue-800">
                Pastikan jumlah barang dan kondisi inventaris selalu diperbarui.
                Periksa setiap pengajuan sebelum barang diserahkan kepada peminjam.
            </p>
        </div>
    </div>
</div>
@endsection