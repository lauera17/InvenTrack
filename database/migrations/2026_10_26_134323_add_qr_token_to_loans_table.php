<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom hanya jika belum tersedia.
        if (!Schema::hasColumn('loans', 'qr_token')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->string('qr_token', 64)->nullable();
            });
        }

        // Tambahkan indeks unik hanya jika belum tersedia.
        $indexes = collect(
            \Illuminate\Support\Facades\DB::select(
                "SHOW INDEX FROM loans"
            )
        );

        $hasUniqueIndex = $indexes->contains(function ($index) {
            return $index->Column_name === 'qr_token'
                && (int) $index->Non_unique === 0;
        });

        if (!$hasUniqueIndex) {
            Schema::table('loans', function (Blueprint $table) {
                $table->unique('qr_token');
            });
        }
    }

    public function down(): void
    {
        // Jangan hapus kolom qr_token yang mungkin sudah
        // digunakan oleh bagian lain aplikasi.
        // Migration ini hanya memastikan kolom dan indeks tersedia.
    }
};