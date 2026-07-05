<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Representa una transacción financiera */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'description', 'type', 'amount',
        'balance', 'transaction_date', 'category', 'reference',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    /** Usuario propietario de la transacción */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
