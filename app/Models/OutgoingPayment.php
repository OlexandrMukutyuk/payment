<?php

namespace App\Models;

use App\Http\Resources\Api\OutgoingPaymentResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Http;

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

    protected static function booted(): void
    {
        static::created(function (self $payment) {
            Http::post(env('BOT_URL') . '/api/payment/outgoing', [
                'outgoingPayment' => OutgoingPaymentResource::make($payment),
                'result' => true,
                'group_ids' => Agent::active()->whereHas('cards', function ($query) use ($payment) {
                    $query->where('active', true)
                          ->where('limit', '>', $payment->sum);
                })->pluck('group_id')->unique()->values()->toArray(),
            ]);
        });
        static::saved(function (self $payment) {
            if (
                $payment->status === 'new' &&
                $payment->wasChanged('status')
            ) {
                Http::post(env('BOT_URL') . '/api/payment/outgoing', [
                    'outgoingPayment' => OutgoingPaymentResource::make($payment),
                    'result' => true,
                    'group_ids' => Agent::active()->whereHas('cards', function ($query) use ($payment) {
                        $query->where('active', true)
                              ->where('limit', '>', $payment->sum);
                    })->pluck('group_id')->unique()->values()->toArray(),
                ]);
            }

            if (
                $payment->status === 'success' || $payment->status === 'failed' &&
                $payment->wasChanged('status')
            ){
                Http::post(env('BOT_URL') . '/api/payment/outgoing/feedback', [
                    'outgoingPayment' => OutgoingPaymentResource::make($payment),
                    'result' => true,
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
