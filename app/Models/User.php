<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    const ROLE_ADMIN = 'admin';
    const ROLE_DRIVER = 'driver';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_CASHIER = 'cashier';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'address_coordinates',
        'email',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getUserRoles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_CASHIER,
            self::ROLE_CUSTOMER,
            self::ROLE_DRIVER
        ];
    }

}
