<?php

namespace Modules\Sort\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Bank extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded=[];
    protected $appends=['first_icon'];

    public function scopeActive($query)
    {
        return $query->where('status',  1);
    }

    public function getFirstIconAttribute()
    {
        $return=  $this->getFirstMediaUrl('icon');

        return  $return;
    }

}
