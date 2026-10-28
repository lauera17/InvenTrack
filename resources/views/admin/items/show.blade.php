@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">

        {{-- Tombol kembali --}}
        <a href="{{ route('admin.items.index') }}"
           class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
            &larr; Kembali ke Data Barang
        </a>

        {{-- Judul --}}
        <div class="mb-6 mt-5">
            <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">
                InvenTrack / Inventaris
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-800">
                Detail Barang
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Informasi lengkap barang inventaris kantor.
            </p>
        </div>

        {{-- Kartu detail --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Foto barang --}}
            <div class="border-b border-slate-100 bg-slate-50 p-6">
                <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-start">

                    @if ($item->image)
                        <img
                            src="{{ asset('storage/' . $item->image) }}"
                            alt="{{ $item->name }}"
                            class="h-56 w-full rounded-xl border border-slate-200 bg-white object-contain sm:w-64"
                        >
                    @else
                        <div class="flex h-56 w-full items-center justify-center rounded-xl border border-slate-200 bg-white text-6xl sm:w-64">
                            📦
                        </div>
                    @endif

                    <div class="flex-1">
                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ $item->category->name ?? 'Belum ada kategori' }}
                        </span>

                        <h2 class="mt-3 text-2xl font-bold text-slate-800">
                            {{ $item->name }}
                        </h2>

                        <p class="mt-2 text-sm text-slate-500">
                            Kode barang: {{ $item->code ?: 'Belum tersedia' }}
                        </p>

                        <div class="mt-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold
                                {{ $item->stock > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                Stok: {{ $item->stock }} unit
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi barang --}}
            <div class="p-6 sm:p-8">
                <h3 class="text-lg font-bold text-slate-800">
                    Informasi Barang
                </h3>

                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm text-slate-500">Nama Barang</p>
                        <p class="mt-2 font-semibold text-slate-800">
                            {{ $item->name }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm text-slate-500">Kategori</p>
                        <p class="mt-2 font-semibold text-slate-800">
                            {{ $item->category->name ?? 'Belum ada kategori' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm text-slate-500">Kode Barang</p>
                        <p class="mt-2 font-semibold text-slate-800">
                            {{ $item->code ?: 'Belum tersedia' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm text-slate-500">Jumlah Stok</p>
                        <p class="mt-2 font-semibold text-slate-800">
                            {{ $item->stock }} unit
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm text-slate-500">Stok Minimum</p>
                        <p class="mt-2 font-semibold text-slate-800">
                            {{ $item->minimum_stock }} unit
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm text-slate-500">Kondisi Barang</p>
                        <p class="mt-2 font-semibold text-slate-800">
                            {{ $item->condition ?: 'Belum diisi' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4 sm:col-span-2">
                        <p class="text-sm text-slate-500">Lokasi Penyimpanan</p>
                        <p class="mt-2 font-semibold text-slate-800">
                            {{ $item->location ?: 'Belum ditentukan' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4 sm:col-span-2">
                        <p class="text-sm text-slate-500">Deskripsi Barang</p>
                        <p class="mt-2 whitespace-pre-line leading-7 text-slate-700">{{ $item->description ?: 'Belum ada deskripsi barang.' }}</p>
                    </div>

                </div>

                {{-- Tombol aksi --}}
                <div class="mt-8 flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">

                    <a href="{{ route('admin.items.index') }}"
                       class="rounded-xl border border-slate-300 px-5 py-3 text-center font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>

                    <a href="{{ route('admin.items.edit', $item) }}"
                       class="rounded-xl bg-amber-500 px-5 py-3 text-center font-semibold text-white hover:bg-amber-600">
                        Edit Barang
                    </a>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection