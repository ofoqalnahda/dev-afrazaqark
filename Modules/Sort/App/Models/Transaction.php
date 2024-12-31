<?php

namespace Modules\Sort\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\App\Models\Admin;
use Modules\Auth\App\Models\User;
use Modules\Setting\App\Models\Area;
use Modules\Setting\App\Models\City;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Transaction extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded=[];
    protected $appends=['first_img'];

    public function getFirstImgAttribute(): string
    {
        return $this->getFirstMediaUrl('building_facade_image')?:'';
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class,'updated_by','id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function property_type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class,'property_type_id','id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class,'city_id','id');
    }
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function operation_type(): BelongsTo
    {
        return $this->belongsTo(OperationType::class,'operation_type_id','id');
    }
    public function transaction_status(): BelongsTo
    {
        return $this->belongsTo(TransactionStatus::class,'status_id','id');
    }
    public function transaction_sub_status(): BelongsTo
    {
        return $this->belongsTo(TransactionStatus::class,'sub_status_id','id');
    }
    public function cancellation_reason(): BelongsTo
    {
        return $this->belongsTo( CancellationReason::class,'reason_id','id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(TransactionPayment::class);

    }

    public function cancelBy(): BelongsTo
    {
        if ($this->cancel_by == 'Admin'){
            return $this->belongsTo( Admin::class,'cancel_by_id','id');
        }

        return $this->belongsTo( User::class,'cancel_by_id','id');


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
