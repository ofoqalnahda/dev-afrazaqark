<?php

namespace Modules\Admin\App\Http\Controllers\Apis; ;



use App\Http\Controllers\ApiController;
use File;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Admin\App\Models\Admin;
use Modules\Auth\Util\AuthUtil;
use Modules\Admin\App\Http\resources\NotificationResource;
use Illuminate\Support\Facades\Config;

class AuthController extends ApiController
{
//    protected $authUtil;

    private AuthUtil $authUtil;

    public function __construct(AuthUtil $authUtil)
    {
        $this->authUtil = $authUtil;
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Admin::class );
        $this->middleware('auth.gard:admin', ['except' => ['login', 'checkPhone', 'SendCode','forgetPassword','checkCode','getAllPermission']]);
    }


    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'phone' => 'required|digits:10|starts_with:05',
            'password' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $admin_delete = Admin::onlyTrashed()->where('phone', $request->phone)->first();
        if ($admin_delete) {
            return responseApiFalse(202, translate('Your account has been deleted. Please contact the administration for further assistance.'));
        }

        $admin = Admin::where('is_active',0)->where('phone', $request->phone)->first();
        if ($admin) {
            return responseApiFalse(202, translate('Your account has inactive. Please contact the administration for further assistance.'));
        }

        $token = auth('admin')
            ->attempt([
                'phone' => $request->phone,
                'password' => $request->password,
            ]);
        if(!$token) {
            return responseApiFalse(202, translate('The login credentials you entered are incorrect. Please try again or reset your password if needed.'));
        }
        return responseApi(200, translate('return success'), $this->createNewToken($token));
    }



    public function logout()
    {
        auth('admin')->logout();
        return responseApi(200, translate('admin logout'));
    }

    public function refresh()
    {
        return responseApi(200, translate('admin login'), $this->createNewToken(auth('admin')->refresh()));
    }


    public function SendCode(Request $request)
    {
        $validator = validator($request->all(), [
            'admin_id' => 'required|integer|exists:admins,id',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $admin = Admin::where('id', $request->admin_id)->first();


        if ($admin) {
            $this->authUtil->SendActivationCode($admin);

            return responseApi(200, translate('return success'), $admin->id);
        }
        return responseApiFalse(405, translate('admin not found'));
    }
    public function checkCode(Request $request)
    {
        $validator = validator($request->all(), [
            'admin_id' => 'required|integer|exists:admins,id',
            'code' => 'required|max:6',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        try {

            $admin = Admin::where('id', $request->admin_id)->first();
            if ($admin->activation_code == $request->code && $admin->activation_code != null) {
                DB::beginTransaction();
                $admin->activation_code = null;
                $admin->save();
                DB::commit();
                $token = auth('admin')->login($admin);
                return responseApi(200, translate('admin login'), $this->createNewToken($token));

            }
            return responseApiFalse(500, translate('activation code is incorrect'));
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function forgetPassword(Request $request)
    {
        $validator = validator($request->all(), [
            'phone' => 'required|digits:10|starts_with:05',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $admin = Admin::where('phone', $request->phone)->first();
        if ($admin) {
            $this->authUtil->SendActivationCode($admin);
            return responseApi(200, translate('return success'), $admin->id);
        }
        return responseApiFalse(405, translate('admin not found'));
    }

    public function resetPassword(Request $request)
    {
        $validator = validator($request->all(), [
            'password' => 'required|confirmed|min:6|max:199',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        auth('admin')->user()->update(['activation_code'=>null,
            'password' =>bcrypt( $request->password)]);
        auth('admin')->user()->save();

        return responseApi(200, translate('Password has been restored'));
    }

    protected function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,//auth('admin')->factory()->getTTL() * 60,
            'admin' => new NotificationResource(auth('admin')->user())
        ];
    }
    public function getAllPermission()
    {
        $data['permissions']= Admin::subModulePermissionArray();
        $data['special']= Admin::specialModulePermissionArray();
        return responseApi(200, '', $data);
    }
    public function getMyPermission()
    {
        $data = auth('admin')->user()->getAllPermissions()->select('id','name');
        return responseApi(200, '', $data);
    }


    public function getProfile(): Application|Response|ResponseFactory
    {
        try {
            $admin = auth('admin')->user();
            return responseApi(200,'', new NotificationResource($admin));
        } catch (\Exception $exception) {
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function update(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'name' => 'nullable|string|between:2,200',
//            'phone' => 'required|digits:10|starts_with:05|unique:admins,phone,'.auth('admin')->user()->id,
            'email' => 'nullable|email|max:20|unique:admins,email,'.auth('admin')->user()->id,
            'image'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();
            $admin = auth('admin')->user();
            $admin->update([
                'name' => $request->name,
//                'phone' => $inputs['phone'],
                'email' => $request->email,
            ]);
            if ($request->hasFile('image')) {
                $admin->clearMediaCollection('images');
                $admin->addMediaFromRequest('image')->toMediaCollection('images');
            }
            DB::commit();
            return responseApi(200, translate('admin updated'), new NotificationResource($admin));
        } catch (\Exception $exception) {

            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function changePassword(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'old_password' => 'required|min:6|max:199',
            'new_password' => 'required|confirmed|min:6|max:199',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        $admin =  auth('api')->user();
        if(\Hash::check($request->old_password,$admin->password)){
            $admin->update([
                'password' =>  bcrypt($request->new_password)
            ]);
            $admin->save();

            return responseApi(200, translate('Password has been changed'));
        }
        return responseApiFalse(405, 'Password is incorrect');

    }


}

