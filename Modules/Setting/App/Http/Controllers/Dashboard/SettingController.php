<?php

namespace Modules\Setting\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class SettingController extends Controller
{


    public  function index(){

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

    public  function update(Request $request){
        $validator = validator($request->all(), [
            'key' => 'required',
            'value' => 'required',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        \Settings::set($request->key,$request->value);

        return responseApi(200,translate('return_data_success'),  \Settings::get($request->key));
    }

}
