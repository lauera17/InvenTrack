<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Data Pengguna
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | 2. Statistik Barang
        |--------------------------------------------------------------------------
        */

        $totalItems = Item::count();

        $totalStock = Item::sum('stock');

        /*
        |--------------------------------------------------------------------------
        | 3. Statistik Peminjaman
        |--------------------------------------------------------------------------
        */

        $totalLoans = Loan::count();

        $pendingLoans = Loan::where('status', 'pending')->count();

        $approvedLoans = Loan::where('status', 'approved')->count();

        $borrowedLoans = Loan::where('status', 'borrowed')->count();

        $returnRequests = Loan::where(
            'status',
            'return_requested'
        )->count();

        $returnedLoans = Loan::where('status', 'returned')->count();

        $rejectedLoans = Loan::where('status', 'rejected')->count();

        /*
        |--------------------------------------------------------------------------
        | 4. Jumlah Pegawai
        |--------------------------------------------------------------------------
        */

        $totalEmployees = User::where('role', 'pegawai')->count();

        /*
        |--------------------------------------------------------------------------
        | 5. Jumlah Peminjaman Pengguna yang Login
        |--------------------------------------------------------------------------
        */

        $myLoanCount = 0;

        if ($user) {
            $myLoanCount = Loan::where(
                'user_id',
                $user->id
            )->count();
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Barang Terbaru
        |--------------------------------------------------------------------------
        */

        $latestItems = Item::with('category')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 7. Peminjaman Terbaru
        |--------------------------------------------------------------------------
        */

        $latestLoansQuery = Loan::with([
            'user',
            'items',
        ])->latest();

        // Admin melihat seluruh transaksi.
        // Pegawai hanya melihat transaksi miliknya.
        if (($user?->role ?? '') !== 'admin' && $user) {
            $latestLoansQuery->where('user_id', $user->id);
        }

        $latestLoans = $latestLoansQuery
            ->take(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 8. Data Grafik Enam Bulan Terakhir
        |--------------------------------------------------------------------------
        */

        $monthlyLoans = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()
                ->startOfMonth()
                ->subMonths($i);

            $count = Loan::whereBetween('created_at', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
            ])->count();

            $monthlyLoans->push([
                'label' => $date->translatedFormat('M'),
                'total' => $count,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Kirim Semua Variabel ke Halaman Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'user',
            'totalItems',
            'totalStock',
            'totalLoans',
            'pendingLoans',
            'approvedLoans',
            'borrowedLoans',
            'returnRequests',
            'returnedLoans',
            'rejectedLoans',
            'totalEmployees',
            'myLoanCount',
            'latestItems',
            'latestLoans',
            'monthlyLoans'
        ));
    }
}