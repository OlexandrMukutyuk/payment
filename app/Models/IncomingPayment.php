<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IncomingPayment extends Model
{
    protected $fillable = [
        'agent_id',
        'chat_id',
        'group_id',
        'sender_name',
        'sender_bank',
        'sender_card',
        'sum',
        'status',
        'recipient_name',
        'recipient_bank',
        'recipient_card',
        'recipient_iban',
        'incoming_sum',
    ];

    public static function getStatuses()
    {
        return [
            'new' => __('new'),
            'in_process' => __('in_process'),
            'success' => __('success'),
            'failed' => __('failed'),
        ];
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function scopeOnlyNew(Builder $query): void
    {
        $query->where('status', 'new');
    }
}
