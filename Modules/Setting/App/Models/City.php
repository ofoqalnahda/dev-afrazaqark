<?php

namespace Modules\Setting\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class City extends Model implements TranslatableContract , HasMedia
{
    use HasFactory, Translatable, InteractsWithMedia;
    public $translatedAttributes = ['title'];
    protected $guarded = [];
    public function scopeActive($query)
    {
        return $query->where('status',  1);
    }
    public function area(){
        return $this->belongsTo(Area::class);
    }
}
