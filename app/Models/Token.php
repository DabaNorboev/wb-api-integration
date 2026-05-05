<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
    protected $table = 'tokens';

    protected $guarded = false;

    public function account(): belongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function apiService(): belongsTo
    {
        return $this->belongsTo(ApiService::class);
    }

    public function tokenType(): belongsTo
    {
        return $this->belongsTo(TokenType::class);
    }
}
