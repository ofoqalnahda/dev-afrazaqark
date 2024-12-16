<?php

namespace Modules\Auth\App\Http\Controllers\Apis;



use App\Http\Controllers\ApiController;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Auth\App\Models\FcmToken as FcmTokenModel;
use Modules\Auth\App\Models\User;
use Modules\Auth\Util\AuthUtil;
use Modules\Customer\App\resources\CustomerResource;

class AuthController extends ApiController
{
//    protected $authUtil;

    private AuthUtil $authUtil;

    public function __construct(AuthUtil $authUtil)
    {
        $this->authUtil = $authUtil;
        $this->middleware('auth.gard:api', ['except' => ['login', 'register','checkCode', 'checkPhone', 'SendCode','forgetPassword', 'ActiveRemoveAccount']]);
    }


    public function login(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'phone' => 'required|digits:10|starts_with:05',
            'password' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $user_delete = User::onlyTrashed()->where('phone', $request->phone)->first();
        if ($user_delete) {
            return responseApiFalse(202, translate('Your account has been deleted. Please contact the administration for further assistance.'));
        }

        $token = auth('api')
            ->attempt(['phone' => $request->phone,
                'password' => $request->password]);
        if(!$token) {
            return responseApiFalse(202, translate('The login credentials you entered are incorrect. Please try again or reset your password if needed.'));
        }
        $user=auth('api')
            ->user();
        if($request->has('fcm_token')){
            FcmTokenModel::updateOrCreate(
                ['user_id' => $user->id],
                ['token' => $request->fcm_token]
            );
        }
        return responseApi(200, translate('return success'), $this->createNewToken($token));
    }


    public function register(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'name' => 'nullable|string|between:2,200',
            'phone' => 'required|digits:10|starts_with:05|unique:users',
            'email' => 'nullable|string|max:20|unique:users',
            'password' => 'required|confirmed|min:6|max:199',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();
            $inputs = $request->except(['password_confirmation']);
            $user = User::create($inputs);
            $this->authUtil->SendActivationCode($user);
            DB::commit();
            $data['user_id'] = $user->id;
            return responseApi(200, translate('user registered'), $data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }

    public function logout(): Application|Response|ResponseFactory
    {
        auth('api')->logout();
        return responseApi(200, translate('user logout'));
    }

    public function refresh(): Application|Response|ResponseFactory
    {
        return responseApi(200, translate('user login'), $this->createNewToken(auth('api')->refresh()));
    }


    public function SendCode(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $user = User::where('id', $request->user_id)->first();


        if ($user) {
            $this->authUtil->SendActivationCode($user);

            return responseApi(200, translate('return success'), $user->id);
        }
        return responseApiFalse(405, translate('user not found'));
    }

    public function checkCode(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'code' => 'required|max:6',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        try {

            $user = User::where('id', $request->user_id)->first();
            if ($user->activation_code == $request->code && $user->activation_code != null) {
                DB::beginTransaction();
                $user->activation_code = null;
                if ($user->activation_at == null) {
                    $user->activation_at = now();
                }
                if($request->has('fcm_token')){
                    FcmTokenModel::createOrUpdate(['user_id'=>$user->id],['token'=> $request->fcm_token]);
                }
                $user->save();

                DB::commit();
                $token = auth()->login($user);
                return responseApi(200, translate('user login'), $this->createNewToken($token));

            }
            return responseApiFalse(500, translate('activation code is incorrect'));
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }

    public function removeAccount(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'password' => 'required|string|min:4|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        if (Hash::check($request->password, auth()->user()->getAuthPassword())) {
            auth()->user()->delete();
            auth()->logout();

            return responseApi(200, translate('Account deleted'));
        }
        return responseApiFalse(500, translate('password is incorrect'));
    }

    public function forgetPassword(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'phone' => 'required|digits:10|starts_with:05',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $this->authUtil->SendActivationCode($user);
            return responseApi(200, translate('return success'), $user->id);
        }
        return responseApiFalse(405, translate('user not found'));
    }


    public function resetPassword(Request $request): Application|Response|ResponseFactory
    {
        $validator = validator($request->all(), [
            'password' => 'required|confirmed|min:6|max:199',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        auth()->user()->update(['activation_code'=>null,
            'password' => bcrypt($request->password)]);
        auth()->user()->save();

        return responseApi(200, translate('Password has been restored'));
    }

    public function ActiveRemoveAccount(): Application|Response|ResponseFactory
    {
        $active_delete_acount= true; //$is_active?->active_delete_acount;
        if($active_delete_acount != \request('app_version') &&  \request('type')  != 'android'){
            return responseApi(200,'', false);
        }elseif($active_delete_acount != \request('app_version') &&  \request('type')  == 'android'){
            return responseApi(200,'', false);
        }
        return responseApi(200,'', true);


    }

    protected function createNewToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,//auth('api')->factory()->getTTL() * 60,
            'user' => new CustomerResource(auth()->user())
        ];
    }
}

