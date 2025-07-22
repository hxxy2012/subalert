<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'nickname',
        'role',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function canManageUsers()
    {
        return in_array($this->role, ['super_admin', 'user_admin']);
    }

    public function canManageSystem()
    {
        return in_array($this->role, ['super_admin', 'system_admin']);
    }

    public function canViewStatistics()
    {
        return in_array($this->role, ['super_admin', 'data_analyst']);
    }
}