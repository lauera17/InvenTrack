
@extends('layouts.inventrack')

@section('title', 'Laporan')
@section('heading', 'Laporan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-slate-900">
        Laporan Peminjaman
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Pilih periode dan status untuk melihat atau mengunduh laporan.
    </p>
</div>

{{-- Filter laporan --}}
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.reports.index') }}"
          class="grid gap-4 md:grid-cols-4">

        <div>
            <label for="start_date"
                   class="mb-2 block text-sm font-semibold text-slate-700">
                Dari Tanggal
            </label>
            <input
                type="date"
                id="start_date"
                name="start_date"
                value="{{ request('start_date') }}"
                class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm"
            >
        </div>

        <div>
            <label for="end_date"
                   class="mb-2 block text-sm font-semibold text-slate-700">
                Sampai Tanggal
            </label>
            <input
                type="date"
                id="end_date"
                name="end_date"
                value="{{ request('end_date') }}"
                class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm"
            >
        </div>

        <div>
            <label for="status"
                   class="mb-2 block text-sm font-semibold text-slate-700">
                Status
            </label>
            <select
                id="status"
                name="status"
                class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm"
            >
                <option value="">Semua Status</option>

                @foreach([
                    'pending',
                    'approved',
                    'borrowed',
                    'return_requested',
                    'returned',
                    'rejected'
                ] as $status)
                    <option
                        value="{{ $status }}"
                        @selected(request('status') === $status)
                    >
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button
                type="submit"
                class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700"
            >
                Terapkan Filter
            </button>
        </div>
    </form>

    {{-- Tombol ekspor --}}
    <div class="mt-5 flex flex-wrap gap-3 border-t border-slate-100 pt-5">
        <a
            href="{{ route('admin.reports.excel', request()->query()) }}"
            class="rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700"
        >
            ↓ Export Excel
        </a>

        <a
            href="{{ route('admin.reports.pdf', request()->query()) }}"
            class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"
        >
            ↓ Export PDF
        </a>
    </div>
</div>

{{-- Tabel laporan --}}
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
                    <tr class="hover:bg-slate-50">

                        {{-- ID transaksi --}}
                        <td class="p-4 font-semibold text-slate-800">
                            #{{ $loan->id }}
                        </td>

                        {{-- Nama peminjam --}}
                        <td class="p-4 text-slate-700">
                            {{ $loan->user?->name ?? 'Pengguna tidak ditemukan' }}
                        </td>

                        {{-- Daftar barang --}}
                        <td class="p-4 text-slate-700">
                            @forelse($loan->items as $item)
                                <div class="mb-1">
                                    {{ $item->name ?? 'Barang tidak ditemukan' }}

                                    <span class="text-slate-500">
                                        ({{ $item->pivot->quantity ?? 0 }} unit)
                                    </span>
                                </div>
                            @empty
                                <span class="text-slate-400">
                                    Belum ada barang
                                </span>
                            @endforelse
                        </td>

                        {{-- Tanggal transaksi --}}
                        <td class="p-4 text-slate-600">
                            {{ $loan->created_at?->format('d/m/Y') ?? '-' }}
                        </td>

                        {{-- Status transaksi --}}
                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'approved' => 'bg-blue-100 text-blue-700',
                                    'borrowed' => 'bg-indigo-100 text-indigo-700',
                                    'return_requested' => 'bg-orange-100 text-orange-700',
                                    'returned' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ];

                                $statusClass = $statusColors[$loan->status]
                                    ?? 'bg-slate-100 text-slate-700';
                            @endphp

                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $loan->status ?? 'unknown')) }}
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="p-10 text-center text-slate-500">
                            Tidak ada data pada filter tersebut.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="border-t border-slate-100 p-4">
        {{ $loans->links() }}
    </div>
</div>
@endsection
