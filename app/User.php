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
        'name', 'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get user's role
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Check if User has 'admin' role
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role->name == 'admin';
    }

    /**
     * Check if User has 'cook' role
     *
     * @return boolean
     */
    public function isCook()
    {
        return $this->role->name == 'cook';
    }

    /**
     * Check if User has 'waiter' role
     *
     * @return boolean
     */
    public function isWatier()
    {
        return $this->role->name == 'waiter';
    }
}
