<?php

namespace Modules\Sort\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PropertyType extends Model implements TranslatableContract ,HasMedia
{
    use HasFactory, Translatable,InteractsWithMedia;
    public $translatedAttributes = ['title'];
    protected $guarded=[];
    protected $appends=['first_img'];

    public function scopeActive($query)
    {
        return $query->where('status',  1);
    }

    public function getFirstImgAttribute()
    {
        $return=  $this->getFirstMediaUrl('images');

        return  $return;
    }

}
