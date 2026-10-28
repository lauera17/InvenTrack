<?php

namespace App\Exports;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoansExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private array $filters = [])
    {
    }

    /**
     * Mengambil data peminjaman sesuai filter.
     */
    public function query(): Builder
    {
        return Loan::query()
            ->with([
                'user',
                'items',
            ])
            ->when(
                $this->filters['start_date'] ?? null,
                function ($query, $date) {
                    $query->whereDate('created_at', '>=', $date);
                }
            )
            ->when(
                $this->filters['end_date'] ?? null,
                function ($query, $date) {
                    $query->whereDate('created_at', '<=', $date);
                }
            )
            ->when(
                $this->filters['status'] ?? null,
                function ($query, $status) {
                    $query->where('status', $status);
                }
            )
            ->latest();
    }

    /**
     * Judul kolom pada file Excel.
     */
    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Peminjam',
            'Email',
            'Barang',
            'Jumlah Unit',
            'Tujuan Peminjaman',
            'Tanggal Pengajuan',
            'Tanggal Penyerahan',
            'Tanggal Pengembalian',
            'Status',
        ];
    }

    /**
     * Memetakan data transaksi ke kolom Excel.
     */
    public function map($loan): array
    {
        $items = $loan->items;

        $namaBarang = $items
            ->map(function ($item) {
                $nama = $item->name ?? 'Barang tidak ditemukan';
                $jumlah = $item->pivot->quantity ?? 0;

                return $nama . ' (' . $jumlah . ' unit)';
            })
            ->join(', ');

        $totalUnit = $items->sum(function ($item) {
            return (int) ($item->pivot->quantity ?? 0);
        });

        return [
            $loan->id,
            $loan->user?->name ?? 'Pengguna tidak ditemukan',
            $loan->user?->email ?? '-',
            $namaBarang !== '' ? $namaBarang : 'Belum ada barang',
            $totalUnit,
            $loan->purpose ?? $loan->reason ?? '-',
            $loan->created_at?->format('Y-m-d H:i:s') ?? '-',

            // Kolom tanggal berikut hanya ditampilkan jika
            // kolom tersebut tersedia pada model/database.
            $loan->handed_over_at?->format('Y-m-d H:i:s') ?? '-',
            $loan->returned_at?->format('Y-m-d H:i:s') ?? '-',

            ucfirst(str_replace('_', ' ', $loan->status ?? 'unknown')),
        ];
    }
}
