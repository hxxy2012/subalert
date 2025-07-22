<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'nickname',
        'avatar',
        'phone',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function getActiveSubscriptionsCount()
    {
        return $this->subscriptions()->where('status', 'active')->count();
    }

    public function getTotalMonthlyExpense()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('cycle', 'monthly')
            ->sum('price');
    }

    public function getExpiringSubscriptions($days = 7)
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->whereBetween('expire_at', [now(), now()->addDays($days)])
            ->get();
    }
}