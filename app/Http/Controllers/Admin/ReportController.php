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
     * Query laporan berdasarkan filter tanggal dan status.
     */
    private function query(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'nullable',
                'in:pending,approved,borrowed,return_requested,returned,rejected',
            ],
        ]);

        return Loan::with([
            'user',
            'items',
        ])
            ->when(
                $filters['start_date'] ?? null,
                function ($query, $date) {
                    $query->whereDate('created_at', '>=', $date);
                }
            )
            ->when(
                $filters['end_date'] ?? null,
                function ($query, $date) {
                    $query->whereDate('created_at', '<=', $date);
                }
            )
            ->when(
                $filters['status'] ?? null,
                function ($query, $status) {
                    $query->where('status', $status);
                }
            )
            ->latest();
    }

    /**
     * Menampilkan halaman laporan admin.
     */
    public function index(Request $request)
    {
        $loans = $this->query($request)
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact('loans'));
    }

    /**
     * Mengunduh laporan dalam format Excel.
     */
    public function excel(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'nullable',
                'in:pending,approved,borrowed,return_requested,returned,rejected',
            ],
        ]);

        return Excel::download(
            new LoansExport($filters),
            'laporan-inventrack.xlsx'
        );
    }

    /**
     * Mengunduh laporan dalam format PDF.
     */
    public function pdf(Request $request)
    {
        $loans = $this->query($request)
            ->limit(10000)
            ->get();

        $pdf = Pdf::loadView(
            'admin.reports.pdf',
            compact('loans')
        )->setPaper('a4', 'landscape');

        return $pdf->download('laporan-inventrack.pdf');
    }
}
