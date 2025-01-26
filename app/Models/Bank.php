<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Bank extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'active',
    ];

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function registerMediaConversions(?Media $media = null): void
{
    $this
        ->addMediaConversion('preview')
        ->fit(Fit::Contain, 300, 300)
        ->nonQueued();
}
}
