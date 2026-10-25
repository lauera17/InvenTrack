<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_documents', function (Blueprint $table) {
            $table->id();

            // Transaksi yang sedang dikembalikan
            $table->foreignId('loan_id')
                ->constrained('loans')
                ->cascadeOnDelete();

            // Lokasi penyimpanan foto kondisi barang
            $table->string('photo_path')->nullable();

            // Keterangan kondisi barang saat dikembalikan
            $table->text('description')->nullable();

            // Admin/operator yang memeriksa pengembalian
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Waktu pemeriksaan pengembalian
            $table->timestamp('reviewed_at')->nullable();

            // Status dokumentasi pengembalian
            $table->string('status')->default('menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_documents');
    }
};