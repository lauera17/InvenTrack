@extends('layouts.inventrack')

@section('title', 'Data Peminjaman')
@section('heading', 'Data Peminjaman')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-slate-900">Data Peminjaman</h2>
    <p class="mt-1 text-sm text-slate-500">Pantau pengajuan, penyerahan, dan pengembalian barang.</p>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <form method="GET" class="grid gap-3 border-b border-slate-100 p-4 sm:grid-cols-3">
        <input name="search" value="{{ request('search') }}"
               placeholder="Cari nama pegawai..."
               class="rounded-xl border border-slate-200 px-4 py-3 text-sm">

        <select name="status" class="rounded-xl border border-slate-200 px-4 py-3 text-sm">
            <option value="">Semua status</option>
            @foreach(['pending','approved','borrowed','return_requested','returned','rejected'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>

        <button class="rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">
            Filter Data
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-5 py-4">Peminjam</th>
                    <th class="px-5 py-4">Barang</th>
                    <th class="px-5 py-4">Tanggal</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <p class="font-semibold">{{ $loan->user->name }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $loan->user->email }}</p>
                        </td>
                        <td class="max-w-64 px-5 py-4">
                            {{ $loan->items->map(fn($line) => $line->item->name.' ('.$line->quantity.')')->join(', ') }}
                        </td>
                        <td class="px-5 py-4 text-slate-500">{{ $loan->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.loans.show', $loan) }}"
                               class="rounded-lg bg-blue-50 px-3 py-2 font-semibold text-blue-700">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                            Belum ada pengajuan peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $loans->links() }}</div>
</div>
@endsection