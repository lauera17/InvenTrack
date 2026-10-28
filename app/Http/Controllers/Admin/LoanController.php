<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    /**
     * Menampilkan daftar seluruh peminjaman untuk Admin.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'items'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('loan_code', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $loans = $query->paginate(10)->withQueryString();

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Menampilkan detail peminjaman.
     */
    public function show(Loan $loan)
    {
        $loan->load(['user', 'items', 'returnDocuments']);

        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Menampilkan formulir pemindaian QR.
     */
    public function scanForm()
    {
        return view('admin.loans.scan');
    }

    /**
     * Menyetujui pengajuan peminjaman.
     *
     * Stok belum dikurangi pada tahap persetujuan.
     * Stok dikurangi ketika barang benar-benar diserahkan.
     */
    public function approve(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            $lockedLoan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedLoan->status !== 'pending') {
                throw ValidationException::withMessages([
                    'loan' => 'Hanya pengajuan berstatus pending yang dapat disetujui.',
                ]);
            }

            $lockedLoan->update([
                'status' => 'approved',
            ]);
        });

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pengajuan peminjaman berhasil disetujui.');
    }

    /**
     * Menolak pengajuan yang belum diserahkan kepada peminjam.
     */
    public function reject(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            $lockedLoan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($lockedLoan->status, ['pending', 'approved'], true)) {
                throw ValidationException::withMessages([
                    'loan' => 'Pengajuan ini tidak dapat ditolak pada status sekarang.',
                ]);
            }

            $lockedLoan->update([
                'status' => 'rejected',
            ]);
        });

        return redirect()
            ->route('admin.loans.index')
            ->with('success', 'Pengajuan peminjaman berhasil ditolak.');
    }

    /**
     * Memproses QR saat barang diserahkan kepada pegawai.
     *
     * QR berisi token acak, bukan ID peminjaman.
     */
    public function handover(Request $request)
    {
        $data = $request->validate([
            'qr_token' => ['required', 'string', 'max:255'],
        ]);

        $loan = DB::transaction(function () use ($data) {
            $lockedLoan = Loan::where('qr_token', $data['qr_token'])
                ->lockForUpdate()
                ->first();

            if (!$lockedLoan) {
                throw ValidationException::withMessages([
                    'qr_token' => 'QR tidak valid atau peminjaman tidak ditemukan.',
                ]);
            }

            if ($lockedLoan->status !== 'approved') {
                throw ValidationException::withMessages([
                    'qr_token' => 'Peminjaman harus disetujui sebelum barang diserahkan.',
                ]);
            }

            $loanItems = $lockedLoan->items()
                ->orderBy('items.id')
                ->lockForUpdate()
                ->get();

            if ($loanItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'qr_token' => 'Peminjaman ini belum memiliki barang.',
                ]);
            }

            foreach ($loanItems as $item) {
                $quantity = (int) $item->pivot->quantity;

                if (
                    (int) $item->stock < $quantity ||
                    strtolower(trim((string) $item->condition)) !== 'baik'
                ) {
                    throw ValidationException::withMessages([
                        'qr_token' => 'Stok atau kondisi salah satu barang tidak memenuhi syarat.',
                    ]);
                }
            }

            foreach ($loanItems as $item) {
                $quantity = (int) $item->pivot->quantity;

                Item::whereKey($item->id)->decrement('stock', $quantity);
            }

            $lockedLoan->update([
                'status' => 'borrowed',
            ]);

            return $lockedLoan;
        });

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'QR berhasil diverifikasi. Barang telah dicatat sebagai dipinjam.');
    }

    /**
     * Menyetujui pengembalian dan mengembalikan stok barang.
     */
    public function approveReturn(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            $lockedLoan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedLoan->status !== 'return_requested') {
                throw ValidationException::withMessages([
                    'loan' => 'Hanya pengembalian yang menunggu pemeriksaan yang dapat disetujui.',
                ]);
            }

            $loanItems = $lockedLoan->items()
                ->orderBy('items.id')
                ->lockForUpdate()
                ->get();

            if ($loanItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'loan' => 'Data barang pada peminjaman ini tidak ditemukan.',
                ]);
            }

            foreach ($loanItems as $item) {
                $quantity = (int) $item->pivot->quantity;

                Item::whereKey($item->id)->increment('stock', $quantity);
            }

            $lockedLoan->update([
                'status' => 'returned',
            ]);
        });

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pengembalian disetujui dan stok barang telah diperbarui.');
    }
}
