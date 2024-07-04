<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technic extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password'
    ];

    protected $hidden = ['password', 'created_at', 'updated_at'];


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'technic_id');
    }
}
