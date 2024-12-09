<?php

namespace Modules\Sort\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatusTranslation extends Model
{
    use HasFactory;
    public $timestamps=false;
    public $table="transaction_status_translations";
    protected $guarded=[];
}
