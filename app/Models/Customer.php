<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'phone_number',
    ];

    // Relasi ke pesanan
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
