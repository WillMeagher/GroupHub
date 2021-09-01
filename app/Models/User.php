<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // returns a request 
    static function find($name) {
        return self::select('users.*')
        ->from('users')
        ->where('users.name', '=', str_replace("_", " ", $name))
        ->first();
    }

    static function findByEmail($email) {
        return self::select('users.*')
        ->from('users')
        ->where('users.email', '=', $email)
        ->first();
    }

    static function allCreated() {
        return self::select('users.*')
        ->from('users')
        ->where('users.account_created', '=', '1')
        ->get();
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
