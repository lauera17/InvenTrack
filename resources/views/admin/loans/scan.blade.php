@extends('layouts.inventrack')

@section('title', 'Verifikasi QR')
@section('heading', 'Verifikasi Penyerahan')

@section('content')
<div class="mx-auto max-w-xl">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-3xl">
            ▦
        </div>

        <h2 class="mt-5 text-center text-2xl font-extrabold">Verifikasi QR Peminjaman</h2>
        <p class="mt-2 text-center text-sm leading-6 text-slate-500">
            Pindai QR dari transaksi yang telah disetujui. Jika pemindai kamera belum diaktifkan,
            masukkan token secara manual.
        </p>

        <div id="reader" class="mt-6 overflow-hidden rounded-xl"></div>

        <form method="POST" action="{{ route('admin.loans.handover') }}" id="handoverForm" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="token" class="mb-2 block text-sm font-semibold">Token QR</label>
                <input id="token" name="token" required maxlength="48" minlength="48"
                       autocomplete="off" spellcheck="false"
                       placeholder="Masukkan token dari QR Code"
                       class="w-full rounded-xl border border-slate-200 px-4 py-3 font-mono text-sm focus:border-blue-500 focus:outline-none">
            </div>

            <button class="w-full rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                Verifikasi dan Konfirmasi Penyerahan
            </button>
        </form>

        <p class="mt-4 text-xs leading-5 text-slate-400">
            Sebelum menekan tombol konfirmasi, pastikan identitas peminjam, barang, dan jumlah
            unit sesuai dengan pengajuan yang telah disetujui.
        </p>
    </div>
</div>
@endsection