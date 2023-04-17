<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_NORMAL = 2;
    const ROLE_ADMIN = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }

    public function books() {
        return $this->hasMany(Book::class);
    }

    public function isAdmin() {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isNormalUser() {
        return $this->role === self::ROLE_NORMAL;
    }
    public function hasPermissionToCreateBook()
    {
        return $this->isAdmin() || $this->isNormalUser();
    }

    public function hasPermissionToViewAnyBooks()
    {
        return $this->isAdmin() || $this->isNormalUser();
    }

    public function hasPermissionToViewBook()
    {
        return $this->isAdmin() || $this->isNormalUser();
    }
}
