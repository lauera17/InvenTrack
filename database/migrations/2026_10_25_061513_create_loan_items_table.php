<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_items', function (Blueprint $table) {
            $table->id();

            // Relasi ke transaksi peminjaman
            $table->foreignId('loan_id')
                ->constrained('loans')
                ->cascadeOnDelete();

            // Barang yang dipinjam
            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            // Jumlah barang yang dipinjam
            $table->unsignedInteger('quantity');

            $table->timestamps();

            // Mencegah barang yang sama dibuat dua baris
            // dalam satu transaksi
            $table->unique(['loan_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_items');
    }
};