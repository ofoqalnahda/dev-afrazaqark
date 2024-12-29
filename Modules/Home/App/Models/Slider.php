<?php

namespace Modules\Home\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slider extends Model implements  HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded= [];
    protected $appends = ['image_url'];
    public function scopeActive($query)
    {
        $now = date('Y-m-d');
        return $query->where('status',  1)->where(function ($q) use ($now){
            $q->where(function ($q2) use ($now){
                $q2->whereDate('start_at','<=',$now )
                    ->whereDate('start_at','!=',null );
            })->where(function ($q3) use ($now){
                $q3->whereDate('end_at','>',$now )->orwhereNull('end_at');
            });
        });

    }
    public function getIsActiveAttribute(): ?string
    {
        $now = date('Y-m-d');
         if($this->status == 1 &&  ($this->start_at <= $now && $this->end_at != null)&& ($this->end_at > $now || $this->end_at == null)){
            return true;
        }
        return FALSE;
    }

    public function getImageUrlAttribute(): ?string
    {
     return   $this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : asset('assets/images/default/slider_'.$this->id.'.png');
    }

}
