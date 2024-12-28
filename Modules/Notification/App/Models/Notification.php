<?php

namespace Modules\Notification\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sort\App\Models\Transaction;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Notification extends Model implements TranslatableContract , HasMedia
{
    use HasFactory, Translatable, InteractsWithMedia;
    public $translatedAttributes = ['title','body'];
    protected $guarded = [];
    protected $appends=['image'];
    public function scopeUnread($query)
    {
        return $query->where('read',  false);
    }

    public function type_data ()
    {
        //'Transaction','Notification','Discount'

        if ($this->type == 'Transaction'){
            return $this->belongsTo(Transaction::class,'type_id');
        }
        return $this->morphTo(__FUNCTION__);

    }

    public function getImageAttribute (){
        return   $this->getFirstMediaUrl('image')?:asset('assets/images/notify'.$this->id.'.png');
    }


}
