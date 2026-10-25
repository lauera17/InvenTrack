@extends('layouts.inventrack')

@section('title', 'Laporan')
@section('heading', 'Laporan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-slate-900">Laporan Peminjaman</h2>
    <p class="mt-2 text-sm text-slate-500">Pilih periode dan status untuk melihat atau mengunduh laporan.</p>
</div>

<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET" action="{{ route('admin.reports.index') }}"
          class="grid gap-4 md:grid-cols-4">
        <div>
            <label class="mb-2 block text-sm font-semibold">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                   class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm">
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                   class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm">
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Status</label>
            <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm">
                <option value="">Semua Status</option>
                @foreach(['pending','approved','borrowed','return_requested','returned','rejected'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                Terapkan Filter
            </button>
        </div>
    </form>

    <div class="mt-5 flex flex-wrap gap-3 border-t border-slate-100 pt-5">
        <a href="{{ route('admin.reports.excel', request()->query()) }}"
           class="rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
            ↓ Export Excel
        </a>

        <a href="{{ route('admin.reports.pdf', request()->query()) }}"
           class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
            ↓ Export PDF
        </a>
    </div>
</div>

<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Peminjam</th>
                    <th class="p-4">Barang</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($loans as $loan)
                    <tr>
                        <td class="p-4 font-semibold">#{{ $loan->id }}</td>
                        <td class="p-4">{{ $loan->user->name }}</td>
                        <td class="p-4">{{ $loan->items->map(fn($line) => $line->item->name)->join(', ') }}</td>
                        <td class="p-4">{{ $loan->created_at->format('d/m/Y') }}</td>
                        <td class="p-4">{{ ucfirst(str_replace('_', ' ', $loan->status)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-500">
                            Tidak ada data pada filter tersebut.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-slate-100 p-4">{{ $loans->links() }}</div>
</div>
@endsection