<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanItem extends Model
{
    use HasFactory;

    protected $table = 'loan_items';

    protected $fillable = [
        'loan_id',
        'item_id',
        'quantity',
    ];

    /**
     * Detail barang ini milik satu transaksi peminjaman.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Detail ini merujuk pada satu barang.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}