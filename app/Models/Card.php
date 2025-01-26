<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Card extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'bank_id',
        'agent_id',
        'limit',
        'status',
        'iban',
        'date_end',
        'number',
        'active',
    ];

    public static function getStatuses()
    {
        return [
            'fop' => __('fop'),
            'fiz' => __('fiz'),
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
