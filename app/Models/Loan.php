<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'approved_by',
        'qr_token',
        'purpose',
        'status',
        'handed_over_at',
        'returned_at',
        'return_note',
        'return_photo',
        'return_approved_by',
    ];

    protected function casts(): array
    {
        return [
            'handed_over_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returnApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'return_approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LoanItem::class);
    }
}