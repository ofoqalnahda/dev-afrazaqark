<?php

namespace Modules\Sort\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TransactionStatus extends Model implements TranslatableContract ,HasMedia
{
    use HasFactory, Translatable,InteractsWithMedia;
    public $translatedAttributes = ['title'];
    public $table = 'transaction_statuses';
    public $translationForeignKey = 'status_id';
    protected $guarded=[];


    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'status_id','id');
    }
}
