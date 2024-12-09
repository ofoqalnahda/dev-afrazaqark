<?php

namespace Modules\Setting\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Icon extends Model implements  HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded = [];
    protected $appends=['first_img'];



    public function getFirstImgAttribute()
    {
        $return=  $this->getFirstMediaUrl('images');

        return  $return;
    }

}
