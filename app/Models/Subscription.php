<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'price',
        'cycle',
        'expire_at',
        'auto_renew',
        'status',
        'icon',
        'note',
        'account_info',
    ];

    protected $casts = [
        'expire_at' => 'date',
        'auto_renew' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function isExpiring($days = 7)
    {
        return $this->expire_at->isBetween(now(), now()->addDays($days));
    }

    public function isExpired()
    {
        return $this->expire_at->isPast();
    }

    public function getDaysUntilExpiry()
    {
        return now()->diffInDays($this->expire_at, false);
    }

    public function getTypeDisplayAttribute()
    {
        $types = [
            'video' => '视频',
            'music' => '音乐',
            'software' => '软件',
            'communication' => '通讯',
            'other' => '其他',
        ];

        return $types[$this->type] ?? '其他';
    }

    public function getCycleDisplayAttribute()
    {
        $cycles = [
            'monthly' => '月付',
            'quarterly' => '季付',
            'yearly' => '年付',
            'custom' => '自定义',
        ];

        return $cycles[$this->cycle] ?? '自定义';
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'active' => '正常',
            'paused' => '暂停',
            'cancelled' => '已取消',
            'expired' => '已过期',
        ];

        return $statuses[$this->status] ?? '未知';
    }
}