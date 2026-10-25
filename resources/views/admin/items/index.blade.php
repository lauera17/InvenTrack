@extends('layouts.inventrack')

@section('title', 'Data Barang')
@section('heading', 'Data Barang')

@section('content')
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900">Inventaris Barang</h2>
        <p class="mt-1 text-sm text-slate-500">Kelola seluruh barang dan ketersediaan stok.</p>
    </div>
    <a href="{{ route('admin.items.create') }}"
       class="rounded-xl bg-blue-600 px-5 py-3 text-center text-sm font-semibold text-white hover:bg-blue-700">
        + Tambah Barang
    </a>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <form method="GET" class="grid gap-3 border-b border-slate-100 p-4 md:grid-cols-3">
        <input name="search" value="{{ request('search') }}"
               placeholder="Cari nama atau kode barang..."
               class="rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

        <select name="category_id"
                class="rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(request('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button class="rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700">
            Cari Barang
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-5 py-4">Kode</th>
                    <th class="px-5 py-4">Nama Barang</th>
                    <th class="px-5 py-4">Kategori</th>
                    <th class="px-5 py-4">Stok</th>
                    <th class="px-5 py-4">Kondisi</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold text-blue-700">{{ $item->code }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-800">{{ $item->name }}</td>
                        <td class="px-5 py-4 text-slate-500">{{ $item->category->name }}</td>
                        <td class="px-5 py-4">
                            <span class="font-bold">{{ $item->stock }}</span>
                            @if($item->stock <= $item->minimum_stock)
                                <span class="ml-1 rounded-full bg-amber-100 px-2 py-1 text-xs text-amber-700">Stok rendah</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ ucfirst(str_replace('_', ' ', $item->condition)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.items.edit', $item) }}"
                                   class="rounded-lg bg-blue-50 px-3 py-2 font-semibold text-blue-700 hover:bg-blue-100">Edit</a>
                                <form method="POST" action="{{ route('admin.items.destroy', $item) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg bg-red-50 px-3 py-2 font-semibold text-red-600 hover:bg-red-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center text-slate-500">
                            Belum ada barang. Tambahkan barang pertama kamu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-slate-100 p-4">
        {{ $items->links() }}
    </div>
</div>
@endsection