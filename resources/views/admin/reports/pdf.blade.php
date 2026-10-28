
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan InvenTrack</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1e293b;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 4px;
            color: #10243e;
        }

        p {
            color: #64748b;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #10243e;
            color: #ffffff;
            text-align: left;
        }

        th,
        td {
            padding: 7px;
            border: 1px solid #dbe3ef;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <h1>InvenTrack</h1>

    <p>
        Laporan Peminjaman Barang —
        Dicetak {{ now()->format('d/m/Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Penyerahan</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($loans as $loan)
                @php
                    $items = $loan->items;

                    $totalUnit = $items->sum(function ($item) {
                        return (int) ($item->pivot->quantity ?? 0);
                    });
                @endphp

                <tr>
                    {{-- ID transaksi --}}
                    <td>{{ $loan->id }}</td>

                    {{-- Nama peminjam --}}
                    <td>
                        {{ $loan->user?->name ?? 'Pengguna tidak ditemukan' }}
                    </td>

                    {{-- Nama barang --}}
                    <td>
                        @forelse($items as $item)
                            {{ $item->name ?? 'Barang tidak ditemukan' }}
                            @if (!$loop->last)
                                <br>
                            @endif
                        @empty
                            Belum ada barang
                        @endforelse
                    </td>

                    {{-- Total unit yang dipinjam --}}
                    <td class="text-center">
                        {{ $totalUnit }}
                    </td>

                    {{-- Tanggal pengajuan --}}
                    <td>
                        {{ $loan->created_at?->format('d/m/Y') ?? '-' }}
                    </td>

                    {{-- Tanggal penyerahan --}}
                    <td>
                        {{ $loan->handed_over_at?->format('d/m/Y') ?? '-' }}
                    </td>

                    {{-- Tanggal pengembalian --}}
                    <td>
                        {{ $loan->returned_at?->format('d/m/Y') ?? '-' }}
                    </td>

                    {{-- Status peminjaman --}}
                    <td class="status">
                        {{ ucfirst(str_replace('_', ' ', $loan->status ?? 'unknown')) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data peminjaman untuk ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem InvenTrack.
    </p>
</body>
</html>
