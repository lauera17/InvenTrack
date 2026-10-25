@extends('layouts.inventrack')

@section('title', 'Tambah Barang')
@section('heading', 'Tambah Barang')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.items.index') }}" class="text-sm font-semibold text-blue-600">← Kembali ke Data Barang</a>
        <h2 class="mt-3 text-2xl font-extrabold text-slate-900">Tambah Inventaris</h2>
        <p class="mt-1 text-sm text-slate-500">Lengkapi informasi barang di bawah ini.</p>
    </div>

    <form method="POST" action="{{ route('admin.items.store') }}"
          class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        @include('admin.items.form', ['item' => null])

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.items.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold">Batal</a>
            <button class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan Barang</button>
        </div>
    </form>
</div>
@endsection