
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">

        {{-- HEADER --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">
                    InvenTrack / Data Peminjaman
                </p>

                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    Detail Peminjaman
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Informasi transaksi, bukti pengembalian, dan verifikasi barang.
                </p>
            </div>

            <a href="{{ route('admin.loans.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100">
                &larr; Kembali
            </a>
        </div>

        {{-- PESAN SUKSES --}}
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- PESAN ERROR --}}
        @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- VALIDASI ERROR --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <p class="font-semibold">Terjadi kesalahan:</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- INFORMASI TRANSAKSI --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Nomor Transaksi
                        </p>

                        <h2 class="mt-1 text-2xl font-bold text-slate-900">
                            {{ $loan->loan_code ?? '#' . $loan->id }}
                        </h2>
                    </div>

                    @php
                        $statusClasses = [
                            'pending' => 'bg-amber-100 text-amber-800',
                            'approved' => 'bg-blue-100 text-blue-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'borrowed' => 'bg-purple-100 text-purple-800',
                            'return_requested' => 'bg-orange-100 text-orange-800',
                            'return_pending' => 'bg-orange-100 text-orange-800',
                            'returned' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-green-100 text-green-800',
                        ];

                        $statusLabels = [
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'borrowed' => 'Sedang Dipinjam',
                            'return_requested' => 'Menunggu Pemeriksaan Pengembalian',
                            'return_pending' => 'Menunggu Pengembalian',
                            'returned' => 'Sudah Dikembalikan',
                            'completed' => 'Selesai',
                        ];
                    @endphp

                    <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-semibold {{ $statusClasses[$loan->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $statusLabels[$loan->status] ?? ucfirst(str_replace('_', ' ', $loan->status)) }}
                    </span>
                </div>
            </div>

            {{-- INFORMASI PEMINJAM --}}
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 sm:p-8">

                <div>
                    <p class="text-sm text-slate-500">
                        Nama Peminjam
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $loan->user->name ?? 'Tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Email Peminjam
                    </p>

                    <p class="mt-2 break-words font-semibold text-slate-900">
                        {{ $loan->user->email ?? 'Tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Tanggal Pengajuan
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $loan->created_at?->format('d-m-Y H:i') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Terakhir Diperbarui
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $loan->updated_at?->format('d-m-Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-slate-500">
                        Keterangan Peminjaman
                    </p>

                    <div class="mt-2 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                        {{ $loan->purpose ?? $loan->description ?? $loan->reason ?? 'Tidak ada keterangan.' }}
                    </div>
                </div>
            </div>

            {{-- DAFTAR BARANG --}}
            <div class="border-t border-slate-100 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-slate-900">
                    Daftar Barang yang Dipinjam
                </h3>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full min-w-[420px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500">
                                <th class="px-4 py-3 font-semibold">
                                    Nama Barang
                                </th>

                                <th class="px-4 py-3 text-center font-semibold">
                                    Jumlah
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($loan->items as $item)
                                <tr>
                                    <td class="px-4 py-4 font-medium text-slate-800">
                                        {{ $item->name ?? 'Barang' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-slate-700">
                                        {{ $item->pivot->quantity ?? 0 }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-6 text-center text-slate-500">
                                        Belum ada barang dalam transaksi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- DETAIL PENGEMBALIAN --}}
            <div class="border-t border-slate-100 p-6 sm:p-8">
                <div>
                    <p class="text-sm font-medium text-orange-600">
                        Pemeriksaan Pengembalian
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-slate-900">
                        Bukti dan Kondisi Barang
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Periksa foto dan keterangan dari pegawai sebelum menyetujui pengembalian.
                    </p>
                </div>

                @if (in_array($loan->status, [
                    'return_requested',
                    'return_pending',
                    'returned',
                    'completed'
                ], true))

                    {{-- KETERANGAN PENGEMBALIAN --}}
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-slate-700">
                            Keterangan Pengembalian dari Pegawai
                        </p>

                        <div class="mt-2 whitespace-pre-line rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-7 text-slate-700">
                            @if (filled($loan->return_note))
                                {{ $loan->return_note }}
                            @else
                                <span class="text-slate-400">
                                    Pegawai belum memberikan keterangan pengembalian.
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- FOTO BUKTI PENGEMBALIAN --}}
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-slate-700">
                            Foto Bukti Pengembalian
                        </p>

                        @php
                            $returnPhotoPath = ltrim(
                                str_replace(
                                    '\\',
                                    '/',
                                    (string) $loan->return_photo
                                ),
                                '/'
                            );

                            $returnPhotoUrl = $returnPhotoPath !== ''
                                ? asset('storage/' . $returnPhotoPath)
                                : null;
                        @endphp

                        @if ($returnPhotoPath !== '')
                            <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-4">

                                <a href="{{ $returnPhotoUrl }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-block max-w-full">

                                    <img
                                        src="{{ $returnPhotoUrl }}"
                                        alt="Foto bukti pengembalian barang"
                                        class="max-h-[500px] max-w-full rounded-xl object-contain sm:w-auto"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                    >

                                    <p
                                        style="display: none;"
                                        class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                                    >
                                        Foto tidak dapat dimuat. Periksa URL foto dan lokasi file.
                                    </p>
                                </a>

                                <p class="mt-3 break-all text-xs text-slate-500">
                                    Lokasi foto:
                                    {{ $returnPhotoPath }}
                                </p>

                                <a href="{{ $returnPhotoUrl }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="mt-3 inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                    Buka Foto Asli
                                </a>
                            </div>
                        @else
                            <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                                Foto bukti pengembalian belum tersedia pada data transaksi ini.
                            </div>
                        @endif
                    </div>

                    {{-- TOMBOL PERSETUJUAN PENGEMBALIAN --}}
                    @if ($loan->status === 'return_requested')
                        <div class="mt-6 rounded-xl border border-orange-200 bg-orange-50 p-5">
                            <h4 class="font-bold text-orange-900">
                                Pengembalian Menunggu Persetujuan
                            </h4>

                            <p class="mt-2 text-sm leading-6 text-orange-800">
                                Pastikan barang yang dikembalikan sudah diperiksa secara fisik.
                                Setelah disetujui, stok akan ditambahkan kembali dan transaksi
                                berubah menjadi selesai.
                            </p>

                            <form
                                action="{{ route('admin.loans.approve-return', $loan) }}"
                                method="POST"
                                class="mt-5"
                                onsubmit="return confirm('Apakah kamu yakin sudah memeriksa barang dan ingin menyetujui pengembalian ini?');"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 sm:w-auto"
                                >
                                    Setujui Pengembalian
                                </button>
                            </form>
                        </div>

                    @elseif (in_array($loan->status, ['returned', 'completed'], true))
                        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-5 text-sm leading-6 text-green-800">
                            <p class="font-semibold">
                                Pengembalian telah disetujui.
                            </p>

                            <p class="mt-1">
                                Transaksi ini sudah ditandai selesai oleh sistem.
                            </p>
                        </div>
                    @endif

                @else
                    <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-600">
                        Belum ada pengajuan pengembalian untuk transaksi ini.
                        Detail foto dan keterangan akan muncul setelah pegawai mengirimkan
                        bukti pengembalian.
                    </div>
                @endif
            </div>

            {{-- QR CODE PENGAMBILAN BARANG --}}
            <div class="border-t border-slate-100 p-6 sm:p-8">
                <h3 class="text-xl font-bold text-slate-900">
                    QR Code Pengambilan Barang
                </h3>

                @if ($loan->status === 'approved' && filled($loan->qr_token))
                    <div class="mt-4 rounded-2xl border border-blue-200 bg-blue-50 p-6 text-center">
                        <p class="text-sm leading-6 text-blue-900">
                            Pengajuan telah disetujui. Gunakan QR berikut untuk verifikasi
                            penyerahan barang.
                        </p>

                        <div class="mx-auto mt-5 inline-block rounded-xl bg-white p-4 shadow-sm">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(240)->margin(2)->generate($loan->qr_token) !!}
                        </div>

                        <p class="mt-3 text-sm font-semibold text-slate-700">
                            {{ $loan->loan_code ?? 'Transaksi #' . $loan->id }}
                        </p>

                        <div class="mt-5 flex flex-col justify-center gap-3 sm:flex-row">
                            <a href="{{ route('admin.loans.scan') }}"
                               class="inline-flex justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                                Buka Verifikasi Penyerahan
                            </a>

                            <button
                                type="button"
                                onclick="window.print()"
                                class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                            >
                                Cetak Halaman
                            </button>
                        </div>
                    </div>

                @elseif ($loan->status === 'approved' && blank($loan->qr_token))
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-5 text-sm text-red-800">
                        Token QR belum tersedia. Periksa proses pembuatan dan penyimpanan token.
                    </div>

                @elseif ($loan->status === 'pending')
                    <div class="mt-4 rounded-xl bg-amber-50 p-5 text-sm text-amber-800">
                        QR pengambilan belum ditampilkan karena pengajuan masih menunggu persetujuan Admin.
                    </div>

                @elseif ($loan->status === 'borrowed')
                    <div class="mt-4 rounded-xl bg-purple-50 p-5 text-sm text-purple-800">
                        Barang sudah tercatat diserahkan. QR pengambilan tidak lagi ditampilkan.
                    </div>

                @else
                    <div class="mt-4 rounded-xl bg-slate-50 p-5 text-sm text-slate-600">
                        QR pengambilan tidak tersedia untuk status transaksi ini.
                    </div>
                @endif
            </div>

            {{-- INFORMASI STATUS PEMINJAMAN --}}
            <div class="border-t border-slate-100 bg-slate-50 p-6 sm:p-8">
                <h3 class="font-bold text-slate-900">
                    Informasi Status Peminjaman
                </h3>

                @if ($loan->status === 'pending')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Pengajuan sedang menunggu persetujuan Admin.
                    </p>

                @elseif ($loan->status === 'approved')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Peminjaman disetujui. Lanjutkan verifikasi QR saat barang diserahkan.
                    </p>

                @elseif ($loan->status === 'borrowed')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Barang sedang dipinjam. Tunggu pengajuan pengembalian dari pegawai.
                    </p>

                @elseif (in_array($loan->status, ['return_requested', 'return_pending'], true))
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Pengembalian menunggu pemeriksaan Admin.
                    </p>

                @elseif (in_array($loan->status, ['returned', 'completed'], true))
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Transaksi peminjaman telah selesai.
                    </p>

                @elseif ($loan->status === 'rejected')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Pengajuan peminjaman ini telah ditolak.
                    </p>

                @else
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Status transaksi:
                        {{ $loan->status }}.
                    </p>
                @endif
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="mt-6 text-center text-xs text-slate-400">
            InvenTrack &copy; {{ date('Y') }}
            — Sistem Informasi Peminjaman Inventaris
        </div>

    </div>
</div>
@endsection
