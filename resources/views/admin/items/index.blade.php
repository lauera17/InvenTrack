@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- Header --}}
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">
                    InvenTrack / Inventaris
                </p>
                <h1 class="mt-2 text-3xl font-bold text-slate-800">
                    Data Barang
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Kelola data dan stok barang inventaris kantor.
                </p>
            </div>

            <a href="{{ route('admin.items.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700">
                + Tambah Barang
            </a>
        </div>

        {{-- Pesan berhasil --}}
        @if (session('success'))
            <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan gagal --}}
        @if (session('error'))
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="mb-2 font-semibold">Terjadi kesalahan:</p>
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Kartu ringkasan --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Jenis Barang</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ $items->total() }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Stok pada Halaman Ini</p>
                <p class="mt-2 text-3xl font-bold text-blue-600">
                    {{ $items->getCollection()->sum('stock') }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Kategori Tersedia</p>
                <p class="mt-2 text-3xl font-bold text-indigo-600">
                    {{ \App\Models\Category::count() }}
                </p>
            </div>
        </div>

        {{-- Tabel barang --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Daftar Inventaris</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Data barang yang tersimpan di database.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase text-slate-500">No.</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase text-slate-500">Barang</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase text-slate-500">Kategori</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase text-slate-500">Stok</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase text-slate-500">Kondisi</th>
                            <th class="px-5 py-4 text-right text-xs font-bold uppercase text-slate-500">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($items as $item)
                            <tr class="transition hover:bg-slate-50">

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                    {{ $items->firstItem() + $loop->index }}
                                </td>

                                {{-- Nama dan foto barang --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->image)
                                            <img
                                                src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->name }}"
                                                class="h-12 w-12 rounded-xl border border-slate-200 object-cover"
                                            >
                                        @else
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                                📦
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-semibold text-slate-800">
                                                {{ $item->name }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                Kode: {{ $item->code }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kategori --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $item->category->name ?? 'Belum ada kategori' }}
                                </td>

                                {{-- Stok --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="rounded-full px-3 py-1 text-sm font-semibold
                                        {{ $item->stock > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $item->stock }} unit
                                    </span>
                                </td>

                                {{-- Kondisi --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $item->condition ?: 'Belum diisi' }}
                                </td>

                                {{-- Tombol aksi --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex justify-end gap-2">

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.items.edit', $item) }}"
                                           class="rounded-lg bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100">
                                            Edit
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.items.destroy', $item) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="text-4xl">📦</div>
                                    <p class="mt-3 font-semibold text-slate-700">
                                        Belum ada data barang
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Klik tombol Tambah Barang untuk memulai.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Navigasi halaman --}}
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $items->links() }}
            </div>
        </div>

    </div>
</div>
@endsection