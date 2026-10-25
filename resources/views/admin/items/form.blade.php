<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold">Kode Barang</label>
        <input name="code" required maxlength="50"
               value="{{ old('code', $item?->code) }}"
               placeholder="INV-001"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Nama Barang</label>
        <input name="name" required
               value="{{ old('name', $item?->name) }}"
               placeholder="Laptop ASUS"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Kategori</label>
        <select name="category_id" required
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
            <option value="">Pilih kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $item?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Lokasi Barang</label>
        <input name="location" value="{{ old('location', $item?->location) }}"
               placeholder="Ruang Inventaris"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Jumlah Stok</label>
        <input type="number" min="0" name="stock" required
               value="{{ old('stock', $item?->stock ?? 0) }}"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Batas Stok Rendah</label>
        <input type="number" min="0" name="minimum_stock" required
               value="{{ old('minimum_stock', $item?->minimum_stock ?? 1) }}"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Kondisi</label>
        <select name="condition" required
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
            @foreach(['baik' => 'Baik', 'perlu_perbaikan' => 'Perlu Perbaikan', 'rusak' => 'Rusak'] as $value => $label)
                <option value="{{ $value }}" @selected(old('condition', $item?->condition ?? 'baik') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-semibold">Deskripsi</label>
        <textarea name="description" rows="4"
                  placeholder="Keterangan tambahan barang..."
                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">{{ old('description', $item?->description) }}</textarea>
    </div>
</div>