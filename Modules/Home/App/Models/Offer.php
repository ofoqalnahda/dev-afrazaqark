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
        $now = date('Y-m-d');
        return $query->where('status',  1)->where(function ($q) use ($now){
            $q->where(function ($q2) use ($now){
                $q2->whereDate('start_at','<=',$now )
                    ->whereDate('end_at','>',$now );
            })->orwhereNull('end_at');
        });

    }
    public function getImageUrlAttribute(): ?string
    {
        return   $this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : asset('assets/images/default/offer_'.$this->id.'.png');
    }
}
