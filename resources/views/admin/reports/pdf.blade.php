<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan InvenTrack</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1e293b; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        p { color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #10243e; color: white; text-align: left; }
        th, td { padding: 7px; border: 1px solid #dbe3ef; vertical-align: top; }
        tr:nth-child(even) { background: #f8fafc; }
    </style>
</head>
<body>
    <h1>InvenTrack</h1>
    <p>Laporan Peminjaman Barang — Dicetak {{ now()->format('d/m/Y H:i') }}</p>

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
            @foreach($loans as $loan)
                <tr>
                    <td>{{ $loan->id }}</td>
                    <td>{{ $loan->user->name }}</td>
                    <td>{{ $loan->items->map(fn($line) => $line->item->name)->join(', ') }}</td>
                    <td>{{ $loan->items->sum('quantity') }}</td>
                    <td>{{ $loan->created_at?->format('d/m/Y') }}</td>
                    <td>{{ $loan->handed_over_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $loan->returned_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $loan->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>