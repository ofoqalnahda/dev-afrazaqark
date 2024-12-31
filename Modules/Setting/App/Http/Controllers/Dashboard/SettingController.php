<?php

namespace Modules\Setting\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Modules\Admin\App\Models\Admin;
use Modules\Admin\Services\AdminService;


class SettingController extends Controller
{

    public function __construct()
    {
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Admin::class );
        $this->middleware('auth.gard:admin');
    }



    public  function index(){

        return self::getData();
    }

    public  function update(Request $request){
        $validator = validator($request->all(), [
            'address' => 'nullable|string|max:150',
            'phone' => 'nullable|numeric|digits:10|starts_with:05',
            'whatsapp' => 'nullable|numeric|digits:10|starts_with:05',
            'email' => 'nullable|email',
            'second_email' => 'nullable|email',
            'link_map' => 'nullable|url',
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        foreach ($request->all() as $key => $value) {
            if($key == 'logo'){
                $logo = $request->file('logo');
                $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('assets/images/settings'), $logoName);
                $value =  'settings/'.$logoName;
            }
            \Settings::set($key,$value);
        }
        return self::getData();
    }
    static function getData()
    {

        $data['logo']=asset('assets/images/'.\Settings::get('logo','settings/logo.png'));
        $data['address']=\Settings::get('address_'.app()->getLocale(),'المنطقه 5 الرياض  السعوديه');
        $data['phone']=\Settings::get('phone','+96617228997');
        $data['second_phone']=\Settings::get('second_phone','+96617228998');
        $data['whatsapp']=\Settings::get('whatsapp','+96617228998');
        $data['email']=\Settings::get('email','ttb_111@hotmail.com');
        $data['second_email']=\Settings::get('second_email','ttb_111@hotmail.com');
        $data['link_map']=\Settings::get('link_map');
        return responseApi(200,translate('return_data_success'),  $data);

    }

}
