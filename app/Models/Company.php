<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $table = 'companies';

    protected $guarded = false;

    public function accounts(): hasMany
    {
        return $this->hasMany(Account::class);
    }
}
