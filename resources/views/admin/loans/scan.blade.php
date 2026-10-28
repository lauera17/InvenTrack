
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6">
    <div class="mx-auto max-w-xl">

        <div class="mb-6">
            <a href="{{ route('admin.loans.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Data Peminjaman
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Verifikasi QR Peminjaman
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Masukkan token QR lengkap dari halaman detail peminjaman Pegawai.
                Kamu tidak memerlukan kamera untuk menggunakan cara ini.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-xl bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-xl bg-red-100 p-4 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-100 p-4 text-red-800">
                <p class="font-semibold">Verifikasi gagal:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-3xl">
                ▦
            </div>

            <h2 class="mt-5 text-center text-xl font-bold text-slate-800">
                Masukkan Token QR
            </h2>

            <p class="mt-2 text-center text-sm leading-6 text-slate-500">
                Di halaman Pegawai, klik tombol Salin Token QR.
                Tempelkan token tersebut ke kolom di bawah.
            </p>

            <form method="POST"
                  action="{{ route('admin.loans.handover') }}"
                  class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="qr_token"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Token QR lengkap (64 karakter)
                    </label>

                    <textarea
                        id="qr_token"
                        name="qr_token"
                        required
                        minlength="64"
                        maxlength="64"
                        rows="4"
                        autocomplete="off"
                        autocapitalize="off"
                        spellcheck="false"
                        placeholder="Tempelkan token QR di sini"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 font-mono text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >{{ old('qr_token') }}</textarea>

                    <p class="mt-2 text-xs text-slate-500">
                        Jangan masukkan kode transaksi seperti LOAN-20261027-ABCDE.
                        Masukkan token acak lengkap yang disalin dari halaman Pegawai.
                    </p>
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Verifikasi dan Konfirmasi Penyerahan
                </button>
            </form>

            <div class="mt-5 rounded-xl bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                <strong>Perhatian:</strong> tombol ini akan memproses penyerahan barang
                jika token valid dan pengajuan sudah disetujui. Pastikan barang dan
                jumlahnya sesuai sebelum mengonfirmasi.
            </div>
        </div>
    </div>
</div>
@endsection
