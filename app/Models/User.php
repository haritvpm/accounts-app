<?php

namespace App\Models;

use Hash;
use Illuminate\Auth\Notifications\ResetPassword;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
  //  use HasApiTokens;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
       
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'ddo',
        
    ];

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}