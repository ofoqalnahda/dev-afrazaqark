<?php

namespace Modules\Admin\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject, HasMedia
{
    use HasApiTokens,HasFactory, Notifiable, HasRoles, HasPermissions, HasApiTokens,InteractsWithMedia,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function setPasswordAttribute($value): string
    {
        return $this->attributes['password'] = bcrypt($value);


    }
    public static function modulePermissionArray()
    {
        return [
            'dashboard' => __('permission.dashboard'),
            'transaction' => __('permission.transaction'),
            'clients' => __('permission.clients'),
            'messages' => __('permission.messages'),
            'offers' => __('permission.offers'),
            'settings' => __('permission.settings'),
            'info_module' => __('permission.info_module'),
            'admin_module' => __('permission.admin_module'),
        ];
    }
    public static function subModulePermissionArray()
    {
        return [
            'dashboard' => [
                'details' => __('permission.details'),
            ],
            'transaction' => [
                'transactions' => __('permission.transactions'),
                'route_type' => __('permission.route_type'),
                'property_types' => __('permission.property_types'),
                'operation_type' => __('permission.operation_type'),
                'transaction_status' => __('permission.transaction_status'),
                'cancellation_reason' => __('permission.cancellation_reason'),
            ],
            'clients' => [
                'clients' => __('permission.clients'),
            ],
            'messages' => [
                'contact_us' => __('permission.contact_us'),
            ],
            'offers' => [
                'offers' => __('permission.offers'),
            ],
            'settings' => [
                'sliders' => __('permission.sliders'),
                'icons' => __('permission.icons'),
                'areas' => __('permission.areas'),
                'cities' => __('permission.cities'),
                'general_settings' => __('permission.general_settings'),
            ],
            'info_module' => [
                'infos' => __('permission.infos'),
                'fqa' => __('permission.fqa'),
            ],
            'admin_module' => [
                    'admins' => __('permission.admins'),
                ]
        ];
    }


    public static function specialModulePermissionArray()
    {
        return [
            'dashboard',
            'messages' ,
            'infos' ,

        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


}
