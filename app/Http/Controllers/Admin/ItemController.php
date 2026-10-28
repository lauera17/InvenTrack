<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar barang.
     */
    public function index(Request $request)
    {
        $items = Item::with('category')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.items.index', compact('items', 'categories'));
    }

    /**
     * Menampilkan formulir tambah barang.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.items.create', compact('categories'));
    }

    /**
     * Menyimpan barang baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'condition' => [
                'required',
                Rule::in([
                    'Baik',
                    'Rusak Ringan',
                    'Rusak Berat',
                ]),
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        // Membuat kode barang otomatis dan unik.
        do {
            $code = 'INV-' . now()->format('Ymd') . '-'
                . Str::upper(Str::random(5));
        } while (Item::where('code', $code)->exists());

        $data['code'] = $code;
        $data['minimum_stock'] = 2;
        $data['location'] = null;

        // Menyimpan foto barang.
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('items', 'public');
        }

        Item::create($data);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail barang.
     */
    public function show(Item $item)
    {
        $item->load('category');

        return view('admin.items.show', compact('item'));
    }

    /**
     * Menampilkan formulir edit barang.
     */
    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * Memperbarui data barang.
     */
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('items', 'code')->ignore($item->id),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'minimum_stock' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],
            'condition' => [
                'required',
                Rule::in([
                    'Baik',
                    'Rusak Ringan',
                    'Rusak Berat',
                ]),
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        // Jika kode tidak dikirim dari formulir, kode lama tetap dipakai.
        if (!array_key_exists('code', $data)) {
            unset($data['code']);
        }

        // Jika stok minimum tidak dikirim, nilai lama tetap dipakai.
        if (!array_key_exists('minimum_stock', $data)) {
            unset($data['minimum_stock']);
        }

        // Mengganti foto hanya jika ada foto baru.
        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $data['image'] = $request->file('image')
                ->store('items', 'public');
        } else {
            unset($data['image']);
        }

        $item->update($data);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang.
     */
    public function destroy(Item $item)
    {
        // Barang dengan riwayat peminjaman tidak boleh dihapus.
        if ($item->loanItems()->exists()) {
            return back()->with(
                'error',
                'Barang memiliki riwayat transaksi sehingga tidak dapat dihapus.'
            );
        }

        // Menghapus foto barang jika tersedia.
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}