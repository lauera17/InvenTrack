<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $loans = Loan::with(['user', 'items.item'])
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->whereHas('user', fn ($u) =>
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                );
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.loans.index', compact('loans'));
    }

    public function show(Loan $loan)
    {
        $loan->load(['user', 'items.item', 'approver', 'returnApprover']);

        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Loan $loan)
    {
        $approved = DB::transaction(function () use ($loan) {
            $locked = Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== 'pending') {
                return false;
            }

            $locked->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'qr_token' => Str::random(48),
            ]);

            return true;
        });

        if (!$approved) {
            return back()->with('error', 'Pengajuan sudah diproses atau statusnya berubah.');
        }

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Pengajuan disetujui. QR pengambilan sudah tersedia.');
    }

    public function reject(Request $request, Loan $loan)
    {
        $request->validate([
            'rejection_note' => ['required', 'string', 'max:1000'],
        ]);

        $rejected = DB::transaction(function () use ($loan, $request) {
            $locked = Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== 'pending') {
                return false;
            }

            $locked->update([
                'status' => 'rejected',
                'purpose' => $locked->purpose
                    . "\n\nAlasan penolakan: "
                    . $request->rejection_note,
            ]);

            return true;
        });

        if (!$rejected) {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        return redirect()->route('admin.loans.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function scanForm()
    {
        return view('admin.loans.scan');
    }

    public function handover(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string', 'size:48'],
        ]);

        $loanId = Loan::where('qr_token', $data['token'])
            ->value('id');

        if (!$loanId) {
            return back()->with('error', 'QR Code tidak valid.');
        }

        $result = DB::transaction(function () use ($loanId, $data) {
            $loan = Loan::whereKey($loanId)->lockForUpdate()->firstOrFail();

            if (
                $loan->status !== 'approved' ||
                !hash_equals((string) $loan->qr_token, $data['token'])
            ) {
                return false;
            }

            $lines = $loan->items()
                ->with('item')
                ->orderBy('item_id')
                ->get();

            foreach ($lines as $line) {
                $item = \App\Models\Item::whereKey($line->item_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($item->condition !== 'baik' || $item->stock < $line->quantity) {
                    throw new \RuntimeException(
                        "Stok {$item->name} tidak lagi mencukupi."
                    );
                }
            }

            foreach ($lines as $line) {
                $item = \App\Models\Item::whereKey($line->item_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $item->decrement('stock', $line->quantity);
            }

            $loan->update([
                'status' => 'borrowed',
                'handed_over_at' => now(),
                'qr_token' => null,
            ]);

            return true;
        });

        if (!$result) {
            return back()->with('error', 'QR sudah digunakan atau peminjaman tidak siap diserahkan.');
        }

        return redirect()->route('admin.loans.show', $loanId)
            ->with('success', 'Penyerahan barang berhasil dikonfirmasi.');
    }

    public function approveReturn(Loan $loan)
    {
        $approved = DB::transaction(function () use ($loan) {
            $locked = Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== 'return_requested') {
                return false;
            }

            $lines = $locked->items()->orderBy('item_id')->get();

            foreach ($lines as $line) {
                $item = \App\Models\Item::whereKey($line->item_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $item->increment('stock', $line->quantity);
            }

            $locked->update([
                'status' => 'returned',
                'returned_at' => now(),
                'return_approved_by' => auth()->id(),
            ]);

            return true;
        });

        if (!$approved) {
            return back()->with('error', 'Pengembalian sudah diproses.');
        }

        return back()->with('success', 'Pengembalian disetujui dan stok diperbarui.');
    }
}