<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'remind_before_days',
        'remind_type',
        'remind_at',
        'status',
        'sent_at',
        'read_at',
        'is_active',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function logs()
    {
        return $this->hasMany(ReminderLog::class);
    }

    public function getRemindTypeArrayAttribute()
    {
        return explode(',', $this->remind_type);
    }

    public function shouldSend()
    {
        return $this->is_active && 
               $this->status === 'pending' && 
               $this->remind_at <= now();
    }
}