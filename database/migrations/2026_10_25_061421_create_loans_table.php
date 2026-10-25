<<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // Nomor unik transaksi peminjaman
            $table->string('loan_code')->unique();

            // Pegawai yang mengajukan peminjaman
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Alasan atau keperluan peminjaman
            $table->text('reason');

            // Status transaksi peminjaman
            $table->string('status')->default('menunggu');

            // Token rahasia untuk QR peminjaman
            $table->string('qr_token')->nullable()->unique();

            // Waktu persetujuan Admin
            $table->timestamp('approved_at')->nullable();

            // Waktu barang diserahkan kepada pegawai
            $table->timestamp('borrowed_at')->nullable();

            // Waktu pengembalian disetujui
            $table->timestamp('returned_at')->nullable();

            // Admin/operator yang menyetujui peminjaman
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};