<?php

namespace Modules\Notification\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Notification\App\Http\resources\NotificationResource;
use Modules\Notification\App\Models\Notification;
use Modules\Sort\Services\TransactionService;

class NotificationController extends Controller
{
    protected $count_paginate = 10;
    public function __construct( )
    {
        $this->middleware('auth.gard:api');
    }
    public function index(Request $request)
    {
        $notifications=  Notification::where('user_id',auth('api')->id());
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        if($count_paginate == 'ALL'){
            $notifications=  $notifications->get();
        }else{
            $notifications=  $notifications->simplePaginate($count_paginate);
        }
        Notification::whereIn('id',$notifications->pluck('id')->toArray())
            ->Unread()
            ->update(['read'=>true]);
        return responseApi('200','',NotificationResource::collection($notifications));
    }

    public function count()
    {

        $notificationsCount = auth()->user()->notifications()
            ->Unread()
            ->count();
        return  responseApi(200, translate('return_data_success'),$notificationsCount);


    }


}
