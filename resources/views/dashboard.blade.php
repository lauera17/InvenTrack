
@extends('layouts.inventrack')

@section('title', 'Dashboard | InvenTrack')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = ($user?->role ?? '') === 'admin';

    $monthlyData = collect($monthlyLoans ?? []);

    $maxMonthlyLoans = max(
        1,
        (int) $monthlyData->max('total')
    );

    $statusLabels = [
        'pending' => 'Menunggu Persetujuan',
        'approved' => 'Disetujui',
        'borrowed' => 'Sedang Dipinjam',
        'return_requested' => 'Menunggu Pengembalian',
        'returned' => 'Dikembalikan',
        'rejected' => 'Ditolak',
    ];

    $statusClasses = [
        'pending' => 'bg-amber-100 text-amber-700',
        'approved' => 'bg-blue-100 text-blue-700',
        'borrowed' => 'bg-indigo-100 text-indigo-700',
        'return_requested' => 'bg-orange-100 text-orange-700',
        'returned' => 'bg-emerald-100 text-emerald-700',
        'rejected' => 'bg-rose-100 text-rose-700',
    ];

    $visibleLoans = collect($latestLoans ?? []);

    if (!$isAdmin && $user) {
        $visibleLoans = $visibleLoans->where('user_id', $user->id);
    }
@endphp

