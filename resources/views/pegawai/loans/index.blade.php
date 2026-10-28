@extends('layouts.inventrack')

@section('title', 'Riwayat Peminjaman')
@section('heading', 'Riwayat Peminjaman')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-slate-800">
        Riwayat Peminjaman Saya
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Pantau status pengajuan dan pengembalian barang.
    </p>
</div>

<div class="space-y-4">
    @forelse($loans as $loan)
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-xs text-slate-400">
                        Kode Transaksi
                    </p>

                    <p class="mt-1 font-bold text-slate-800">
                        {{ $loan->loan_code ?? 'LOAN-' . $loan->id }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $loan->created_at?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                @php
                    $status = $loan->status ?? 'pending';

                    $statusClass = match ($status) {
                        'pending' => 'bg-amber-100 text-amber-800',
                        'approved' => 'bg-blue-100 text-blue-800',
                        'borrowed' => 'bg-indigo-100 text-indigo-800',
                        'return_requested' => 'bg-purple-100 text-purple-800',
                        'returned' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp

                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </span>
            </div>

            <div class="mt-5">
                <h3 class="text-sm font-bold text-slate-700">
                    Barang yang Diajukan
                </h3>

                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    @forelse($loan->items as $item)
                        <li class="flex flex-wrap justify-between gap-2 rounded-lg bg-slate-50 px-3 py-2">
                            <span>
                                {{ $item->name ?? 'Barang tidak ditemukan' }}
                            </span>

                            <span class="font-semibold">
                                {{ $item->pivot?->quantity ?? 0 }} unit
                            </span>
                        </li>
                    @empty
                        <li class="text-slate-400">
                            Belum ada rincian barang.
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-4">
                <p class="text-xs font-semibold text-slate-500">
                    Keperluan Peminjaman
                </p>

                <p class="mt-1 whitespace-pre-line text-sm text-slate-600">
                    {{ $loan->purpose ?? $loan->reason ?? '-' }}
                </p>
            </div>

            <div class="mt-5 flex flex-wrap gap-3">
                @if(\Illuminate\Support\Facades\Route::has('pegawai.loans.show'))
                    <a href="{{ route('pegawai.loans.show', $loan) }}"
                       class="inline-block rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Lihat Detail
                    </a>
                @endif

                @if($status === 'borrowed')
                    <a href="{{ route('pegawai.loans.return.form', $loan) }}"
                       class="inline-block rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        Ajukan Pengembalian
                    </a>
                @endif
            </div>

        </div>
    @empty
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
            <p class="font-semibold text-slate-700">
                Kamu belum memiliki riwayat peminjaman.
            </p>
            <p class="mt-2 text-sm text-slate-500">
                Pengajuan peminjaman yang kamu buat akan muncul di sini.
            </p>
        </div>
    @endforelse

    <div class="mt-6">
        {{ $loans->links() }}
    </div>
</div>
@endsection