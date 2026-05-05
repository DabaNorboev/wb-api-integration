<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $table = 'accounts';

    protected $guarded = false;
    public function company(): belongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

}
