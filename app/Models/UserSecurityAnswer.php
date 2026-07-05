<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Representa la respuesta de un usuario a una pregunta de seguridad */
class UserSecurityAnswer extends Model
{
    protected $fillable = ['user_id', 'security_question_id', 'answer'];

    /** Usuario al que pertenece la respuesta */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Pregunta de seguridad asociada */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SecurityQuestion::class, 'security_question_id');
    }
}
