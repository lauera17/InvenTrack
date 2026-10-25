<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        $stats = [
            'total' => Item::count(),
            'available' => Item::sum('stock'),
            'pending' => Loan::where('status', 'pending')->count(),
            'active' => Loan::whereIn('status', [
                'approved',
                'borrowed',
                'return_requested',
            ])->count(),
        ];

        $loans = Loan::with(['user', 'items.item'])
            ->when(!$isAdmin, fn ($query) =>
                $query->where('user_id', $user->id)
            )
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'user',
            'isAdmin',
            'stats',
            'loans'
        ));
    }
}
