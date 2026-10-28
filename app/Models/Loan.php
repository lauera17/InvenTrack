<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_code',
        'user_id',
        'purpose',
        'reason',
        'status',
        'qr_token',
        'return_note',
        'return_photo',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Pegawai yang mengajukan peminjaman.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Barang-barang dalam transaksi peminjaman.
     * Jumlah barang tersedia melalui $item->pivot->quantity.
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'loan_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Detail setiap baris barang pada transaksi.
     * Relasi item() berada di model LoanItem, bukan model Item.
     */
    public function loanItems(): HasMany
    {
        return $this->hasMany(LoanItem::class);
    }

    /**
     * Dokumen atau bukti pengembalian.
     */
    public function returnDocuments(): HasMany
    {
        return $this->hasMany(ReturnDocument::class);
    }
}