<div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">

    {{-- HEADER --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-bold uppercase tracking-widest text-blue-600">
                INVENTRACK
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-800">
                Dashboard
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Ringkasan inventaris dan aktivitas peminjaman barang.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">
            <p class="text-xs font-medium text-slate-500">Tanggal hari ini</p>

            <p class="mt-1 font-semibold text-slate-800">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    {{-- PESAN SUKSES DAN ERROR --}}
    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Ada hal yang perlu diperiksa:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- SAPAAN --}}
    <div class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-blue-700 to-indigo-600 p-6 text-white shadow-lg sm:p-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-blue-100">
                    Selamat datang kembali,
                </p>

                <h2 class="mt-2 text-2xl font-bold sm:text-3xl">
                    {{ $user?->name ?? 'Pengguna' }}!
                </h2>

                <p class="mt-3 max-w-xl text-sm leading-6 text-blue-100">
                    @if($isAdmin)
                        Kelola inventaris, pantau peminjaman, dan periksa pengembalian barang dengan mudah.
                    @else
                        Periksa ketersediaan barang dan pantau status peminjamanmu melalui InvenTrack.
                    @endif
                </p>
            </div>

            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-3xl font-bold">
                {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </div>

    {{-- STATISTIK ADMIN --}}
    @if($isAdmin)

        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Jenis Barang</p>
                    <span class="rounded-xl bg-blue-50 p-3 text-xl">📦</span>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($totalItems ?? 0) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Jenis barang terdaftar
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total Stok</p>
                    <span class="rounded-xl bg-emerald-50 p-3 text-xl">🗃️</span>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($totalStock ?? 0) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Unit barang tercatat
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total Peminjaman</p>
                    <span class="rounded-xl bg-violet-50 p-3 text-xl">📋</span>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($totalLoans ?? 0) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Semua transaksi peminjaman
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total Pegawai</p>
                    <span class="rounded-xl bg-amber-50 p-3 text-xl">👥</span>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($totalEmployees ?? 0) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Akun dengan peran pegawai
                </p>
            </div>

        </div>

        {{-- STATUS PEMINJAMAN --}}
        <div class="mb-8">
            <div class="mb-4">
                <h2 class="text-lg font-bold text-slate-800">
                    Status Peminjaman
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Ringkasan status seluruh transaksi.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                    <p class="text-sm font-medium text-amber-700">Menunggu Persetujuan</p>
                    <p class="mt-3 text-3xl font-bold text-amber-800">
                        {{ number_format($pendingLoans ?? 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                    <p class="text-sm font-medium text-blue-700">Disetujui</p>
                    <p class="mt-3 text-3xl font-bold text-blue-800">
                        {{ number_format($approvedLoans ?? 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                    <p class="text-sm font-medium text-indigo-700">Sedang Dipinjam</p>
                    <p class="mt-3 text-3xl font-bold text-indigo-800">
                        {{ number_format($borrowedLoans ?? 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5">
                    <p class="text-sm font-medium text-orange-700">Pengajuan Pengembalian</p>
                    <p class="mt-3 text-3xl font-bold text-orange-800">
                        {{ number_format($returnRequests ?? 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                    <p class="text-sm font-medium text-emerald-700">Sudah Dikembalikan</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-800">
                        {{ number_format($returnedLoans ?? 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">
                    <p class="text-sm font-medium text-rose-700">Ditolak</p>
                    <p class="mt-3 text-3xl font-bold text-rose-800">
                        {{ number_format($rejectedLoans ?? 0) }}
                    </p>
                </div>

            </div>
        </div>

    @else

        {{-- RINGKASAN PEGAWAI --}}
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Jenis Barang</p>
                <p class="mt-3 text-3xl font-bold text-slate-800">
                    {{ number_format($totalItems ?? 0) }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Stok</p>
                <p class="mt-3 text-3xl font-bold text-slate-800">
                    {{ number_format($totalStock ?? 0) }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Peminjaman Saya</p>
                <p class="mt-3 text-3xl font-bold text-slate-800">
                    {{ number_format($myLoanCount ?? 0) }}
                </p>
            </div>

        </div>

    @endif

    {{-- GRAFIK PEMINJAMAN KHUSUS ADMIN --}}
    @if($isAdmin)
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-bold text-slate-800">
                Aktivitas Peminjaman
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Jumlah transaksi selama enam bulan terakhir.
            </p>

            <div class="mt-8 flex h-56 items-end justify-around gap-3 border-b border-l border-slate-200 px-3 pb-2 sm:gap-6">

                @forelse($monthlyData as $month)
                    @php
                        $monthTotal = (int) data_get($month, 'total', 0);
                        $monthLabel = data_get($month, 'label', '-');
                        $barHeight = $monthTotal > 0
                            ? max(8, ($monthTotal / $maxMonthlyLoans) * 150)
                            : 4;
                    @endphp

                    <div class="flex h-full min-w-0 flex-1 flex-col items-center justify-end">

                        <span class="mb-2 text-xs font-semibold text-slate-600">
                            {{ $monthTotal }}
                        </span>

                        <div
                            class="w-full max-w-14 rounded-t-xl bg-blue-600"
                            style="height: {{ $barHeight }}px">
                        </div>

                        <span class="mt-3 text-xs font-medium text-slate-500">
                            {{ $monthLabel }}
                        </span>

                    </div>
                @empty
                    <div class="flex w-full items-center justify-center pb-6">
                        <p class="text-sm text-slate-500">
                            Belum ada data grafik.
                        </p>
                    </div>
                @endforelse

            </div>
        </div>
    @endif

    {{-- PEMINJAMAN TERBARU --}}
    <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    {{ $isAdmin ? 'Peminjaman Terbaru' : 'Aktivitas Peminjaman Saya' }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi transaksi peminjaman barang.
                </p>
            </div>

            @if($isAdmin)
                <a
                    href="{{ route('admin.loans.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Lihat Semua
                </a>
            @else
                <a
                    href="{{ route('pegawai.loans.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Peminjaman Saya
                </a>
            @endif

        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Kode
                        </th>

                        @if($isAdmin)
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Peminjam
                            </th>
                        @endif

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Barang
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Tanggal
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($visibleLoans as $loan)
                        <tr class="transition hover:bg-slate-50">

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="font-semibold text-slate-800">
                                    {{ $loan->loan_code ?? ('LOAN-' . $loan->id) }}
                                </span>
                            </td>

                            @if($isAdmin)
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $loan->user?->name ?? 'Pengguna tidak ditemukan' }}
                                </td>
                            @endif

                            <td class="px-5 py-4">
                                @forelse($loan->items as $item)
                                    <div class="mb-1 text-sm text-slate-700">
                                        {{ $item->name ?? 'Barang tidak ditemukan' }}

                                        <span class="text-slate-400">
                                            ({{ $item->pivot?->quantity ?? 0 }} unit)
                                        </span>
                                    </div>
                                @empty
                                    <span class="text-sm text-slate-400">
                                        Belum ada detail barang.
                                    </span>
                                @endforelse
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $loan->created_at?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @php
                                    $status = $loan->status ?? 'pending';
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="{{ $isAdmin ? 5 : 4 }}"
                                class="px-5 py-12 text-center">

                                <div class="text-3xl">📋</div>

                                <p class="mt-3 font-semibold text-slate-700">
                                    Belum ada peminjaman
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Transaksi akan muncul setelah pengajuan dibuat.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- BARANG TERBARU KHUSUS ADMIN --}}
    @if($isAdmin)
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 p-5">
                <h2 class="text-lg font-bold text-slate-800">
                    Barang Terbaru
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Lima barang terakhir yang ditambahkan.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Nama Barang
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Kategori
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Stok
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Kondisi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse(collect($latestItems ?? []) as $item)
                            <tr class="transition hover:bg-slate-50">

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $item->name ?? 'Barang tanpa nama' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $item->code ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $item->category?->name ?? 'Tanpa kategori' }}
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-slate-700">
                                    {{ number_format($item->stock ?? 0) }} unit
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $item->condition ?? '-' }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center">
                                    <div class="text-3xl">📦</div>

                                    <p class="mt-3 font-semibold text-slate-700">
                                        Belum ada data barang
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Barang yang ditambahkan akan muncul di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    @endif

</div>
@endsection
