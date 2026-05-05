<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiService extends Model
{
    protected $table = 'api_services';
    protected $guarded = false;

    public function tokenTypes(): hasMany
    {
        return $this->hasMany(TokenType::class);
    }

    public function tokens(): hasMany
    {
        return $this->hasMany(Token::class);
    }
}
