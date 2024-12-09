<?php

namespace Modules\Info\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model implements TranslatableContract
{
    use HasFactory,  Translatable;

    public array $translatedAttributes = ['title','description'];
    protected $guarded = [];

}
