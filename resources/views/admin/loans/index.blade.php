
@extends('layouts.inventrack')

@section('title', !empty($isReturnsPage) ? 'Data Pengembalian' : 'Data Peminjaman')
@section('heading', !empty($isReturnsPage) ? 'Data Pengembalian' : 'Data Peminjaman')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-slate-800">
        {{ !empty($isReturnsPage) ? 'Pengelolaan Pengembalian' : 'Pengelolaan Peminjaman' }}
    </h2>

    <p class="mt-2 text-sm text-slate-500">
        Kelola pengajuan peminjaman dan periksa bukti pengembalian barang inventaris.
    </p>
</div>

@if(session('success'))
    <div class="mb-5 rounded-xl bg-green-100 p-4 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-5 rounded-xl bg-red-100 p-4 text-sm text-red-800">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-xl bg-red-100 p-4 text-sm text-red-800">
        <p class="font-bold">Ada masalah:</p>
        <ul class="mt-2 list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    @forelse($loans as $loan)
        @php
            $status = $loan->status ?? 'pending';

            $statusClass = match ($status) {
                'pending' => 'bg-amber-100 text-amber-800',
                'approved' => 'bg-blue-100 text-blue-800',
                'borrowed' => 'bg-indigo-100 text-indigo-800',
                'return_requested', 'return_pending' => 'bg-orange-100 text-orange-800',
                'returned', 'completed' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                default => 'bg-slate-100 text-slate-700',
            };

            $statusLabel = match ($status) {
                'pending' => 'Menunggu Persetujuan',
                'approved' => 'Disetujui',
                'borrowed' => 'Sedang Dipinjam',
                'return_requested' => 'Request Return',
                'return_pending' => 'Menunggu Pengembalian',
                'returned' => 'Sudah Dikembalikan',
                'completed' => 'Selesai',
                'rejected' => 'Ditolak',
                default => ucfirst(str_replace('_', ' ', $status)),
            };
        @endphp

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            {{-- KODE TRANSAKSI DAN STATUS --}}
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-xs text-slate-400">Kode Transaksi</p>

                    <p class="mt-1 font-bold text-slate-800">
                        {{ $loan->loan_code ?? 'LOAN-' . $loan->id }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $loan->created_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- INFORMASI PEMINJAM --}}
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-500">
                        Nama Peminjam
                    </p>

                    <p class="mt-1 font-bold text-slate-800">
                        {{ $loan->user?->name ?? 'Pengguna tidak ditemukan' }}
                    </p>

                    <p class="mt-1 break-words text-sm text-slate-500">
                        {{ $loan->user?->email ?? '-' }}
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-500">
                        Keperluan
                    </p>

                    <p class="mt-1 whitespace-pre-line text-sm text-slate-700">
                        {{ $loan->purpose ?? $loan->reason ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- DAFTAR BARANG --}}
            <div class="mt-5">
                <h3 class="text-sm font-bold text-slate-700">
                    Daftar Barang
                </h3>

                <ul class="mt-3 space-y-2">
                    @forelse($loan->items as $item)
                        <li class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-100 p-3">
                            <div>
                                <p class="font-semibold text-slate-800">
                                    {{ $item->name ?? 'Barang tidak ditemukan' }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    Kode: {{ $item->code ?? '-' }}
                                </p>
                            </div>

                            <span class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700">
                                {{ $item->pivot?->quantity ?? 0 }} unit
                            </span>
                        </li>
                    @empty
                        <li class="rounded-xl bg-slate-50 p-3 text-sm text-slate-500">
                            Belum ada rincian barang pada transaksi ini.
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="mt-5 flex flex-wrap gap-3">

                {{-- TOMBOL DETAIL SELALU TERSEDIA --}}
                <a href="{{ route('admin.loans.show', $loan) }}"
                   class="inline-flex items-center justify-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-bold text-blue-700 transition hover:bg-blue-100">
                    Lihat Detail
                </a>

                @if(!empty($isReturnsPage))

                    {{-- AKSI KHUSUS PENGEMBALIAN --}}
                    @if($status === 'return_requested')
                        <form method="POST"
                              action="{{ route('admin.loans.approve-return', $loan) }}"
                              onsubmit="return confirm('Pastikan barang sudah diperiksa secara fisik. Setujui pengembalian ini?')">
                            @csrf

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-green-700">
                                Setujui Pengembalian
                            </button>
                        </form>
                    @elseif(in_array($status, ['returned', 'completed'], true))
                        <span class="inline-flex items-center rounded-xl bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                            Pengembalian Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                            Belum menunggu pemeriksaan pengembalian
                        </span>
                    @endif

                @else

                    {{-- AKSI PEMINJAMAN --}}
                    @if($status === 'pending')
                        <form method="POST"
                              action="{{ route('admin.loans.approve', $loan) }}"
                              onsubmit="return confirm('Setujui pengajuan peminjaman ini?')">
                            @csrf

                            <button type="submit"
                                    class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                                Setujui
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('admin.loans.reject', $loan) }}"
                              onsubmit="return confirm('Tolak pengajuan peminjaman ini?')">
                            @csrf

                            <button type="submit"
                                    class="rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-red-700">
                                Tolak
                            </button>
                        </form>
                    @endif

                    @if($status === 'approved')
                        <a href="{{ route('admin.loans.scan') }}"
                           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-indigo-700">
                            Scan QR Serah Terima
                        </a>
                    @endif

                @endif
            </div>

            {{-- PETUNJUK STATUS PENGEMBALIAN --}}
            @if($status === 'return_requested')
                <div class="mt-4 rounded-xl border border-orange-200 bg-orange-50 p-4 text-sm leading-6 text-orange-800">
                    <p class="font-bold">Pengembalian menunggu pemeriksaan.</p>
                    <p class="mt-1">
                        Klik <strong>Lihat Detail</strong> untuk memeriksa foto dan keterangan
                        dari pegawai sebelum menyetujui pengembalian.
                    </p>
                </div>
            @endif

        </div>
    @empty
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
            <p class="font-bold text-slate-700">
                {{ !empty($isReturnsPage) ? 'Belum ada data pengembalian.' : 'Belum ada pengajuan peminjaman.' }}
            </p>

            <p class="mt-2 text-sm text-slate-500">
                Data transaksi akan tampil di sini setelah tersedia.
            </p>
        </div>
    @endforelse

    <div class="mt-6">
        {{ $loans->links() }}
    </div>
</div>
@endsection
