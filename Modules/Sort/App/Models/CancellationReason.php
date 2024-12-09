<?php

namespace Modules\Sort\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationReason extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    public $translatedAttributes = ['title'];
    public $translationForeignKey = 'reason_id';

    protected $guarded=[];

    public function scopeActive($query)
    {
        return $query->where('status',  1);
    }

}
