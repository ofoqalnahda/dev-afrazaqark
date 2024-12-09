<?php

namespace Modules\Sort\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Transaction extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded=[];
    protected $appends=['first_img'];

    public function getFirstImgAttribute()
    {
        $return=  $this->getFirstMediaUrl('images')?:'';

        return  $return;
    }

    public function property_type()
    {
        return $this->belongsTo(PropertyType::class,'property_type_id','id');
    }
    public function transaction_status()
    {
        return $this->belongsTo(TransactionStatus::class,'status_id','id');
    }
    public function transaction_sub_status()
    {
        return $this->belongsTo(TransactionStatus::class,'sub_status_id','id');
    }
    public function cancellation_reason()
    {
        return $this->belongsTo( CancellationReason::class,'reason_id','id');
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionPayment::class);

    }



    public function scopeFilterAds($query, array $filters)
    {
        $keys = ['property_type_id', 'invoice_number','status_id', 'search', 'city_id', 'area_d'];

        $filters = array_filter($filters);
        foreach ($filters as $filterKey => $filterValue) {
            if (!in_array($filterKey, $keys) || !$filterValue) {
                continue;
            }

            if ($filterKey == 'search') {
                $query->where(function ($query2) use ($filterValue) {
                    $query2->where('invoice_number', 'like', '%' . $filterValue . '%')
                            ->Orwherehas('property_type', function ($query3) use ($filterValue) {
                                $query3->whereTranslation('title', 'like', '%' . $filterValue . '%');
                            });
                });
            }
            else {
                $query->where($filterKey, $filterValue);
            }
        }

        return $query;
    }

}
