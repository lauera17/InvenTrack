@extends('layouts.inventrack')

@section('title', 'Ajukan Peminjaman')
@section('heading', 'Ajukan Peminjaman')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6">
        <p class="text-sm font-medium text-blue-600">TRANSAKSI BARU</p>
        <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Peminjaman Barang</h2>
        <p class="mt-2 text-sm text-slate-500">Pilih barang, tentukan jumlah, lalu tuliskan tujuan peminjaman.</p>
    </div>

    <form method="POST" action="{{ route('pegawai.loans.store') }}" id="loanForm">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="space-y-4 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-bold text-slate-900">Pilih Barang</h3>
                        <span class="text-xs text-slate-500">{{ $items->count() }} barang tersedia</span>
                    </div>

                    <input id="searchItem" type="search"
                           placeholder="Cari nama barang atau kategori..."
                           class="mb-4 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">

                    <div class="grid gap-3 sm:grid-cols-2">
                        @forelse($items as $item)
                            <div data-item-card data-search="{{ strtolower($item->name.' '.$item->category->name) }}"
                                 class="rounded-xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50/30">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $item->name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $item->category->name }}</p>
                                        <p class="mt-2 text-xs text-emerald-700">Stok tersedia: {{ $item->stock }}</p>
                                    </div>
                                    <span class="rounded-lg bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700">
                                        {{ $item->code }}
                                    </span>
                                </div>

                                <label class="mt-4 block text-xs font-semibold text-slate-600">Jumlah</label>
                                <div class="mt-2 flex items-center gap-3">
                                    <input type="number"
                                           min="0"
                                           max="{{ $item->stock }}"
                                           value="0"
                                           data-item-id="{{ $item->id }}"
                                           data-item-name="{{ $item->name }}"
                                           data-stock="{{ $item->stock }}"
                                           class="item-quantity w-24 rounded-lg border border-slate-200 px-3 py-2 text-sm"
                                           aria-label="Jumlah {{ $item->name }}">
                                    <span class="text-xs text-slate-400">Maks. {{ $item->stock }} unit</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl bg-slate-50 p-6 text-sm text-slate-500 sm:col-span-2">
                                Belum ada barang yang tersedia untuk dipinjam.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="font-bold text-slate-900">Ringkasan Pengajuan</h3>
                    <div class="mt-4 flex justify-between text-sm">
                        <span class="text-slate-500">Jenis barang</span>
                        <strong id="itemCount">0</strong>
                    </div>
                    <div class="mt-3 flex justify-between text-sm">
                        <span class="text-slate-500">Total unit</span>
                        <strong id="unitCount">0</strong>
                    </div>

                    <div class="my-5 border-t border-slate-100"></div>

                    <label for="purpose" class="mb-2 block text-sm font-semibold">Keterangan Peminjaman</label>
                    <textarea id="purpose" name="purpose" rows="5" required maxlength="2000"
                              placeholder="Contoh: Untuk presentasi kegiatan kantor..."
                              class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm focus:border-blue-500 focus:outline-none">{{ old('purpose') }}</textarea>

                    <p class="mt-2 text-xs text-slate-400">Pengajuan akan diperiksa oleh Admin.</p>

                    <button type="submit" id="submitLoan"
                            class="mt-5 w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">
                        Kirim Pengajuan →
                    </button>
                    <a href="{{ route('dashboard') }}"
                       class="mt-3 block text-center text-sm text-slate-500 hover:text-slate-800">
                        Batal
                    </a>
                </div>
            </aside>
        </div>
    </form>
</div>

<script>
const quantityInputs = document.querySelectorAll('.item-quantity');
const searchInput = document.getElementById('searchItem');

function updateSummary() {
    let kinds = 0;
    let units = 0;

    quantityInputs.forEach((input) => {
        const value = Math.max(0, Number(input.value) || 0);
        const stock = Number(input.dataset.stock);

        if (value > stock) input.value = stock;

        const quantity = Math.min(stock, Math.max(0, Number(input.value) || 0));

        if (quantity > 0) {
            kinds++;
            units += quantity;
        }
    });

    document.getElementById('itemCount').textContent = kinds;
    document.getElementById('unitCount').textContent = units;
}

quantityInputs.forEach((input) => {
    input.addEventListener('input', updateSummary);
});

searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase().trim();

    document.querySelectorAll('[data-item-card]').forEach((card) => {
        card.hidden = !card.dataset.search.includes(query);
    });
});

document.getElementById('loanForm').addEventListener('submit', (event) => {
    const selected = [...quantityInputs].filter((input) => Number(input.value) > 0);

    if (selected.length === 0) {
        event.preventDefault();
        alert('Pilih minimal satu barang dengan jumlah lebih dari nol.');
        return;
    }

    selected.forEach((input) => {
        const hiddenId = document.createElement('input');
        hiddenId.type = 'hidden';
        hiddenId.name = 'items[' + input.dataset.itemId + '][id]';
        hiddenId.value = input.dataset.itemId;

        const hiddenQuantity = document.createElement('input');
        hiddenQuantity.type = 'hidden';
        hiddenQuantity.name = 'items[' + input.dataset.itemId + '][quantity]';
        hiddenQuantity.value = input.value;

        event.currentTarget.append(hiddenId, hiddenQuantity);
    });

    document.getElementById('submitLoan').disabled = true;
    document.getElementById('submitLoan').textContent = 'Mengirim...';
});

updateSummary();
</script>
@endsection