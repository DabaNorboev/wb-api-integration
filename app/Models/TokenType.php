<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TokenType extends Model
{
    protected $table = 'token_types';

    protected $guarded = false;

    public function apiService(): belongsTo
    {
        return $this->belongsTo(ApiService::class);
    }

    public function tokens(): hasMany
    {
        return $this->hasMany(Token::class);
    }
}
