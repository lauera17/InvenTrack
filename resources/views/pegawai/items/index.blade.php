@extends('layouts.inventrack')

@section('title', 'Stok Barang')
@section('heading', 'Stok Barang')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-slate-900">Katalog Inventaris</h2>
    <p class="mt-2 text-sm text-slate-500">Lihat ketersediaan barang sebelum mengajukan peminjaman.</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @forelse($items as $item)
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl text-blue-700">▣</div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $item->stock > 0 && $item->condition === 'baik' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                    {{ $item->stock > 0 && $item->condition === 'baik' ? 'Tersedia' : 'Tidak tersedia' }}
                </span>
            </div>

            <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $item->code }}</p>
            <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $item->name }}</h3>
            <p class="mt-1 text-sm text-slate-500">{{ $item->category->name }}</p>

            <div class="mt-4 flex justify-between border-t border-slate-100 pt-4 text-sm">
                <span class="text-slate-500">Stok tersedia</span>
                <strong>{{ $item->stock }} unit</strong>
            </div>

            <a href="{{ route('pegawai.loans.create') }}"
               class="mt-4 block rounded-xl bg-blue-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-blue-700">
                Ajukan Peminjaman
            </a>
        </article>
    @empty
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 sm:col-span-2 xl:col-span-3">
            Belum ada inventaris terdaftar.
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $items->links() }}</div>
@endsection