@extends('layouts.inventrack')

@section('title', 'Riwayat Peminjaman')
@section('heading', 'Riwayat Peminjaman')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold">Riwayat Peminjaman Saya</h2>
    <p class="mt-2 text-sm text-slate-500">Pantau status pengajuan dan pengembalian barang.</p>
</div>

<div class="space-y-4">
    @forelse($loans as $loan)
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-xs text-slate-400">Transaksi #{{ $loan->id }}</p>
                    <p class="mt-1 font-bold">{{ $loan->created_at->format('d M Y') }}</p>
                </div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                </span>
            </div>

            <ul class="mt-4 list-inside list-disc text-sm text-slate-600">
                @foreach($loan->items as $line)
                    <li>{{ $line->item->name }} — {{ $line->quantity }} unit</li>
                @endforeach
            </ul>

            <p class="mt-3 whitespace-pre-line text-sm text-slate-500">{{ $loan->purpose }}</p>

            @if($loan->status === 'borrowed')
                <a href="{{ route('pegawai.loans.return.form', $loan) }}"
                   class="mt-4 inline-block rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white">
                    Ajukan Pengembalian
                </a>
            @endif
        </div>
    @empty
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
            Kamu belum memiliki riwayat peminjaman.
        </div>
    @endforelse

    {{ $loans->links() }}
</div>
@endsection