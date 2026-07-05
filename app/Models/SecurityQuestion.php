<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Representa una pregunta de seguridad */
class SecurityQuestion extends Model
{
    protected $fillable = ['question'];

    /** Respuestas de los usuarios a esta pregunta */
    public function answers(): HasMany
    {
        return $this->hasMany(UserSecurityAnswer::class);
    }
}
