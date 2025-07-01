<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'group_id',
        'chat_id',
        'chat_name',
        'phone',
        'name',
        'is_one_day',
        'active',
        'schedule',
        'inn',
    ];

    public function getActiveCardAttribute()
    {
        return $this->cards()->where('active', true)->first();
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function outgoingPayments()
    {
        return $this->hasMany(OutgoingPayment::class);
    }

    public function incomingPayments()
    {
        return $this->hasMany(IncomingPayment::class);
    }
}
