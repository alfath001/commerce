<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'tglLahir', 'noHP', 'foto',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
    ];

    public function is_admin(){
        if ($this->admin) {
            return true;
        }
        return false;
    }

    public function iklan(){
        return $this->hasMany(Iklan::class);
    }

    public function komen(){
        return $this->hasMany(Komen::class);
    }
}
