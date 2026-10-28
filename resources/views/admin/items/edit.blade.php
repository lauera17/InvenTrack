@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6">
    <div class="mx-auto max-w-3xl">

        <a href="{{ route('admin.items.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">
            ← Kembali ke Data Barang
        </a>

        <div class="mb-6 mt-5">
            <h1 class="text-3xl font-bold text-slate-800">Edit Barang</h1>
            <p class="mt-2 text-sm text-slate-500">
                Perbarui informasi barang inventaris.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.items.update', $item) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @method('PUT')

            <button type="submit">
                Simpan Perubahan
            </button>
        </form>

            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $item->name) }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select id="category_id" name="category_id" required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="stock" class="mb-2 block text-sm font-semibold text-slate-700">
                        Jumlah Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stock"
                           value="{{ old('stock', $item->stock) }}" min="0" required
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="condition" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kondisi Barang
                    </label>
                    <select id="condition" name="condition"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih kondisi</option>
                        <option value="Baik" {{ old('condition', $item->condition) == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('condition', $item->condition) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('condition', $item->condition) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('condition')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">
                    Deskripsi Barang
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('description', $item->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">
                    Ganti Foto Barang
                </label>

                @if ($item->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $item->image) }}"
                             alt="{{ $item->name }}"
                             class="h-32 w-32 rounded-xl border border-slate-200 object-cover">
                        <p class="mt-2 text-xs text-slate-500">Foto saat ini</p>
                    </div>
                @endif

                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm">
                <p class="mt-2 text-xs text-slate-500">
                    Kosongkan jika tidak ingin mengganti foto.
                </p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.items.index') }}"
                   class="rounded-xl border border-slate-300 px-5 py-3 text-center font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection