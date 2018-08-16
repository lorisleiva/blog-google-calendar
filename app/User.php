<?php

namespace App;

use App\GoogleAccount;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function googleAccounts()
    {
        return $this->hasMany(GoogleAccount::class);
    }
}
