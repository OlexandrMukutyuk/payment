<?php

namespace App\Models;

use App\Http\Resources\Api\IncomingPaymentResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

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

    protected static function booted(): void
    {
        static::created(function (self $payment) {
            Http::post(env('BOT_URL') . '/api/payment/incoming', [
                'incomingPayment' => IncomingPaymentResource::make($payment),
                // 'result' => true,
                // 'group_ids' => Agent::active()->whereHas('cards', function ($query) {
                //     $query->where('active', true)
                //           ->whereColumn('limit', '>', 'amount');
                // })->pluck('group_id')->unique()->values()->toArray(),
            ]);
        });
        static::saved(function (self $payment) {
            if (
                $payment->status === 'new' &&
                $payment->wasChanged('status')
            ) {
                Http::post(env('BOT_URL') . '/api/payment/incoming', [
                    'incomingPayment' => IncomingPaymentResource::make($payment),
                    // 'result' => true,
                    // 'group_ids' => Agent::active()->whereHas('cards', function ($query) {
                    //     $query->where('active', true);
                    // })->pluck('group_id')->unique()->values()->toArray(),
                ]);
            }

            if (
                $payment->status === 'success' || $payment->status === 'failed' &&
                $payment->wasChanged('status')
            ){
                Http::post(env('BOT_URL') . '/api/payment/incoming/feedback', [
                    'incomingPayment' => IncomingPaymentResource::make($payment),
                    // 'result' => true,
                ]);
            }

        });
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
