<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OutgoingPayment extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'agent_id',
        'chat_id',
        'group_id',
        'card_user_name',
        'bank',
        'card',
        'fee',
        'incoming_sum',
        'status',
        'recipient_name',
        'recipient_bank',
        'recipient_card',
        'recipient_iban',
        'sum',
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
