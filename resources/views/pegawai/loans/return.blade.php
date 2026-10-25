@extends('layouts.inventrack')

@section('title', 'Pengembalian Barang')
@section('heading', 'Pengembalian Barang')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-slate-900">Form Pengembalian</h2>
        <p class="mt-2 text-sm text-slate-500">Unggah foto terbaru dan tuliskan kondisi barang yang dikembalikan.</p>
    </div>

    <form method="POST"
          action="{{ route('pegawai.loans.return.submit', $loan) }}"
          enctype="multipart/form-data"
          class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-sm text-slate-500">Nomor transaksi</p>
            <p class="mt-1 font-bold">INV-{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</p>

            <p class="mt-4 text-sm font-semibold">Barang yang dikembalikan</p>
            <ul class="mt-2 list-inside list-disc text-sm text-slate-600">
                @foreach($loan->items as $line)
                    <li>{{ $line->item->name }} — {{ $line->quantity }} unit</li>
                @endforeach
            </ul>
        </div>

        <div>
            <label for="return_photo" class="mb-2 block text-sm font-semibold">Foto Dokumentasi</label>
            <input id="return_photo" type="file" name="return_photo" accept="image/jpeg,image/png,image/webp" required
                   class="w-full rounded-xl border border-slate-200 p-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-700">
            <p class="mt-2 text-xs text-slate-400">Format JPG, PNG, atau WebP. Maksimal 5 MB.</p>
        </div>

        <div>
            <label for="return_note" class="mb-2 block text-sm font-semibold">Keterangan Kondisi</label>
            <textarea id="return_note" name="return_note" required maxlength="2000" rows="5"
                      placeholder="Contoh: Barang kembali dalam kondisi baik, lengkap dengan aksesori."
                      class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">{{ old('return_note') }}</textarea>
        </div>

        <button class="w-full rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
            Kirim Pengembalian
        </button>
    </form>
</div>
@endsection