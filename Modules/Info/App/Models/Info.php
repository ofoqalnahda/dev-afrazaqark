<?php

namespace Modules\Info\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Info extends Model implements TranslatableContract
{
    use HasFactory, \Astrotomic\Translatable\Translatable;

    public array $translatedAttributes = ['title','description'];
    protected $guarded = [];
}
