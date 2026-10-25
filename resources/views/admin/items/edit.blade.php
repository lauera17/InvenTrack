@extends('layouts.inventrack')

@section('title', 'Edit Barang')
@section('heading', 'Edit Barang')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.items.index') }}" class="text-sm font-semibold text-blue-600">← Kembali ke Data Barang</a>
        <h2 class="mt-3 text-2xl font-extrabold text-slate-900">Edit Inventaris</h2>
    </div>

    <form method="POST" action="{{ route('admin.items.update', $item) }}"
          class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        @include('admin.items.form', ['item' => $item])

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.items.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold">Batal</a>
            <button class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection