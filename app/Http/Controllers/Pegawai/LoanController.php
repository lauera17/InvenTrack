<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use App\Models\LoanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $loans = Loan::with(['items', 'returnDocuments'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('pegawai.loans.index', compact('loans'));
    }

    public function create()
    {
        $items = Item::with('category')
            ->where('stock', '>', 0)
            ->where('condition', 'Baik')
            ->orderBy('name')
            ->get();

        return view('pegawai.loans.create', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purpose' => ['required', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => [
                'required',
                'integer',
                'distinct',
                'exists:items,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],
        ]);

        $loan = DB::transaction(function () use ($request, $data) {
            $requested = collect($data['items'])
                ->keyBy(fn ($line) => (int) $line['id']);

            $items = Item::whereIn('id', $requested->keys())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($items->count() !== $requested->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Ada barang yang tidak ditemukan.',
                ]);
            }

            foreach ($requested as $itemId => $line) {
                $item = $items->get($itemId);
                $quantity = (int) $line['quantity'];

                if (
                    !$item ||
                    strtolower(trim((string) $item->condition)) !== 'baik' ||
                    (int) $item->stock < $quantity
                ) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok atau kondisi barang tidak memenuhi syarat.',
                    ]);
                }
            }

            do {
                $loanCode = 'LOAN-' .
                    now()->format('Ymd') . '-' .
                    Str::upper(Str::random(5));
            } while (Loan::where('loan_code', $loanCode)->exists());

            $loan = Loan::create([
                'loan_code' => $loanCode,
                'user_id' => $request->user()->id,
                'purpose' => $data['purpose'],
                'reason' => $data['purpose'],
                'status' => 'pending',
                'qr_token' => Str::random(64),
            ]);

            foreach ($requested as $itemId => $line) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'item_id' => (int) $itemId,
                    'quantity' => (int) $line['quantity'],
                ]);
            }

            return $loan;
        });

        return redirect()
            ->route('pegawai.loans.show', $loan)
            ->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function show(Request $request, Loan $loan)
    {
        abort_unless(
            (int) $loan->user_id === (int) $request->user()->id,
            403
        );

        // Buat token untuk transaksi lama yang belum memiliki token,
        // tetapi hanya setelah pengajuan disetujui atau saat dipinjam.
        if (
            in_array($loan->status, ['approved', 'borrowed'], true) &&
            empty($loan->qr_token)
        ) {
            $loan->qr_token = Str::random(64);
            $loan->save();
        }

        $loan->load([
            'user',
            'items',
            'returnDocuments',
        ]);

        return view('pegawai.loans.show', compact('loan'));
    }

    public function returnForm(Request $request, Loan $loan)
    {
        abort_unless(
            (int) $loan->user_id === (int) $request->user()->id,
            403
        );

        if ($loan->status !== 'borrowed') {
            return redirect()
                ->route('pegawai.loans.show', $loan)
                ->with('error', 'Barang belum dapat dikembalikan.');
        }

        $loan->load('items');

        return view('pegawai.loans.return', compact('loan'));
    }

    public function submitReturn(Request $request, Loan $loan)
    {
        abort_unless(
            (int) $loan->user_id === (int) $request->user()->id,
            403
        );

        if ($loan->status !== 'borrowed') {
            return back()->with(
                'error',
                'Peminjaman ini belum dapat diajukan untuk pengembalian.'
            );
        }

        $data = $request->validate([
            'return_note' => ['required', 'string', 'max:2000'],
            'return_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        // Simpan foto ke storage/app/public/returns.
        $path = $request->file('return_photo')
            ->store('returns', 'public');

        try {
            DB::transaction(function () use (
                $loan,
                $request,
                $data,
                $path
            ) {
                $locked = Loan::whereKey($loan->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    (int) $locked->user_id !== (int) $request->user()->id ||
                    $locked->status !== 'borrowed'
                ) {
                    throw ValidationException::withMessages([
                        'return_note' => 'Status peminjaman sudah berubah. Muat ulang halaman.',
                    ]);
                }

                /*
                 * BAGIAN INI MENGASUMSIKAN kolom return_note dan
                 * return_photo berada di tabel loans.
                 * Jika proyekmu menyimpan pengembalian pada tabel
                 * return_documents, bagian ini harus disesuaikan
                 * dengan struktur tabel dan model ReturnDocument.
                 */
                $locked->update([
                    'return_note' => $data['return_note'],
                    'return_photo' => $path,
                    'status' => 'return_requested',
                ]);
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);
            throw $exception;
        }

        return redirect()
            ->route('pegawai.loans.show', $loan)
            ->with(
                'success',
                'Pengajuan pengembalian berhasil dikirim. Tunggu pemeriksaan Admin.'
            );
    }
}