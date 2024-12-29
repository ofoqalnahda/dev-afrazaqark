<?php

namespace Modules\Home\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Offer extends Model implements  HasMedia
{
    use HasFactory,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded= [];
    protected $appends = ['image_url'];

    public function scopeActive($query)
    {
        return $query->where('status',  1);

    }
    public function getImageUrlAttribute(): ?string
    {
        return   $this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : asset('assets/images/default/offer_'.$this->id.'.png');
    }
}
