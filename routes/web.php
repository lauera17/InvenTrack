<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Pegawai\LoanController as PegawaiLoanController;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| ROUTE PENGGUNA YANG SUDAH LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard Admin dan Pegawai
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // CRUD Data Barang
            Route::resource('items', ItemController::class)
                ->except(['show']);

            // Daftar Peminjaman
            Route::get('/loans', [AdminLoanController::class, 'index'])
                ->name('loans.index');

            // Daftar Pengembalian Barang
            // Sementara menggunakan halaman daftar peminjaman
            Route::get('/returns', [AdminLoanController::class, 'index'])
                ->name('returns.index');

            // Halaman Pemindaian QR
            Route::get('/loans/scan', [AdminLoanController::class, 'scanForm'])
                ->name('loans.scan');

            // Konfirmasi Penyerahan Barang melalui QR
            Route::post('/loans/handover', [AdminLoanController::class, 'handover'])
                ->name('loans.handover');

            // Detail Peminjaman
            Route::get('/loans/{loan}', [AdminLoanController::class, 'show'])
                ->name('loans.show');

            // Setujui Peminjaman
            Route::post('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])
                ->name('loans.approve');

            // Tolak Peminjaman
            Route::post('/loans/{loan}/reject', [AdminLoanController::class, 'reject'])
                ->name('loans.reject');

            // Setujui Pengembalian
            Route::post(
                '/loans/{loan}/approve-return',
                [AdminLoanController::class, 'approveReturn']
            )->name('loans.approve-return');

            /*
            |--------------------------------------------------------------------------
            | LAPORAN PEMINJAMAN
            |--------------------------------------------------------------------------
            */

            // Halaman laporan
            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');

            // Export laporan Excel
            Route::get('/reports/excel', [ReportController::class, 'excel'])
                ->name('reports.excel');

            // Export laporan PDF
            Route::get('/reports/pdf', [ReportController::class, 'pdf'])
                ->name('reports.pdf');
        });

    /*
    |--------------------------------------------------------------------------
    | PEGAWAI / PEMINJAM
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:pegawai'])
        ->prefix('pegawai')
        ->name('pegawai.')
        ->group(function () {

            // Lihat Stok Barang
            Route::get('/items', function () {
                $items = Item::with('category')
                    ->orderBy('name')
                    ->paginate(12);

                return view('pegawai.items.index', compact('items'));
            })->name('items.index');

            // Riwayat Peminjaman
            Route::get('/loans', [PegawaiLoanController::class, 'index'])
                ->name('loans.index');

            // Form Pengajuan Peminjaman
            Route::get('/loans/create', [PegawaiLoanController::class, 'create'])
                ->name('loans.create');

            // Simpan Pengajuan Peminjaman
            Route::post('/loans', [PegawaiLoanController::class, 'store'])
                ->name('loans.store');

            // Detail Peminjaman Pegawai
            Route::get('/loans/{loan}', [PegawaiLoanController::class, 'show'])
                ->name('loans.show');

            // Form Pengembalian
            Route::get('/loans/{loan}/return', [PegawaiLoanController::class, 'returnForm'])
                ->name('loans.return.form');

            // Kirim Pengajuan Pengembalian
            Route::post('/loans/{loan}/return', [PegawaiLoanController::class, 'submitReturn'])
                ->name('loans.return.submit');
        });
});

/*
|--------------------------------------------------------------------------
| AUTENTIKASI LARAVEL BREEZE
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';