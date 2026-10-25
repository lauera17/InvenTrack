<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $loans = Loan::with('items.item')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('pegawai.loans.index', compact('loans'));
    }

    public function create()
    {
        $items = Item::with('category')
            ->where('stock', '>', 0)
            ->where('condition', 'baik')
            ->orderBy('name')
            ->get();

        return view('pegawai.loans.create', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purpose' => ['required', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $loan = DB::transaction(function () use ($request, $data) {
            $loan = Loan::create([
                'user_id' => $request->user()->id,
                'purpose' => $data['purpose'],
                'status' => 'pending',
            ]);

            $requested = collect($data['items'])->keyBy('id');

            $items = Item::whereIn('id', $requested->keys())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($items->count() !== $requested->count()) {
                abort(422, 'Ada barang yang tidak ditemukan.');
            }

            foreach ($requested as $itemId => $line) {
                $item = $items->get($itemId);
                $quantity = (int) $line['quantity'];

                if ($item->condition !== 'baik' || $item->stock < $quantity) {
                    abort(422, "Stok {$item->name} tidak mencukupi.");
                }

                $loan->items()->create([
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                ]);
            }

            return $loan;
        });

        return redirect()->route('pegawai.loans.show', $loan)
            ->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function show(Request $request, Loan $loan)
    {
        abort_unless($loan->user_id === $request->user()->id, 403);

        $loan->load('items.item');

        return view('pegawai.loans.show', compact('loan'));
    }

    public function returnForm(Request $request, Loan $loan)
    {
        abort_unless($loan->user_id === $request->user()->id, 403);
        abort_unless($loan->status === 'borrowed', 403);

        return view('pegawai.loans.return', compact('loan'));
    }

    public function submitReturn(Request $request, Loan $loan)
    {
        abort_unless($loan->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'return_note' => ['required', 'string', 'max:2000'],
            'return_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $path = $data['return_photo']->store('returns', 'public');

        $updated = DB::transaction(function () use ($loan, $request, $data, $path) {
            $locked = Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();

            if (
                $locked->user_id !== $request->user()->id ||
                $locked->status !== 'borrowed'
            ) {
                return false;
            }

            $locked->update([
                'return_note' => $data['return_note'],
                'return_photo' => $path,
                'status' => 'return_requested',
            ]);

            return true;
        });

        if (!$updated) {
            return back()->with('error', 'Status peminjaman sudah berubah. Muat ulang halaman.');
        }

        return redirect()->route('pegawai.loans.show', $loan)
            ->with('success', 'Pengembalian diajukan. Tunggu pemeriksaan Admin.');
    }
}