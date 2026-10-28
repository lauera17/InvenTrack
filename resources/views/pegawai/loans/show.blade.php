
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6">
    <div class="mx-auto max-w-4xl">

        <div class="mb-6">
            <a href="{{ route('pegawai.loans.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Peminjaman
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Detail Peminjaman
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Informasi pengajuan dan QR pengambilan barang.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-800">
                <p class="font-semibold">Terjadi kesalahan:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Kode Peminjaman</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $loan->loan_code }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <span class="mt-1 inline-block rounded-full px-3 py-1 text-sm font-semibold
                        {{ $loan->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $loan->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $loan->status === 'borrowed' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $loan->status === 'returned' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $loan->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $loan->status === 'return_requested' ? 'bg-orange-100 text-orange-800' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                    </span>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-slate-500">Keperluan Peminjaman</p>
                    <p class="mt-1 whitespace-pre-line text-slate-700">
                        {{ $loan->purpose ?: $loan->reason ?: 'Tidak ada keterangan.' }}
                    </p>
                </div>
            </div>

            <hr class="my-6 border-slate-200">

            <h2 class="mb-4 text-lg font-bold text-slate-800">
                Barang yang Diajukan
            </h2>

            @forelse ($loan->items as $item)
                <div class="mb-3 flex items-start justify-between gap-4 rounded-xl border border-slate-200 p-4">
                    <div>
                        <p class="font-semibold text-slate-800">
                            {{ $item->name }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Kode barang: {{ $item->code ?? '-' }}
                        </p>
                    </div>

                    <p class="shrink-0 text-sm font-semibold text-slate-700">
                        Jumlah: {{ $item->pivot->quantity }}
                    </p>
                </div>
            @empty
                <p class="text-sm text-slate-500">
                    Belum ada rincian barang pada peminjaman ini.
                </p>
            @endforelse

            @if ($loan->status === 'approved' && $loan->qr_token)
                <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-6 text-center">

                    <h2 class="text-xl font-bold text-blue-900">
                        QR Pengambilan Barang
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-blue-800">
                        QR ini digunakan untuk memverifikasi penyerahan barang.
                        Jika Admin menggunakan laptop tanpa kamera, salin token
                        lengkap di bawah dan tempelkan pada halaman verifikasi Admin.
                    </p>

                    <div class="mx-auto mt-5 inline-block rounded-xl bg-white p-4 shadow-sm">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(240)->margin(2)->generate($loan->qr_token) !!}
                    </div>

                    <p class="mt-3 text-sm text-slate-600">
                        Kode transaksi:
                        <span class="font-semibold">{{ $loan->loan_code }}</span>
                    </p>

                    <div class="mt-6 text-left">
                        <label for="qrToken" class="mb-2 block text-sm font-semibold text-slate-800">
                            Token QR lengkap
                        </label>

                        <textarea id="qrToken" readonly rows="3"
                            class="w-full break-all rounded-xl border border-blue-200 bg-white p-3 font-mono text-sm text-slate-700">{{ $loan->qr_token }}</textarea>

                        <button type="button" id="copyTokenButton"
                            class="mt-3 w-full rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                            Salin Token QR
                        </button>

                        <p id="copyTokenMessage" role="status"
                            class="mt-2 text-center text-sm text-slate-600"></p>
                    </div>

                    <p class="mt-4 text-xs leading-5 text-slate-500">
                        Token ini bersifat rahasia. Gunakan hanya untuk verifikasi
                        peminjaman ini dan jangan membagikannya kepada orang lain.
                    </p>

                    <button type="button" onclick="window.print()"
                        class="mt-4 rounded-lg border border-blue-300 bg-white px-5 py-3 font-semibold text-blue-700 hover:bg-blue-100">
                        Cetak QR
                    </button>
                </div>

            @elseif ($loan->status === 'pending')
                <div class="mt-6 rounded-xl bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                    Pengajuan masih menunggu persetujuan Admin. QR pengambilan
                    akan ditampilkan setelah pengajuan disetujui.
                </div>

            @elseif ($loan->status === 'borrowed')
                <div class="mt-6 rounded-xl bg-purple-50 p-4 text-sm leading-6 text-purple-800">
                    Barang sudah diserahkan dan tercatat sedang dipinjam.
                    QR pengambilan tidak lagi ditampilkan.
                </div>

            @elseif ($loan->status === 'rejected')
                <div class="mt-6 rounded-xl bg-red-50 p-4 text-sm leading-6 text-red-800">
                    Pengajuan peminjaman ditolak oleh Admin.
                </div>

            @elseif ($loan->status === 'return_requested')
                <div class="mt-6 rounded-xl bg-orange-50 p-4 text-sm leading-6 text-orange-800">
                    Pengajuan pengembalian sedang menunggu pemeriksaan Admin.
                </div>

            @elseif ($loan->status === 'returned')
                <div class="mt-6 rounded-xl bg-green-50 p-4 text-sm leading-6 text-green-800">
                    Peminjaman dan pengembalian telah selesai.
                </div>
            @endif

            @if ($loan->status === 'borrowed')
                <div class="mt-5">
                    <a href="{{ route('pegawai.loans.return.form', $loan) }}"
                       class="inline-flex rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                        Ajukan Pengembalian
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

@if ($loan->status === 'approved' && $loan->qr_token)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('copyTokenButton');
    const tokenField = document.getElementById('qrToken');
    const message = document.getElementById('copyTokenMessage');

    if (!button || !tokenField || !message) {
        return;
    }

    button.addEventListener('click', async function () {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(tokenField.value);
            } else {
                tokenField.focus();
                tokenField.select();
                tokenField.setSelectionRange(0, tokenField.value.length);

                const copied = document.execCommand('copy');

                if (!copied) {
                    throw new Error('Penyalinan otomatis tidak tersedia.');
                }
            }

            message.textContent = 'Token berhasil disalin. Tempelkan di halaman Admin.';
        } catch (error) {
            tokenField.focus();
            tokenField.select();
            message.textContent = 'Salin manual: tekan Ctrl+C setelah token dipilih.';
        }
    });
});
</script>
@endif
@endsection
