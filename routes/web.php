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

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN DAN PEGAWAI
    |--------------------------------------------------------------------------
    */

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

            /*
            |--------------------------------------------------------------------------
            | CRUD DATA BARANG
            |--------------------------------------------------------------------------
            |
            | Route show diaktifkan agar tombol Detail bisa digunakan.
            | ItemController harus memiliki method show().
            |
            */

            Route::resource('items', ItemController::class);

            /*
            |--------------------------------------------------------------------------
            | PEMINJAMAN
            |--------------------------------------------------------------------------
            */

            Route::get('/loans', [AdminLoanController::class, 'index'])
                ->name('loans.index');

            Route::get('/returns', [AdminLoanController::class, 'index'])
                ->name('returns.index');

            // Halaman pemindaian QR
            Route::get('/loans/scan', [AdminLoanController::class, 'scanForm'])
                ->name('loans.scan');

            // Konfirmasi penyerahan barang melalui QR
            Route::post('/loans/handover', [AdminLoanController::class, 'handover'])
                ->name('loans.handover');

            // Setujui peminjaman
            Route::post('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])
                ->name('loans.approve');

            // Tolak peminjaman
            Route::post('/loans/{loan}/reject', [AdminLoanController::class, 'reject'])
                ->name('loans.reject');

            // Setujui pengembalian
            Route::post(
                '/loans/{loan}/approve-return',
                [AdminLoanController::class, 'approveReturn']
            )->name('loans.approve-return');

            // Detail peminjaman
            Route::get('/loans/{loan}', [AdminLoanController::class, 'show'])
                ->name('loans.show');

            /*
            |--------------------------------------------------------------------------
            | LAPORAN PEMINJAMAN
            |--------------------------------------------------------------------------
            */

            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');

            Route::get('/reports/excel', [ReportController::class, 'excel'])
                ->name('reports.excel');

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

            // Lihat stok barang
            Route::get('/items', function () {
                $items = Item::with('category')
                    ->orderBy('name')
                    ->paginate(12);

                return view('pegawai.items.index', compact('items'));
            })->name('items.index');

            // Riwayat peminjaman
            Route::get('/loans', [PegawaiLoanController::class, 'index'])
                ->name('loans.index');

            // Form pengajuan peminjaman
            Route::get('/loans/create', [PegawaiLoanController::class, 'create'])
                ->name('loans.create');

            // Simpan pengajuan peminjaman
            Route::post('/loans', [PegawaiLoanController::class, 'store'])
                ->name('loans.store');

            // Form pengembalian
            Route::get(
                '/loans/{loan}/return',
                [PegawaiLoanController::class, 'returnForm']
            )->name('loans.return.form');

            // Kirim pengajuan pengembalian
            Route::post(
                '/loans/{loan}/return',
                [PegawaiLoanController::class, 'submitReturn']
            )->name('loans.return.submit');

            // Detail peminjaman pegawai
            Route::get('/loans/{loan}', [PegawaiLoanController::class, 'show'])
                ->name('loans.show');
        });
});

/*
|--------------------------------------------------------------------------
| AUTENTIKASI LARAVEL BREEZE
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';