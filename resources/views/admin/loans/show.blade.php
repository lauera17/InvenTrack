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
                    Informasi lengkap transaksi peminjaman barang inventaris.
                </p>
            </div>

            <a href="{{ route('admin.loans.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">
                &larr; Kembali
            </a>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- INFORMASI UTAMA --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Nomor Transaksi
                        </p>

                        <h2 class="mt-1 text-2xl font-bold text-slate-900">
                            #{{ $loan->id }}
                        </h2>
                    </div>

                    @php
                        $statusClasses = [
                            'pending' => 'bg-amber-100 text-amber-800',
                            'approved' => 'bg-blue-100 text-blue-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'borrowed' => 'bg-purple-100 text-purple-800',
                            'return_pending' => 'bg-orange-100 text-orange-800',
                            'returned' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-green-100 text-green-800',
                        ];

                        $statusLabels = [
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'borrowed' => 'Sedang Dipinjam',
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

            {{-- DATA PEMINJAM --}}
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 sm:p-8">

                <div>
                    <p class="text-sm text-slate-500">Nama Peminjam</p>
                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $loan->user->name ?? 'Tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Email Peminjam</p>
                    <p class="mt-2 break-words font-semibold text-slate-900">
                        {{ $loan->user->email ?? 'Tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tanggal Pengajuan</p>
                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $loan->created_at ? $loan->created_at->format('d-m-Y H:i') : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Terakhir Diperbarui</p>
                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $loan->updated_at ? $loan->updated_at->format('d-m-Y H:i') : '-' }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-slate-500">
                        Keterangan Peminjaman
                    </p>

                    <div class="mt-2 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                        {{ $loan->description ?? $loan->reason ?? 'Tidak ada keterangan.' }}
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
                            @forelse($loan->items as $item)
                                <tr>
                                    <td class="px-4 py-4 font-medium text-slate-800">
                                        {{ $item->name ?? $item->item->name ?? 'Barang' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-slate-700">
                                        {{ $item->pivot->quantity ?? $item->quantity ?? 0 }}
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

            {{-- TAHAP 17: QR CODE PENGAMBILAN BARANG --}}
            @if($loan->status === 'approved' && $loan->qr_token)
                <div class="border-t border-slate-100 p-6 sm:p-8">
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-6 text-center">

                        <h3 class="text-xl font-bold text-blue-900">
                            QR Code Pengambilan Barang
                        </h3>

                        <p class="mt-2 text-sm text-blue-800">
                            Tunjukkan QR ini kepada operator untuk memverifikasi penyerahan.
                        </p>

                        <div class="mx-auto mt-5 flex max-w-xs justify-center rounded-xl bg-white p-4 shadow-sm">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(220)->margin(2)->generate($loan->qr_token) !!}
                        </div>

                        <p class="mt-4 text-xs text-slate-500">
                            Token transaksi:
                            {{ substr($loan->qr_token, 0, 8) }}••••
                        </p>

                        <a href="{{ route('admin.loans.scan') }}"
                           class="mt-5 inline-block rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                            Buka Halaman Verifikasi
                        </a>

                    </div>
                </div>
            @endif

            {{-- INFORMASI BERDASARKAN STATUS --}}
            <div class="border-t border-slate-100 bg-slate-50 p-6 sm:p-8">
                <h3 class="font-bold text-slate-900">
                    Informasi Status Peminjaman
                </h3>

                @if($loan->status === 'pending')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Pengajuan sedang menunggu persetujuan admin.
                        Periksa keterangan peminjaman dan ketersediaan stok.
                    </p>

                @elseif($loan->status === 'approved')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Peminjaman telah disetujui. Gunakan QR Code untuk
                        memverifikasi pengambilan barang.
                    </p>

                @elseif($loan->status === 'borrowed')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Barang sedang dipinjam. Tunggu pengembalian barang
                        dari pegawai.
                    </p>

                @elseif($loan->status === 'return_pending')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Pengembalian sedang menunggu pemeriksaan admin.
                    </p>

                @elseif($loan->status === 'returned' || $loan->status === 'completed')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Transaksi peminjaman telah selesai.
                    </p>

                @elseif($loan->status === 'rejected')
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Pengajuan peminjaman ini telah ditolak.
                    </p>

                @else
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Silakan periksa status transaksi untuk mengetahui
                        proses peminjaman selanjutnya.
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