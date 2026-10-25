<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Query laporan berdasarkan filter.
     */
    private function query(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => [
                'nullable',
                'in:pending,approved,borrowed,return_requested,returned,rejected'
            ],
        ]);

        return Loan::with(['user', 'items.item'])
            ->when($filters['start_date'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($filters['end_date'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest();
    }

    /**
     * Menampilkan halaman laporan.
     */
    public function index(Request $request)
    {
        $loans = $this->query($request)
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact('loans'));
    }

    /**
     * Mengunduh laporan Excel.
     */
    public function excel(Request $request)
    {
        return Excel::download(
            new LoansExport(
                $request->validate([
                    'start_date' => ['nullable', 'date'],
                    'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
                    'status' => [
                        'nullable',
                        'in:pending,approved,borrowed,return_requested,returned,rejected'
                    ],
                ])
            ),
            'laporan-inventrack.xlsx'
        );
    }

    /**
     * Mengunduh laporan PDF.
     */
    public function pdf(Request $request)
    {
        $loans = $this->query($request)->limit(10000)->get();

        $pdf = Pdf::loadView(
            'admin.reports.pdf',
            compact('loans')
        )->setPaper('a4', 'landscape');

        return $pdf->download('laporan-inventrack.pdf');
    }
}