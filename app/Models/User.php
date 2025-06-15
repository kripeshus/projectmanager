<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'user';
    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token']; // hides password from JSON responses

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }
}
