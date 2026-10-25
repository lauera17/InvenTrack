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

    public function query(): Builder
    {
        return Loan::query()
            ->with(['user', 'items.item'])
            ->when($this->filters['start_date'] ?? null, fn ($q, $date) =>
                $q->whereDate('created_at', '>=', $date)
            )
            ->when($this->filters['end_date'] ?? null, fn ($q, $date) =>
                $q->whereDate('created_at', '<=', $date)
            )
            ->when($this->filters['status'] ?? null, fn ($q, $status) =>
                $q->where('status', $status)
            )
            ->latest();
    }

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

    public function map($loan): array
    {
        return [
            $loan->id,
            $loan->user->name,
            $loan->user->email,
            $loan->items->map(fn ($line) =>
                $line->item->name
            )->join(', '),
            $loan->items->sum('quantity'),
            $loan->purpose,
            $loan->created_at?->format('Y-m-d H:i:s'),
            $loan->handed_over_at?->format('Y-m-d H:i:s'),
            $loan->returned_at?->format('Y-m-d H:i:s'),
            $loan->status,
        ];
    }
}