<?php

namespace Modules\Sort\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TransactionPayment extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded=[];
    protected $appends=['first_img'];

    public function getFirstImgAttribute()
    {
        $return=  $this->getFirstMediaUrl('images')?:'';

        return  $return;
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo( Transaction::class,'transaction_id','id');
    }

}
