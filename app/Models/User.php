<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasAvatar;
use App\Enums\Role;
use App\Casts\RoleCast;

class User extends Authenticatable implements HasAvatar
{
    use HasFactory, Notifiable;

    public function getFilamentAvatarUrl(): ?string
    {

        if (empty($this->avatar_url)) {
            return $this->avatar_url;
        }

        return "/storage/".$this->avatar_url;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_url',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => RoleCast::class
    ];

   

    public function scopeWithOutAdmin($query)
    {
        return $query->where('email', '!=', 'admin@autoportal.store');
    }
}
