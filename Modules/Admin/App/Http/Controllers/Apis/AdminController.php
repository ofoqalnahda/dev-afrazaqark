<?php

namespace Modules\Admin\App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use GPBMetadata\Google\Api\Log;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Admin\App\Http\resources\NotificationResource;
use Modules\Admin\App\Http\resources\AdminWithPermissionsResource;
use Modules\Admin\App\Models\Admin;
use Modules\Admin\Services\AdminService;
use function GuzzleHttp\Promise\all;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Admin::class );
        $this->middleware('auth.gard:admin', ['except' => ['login', 'checkPhone', 'SendCode','forgetPassword','checkCode','getAllPermission']]);
    }

    /**
     * Show admins.
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function index(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
       $admins= $this->adminService->index($request);
        return responseApi(200, translate('return success'),NotificationResource::collection($admins));

    }
    /**
     * Show deleted admins.
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function IndexDeleted(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
       $admins= $this->adminService->IndexDeleted($request);
        return responseApi(200, translate('return success'),NotificationResource::collection($admins));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function store(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $validator = validator($request->all(), [
            'phone' => 'required|digits:10|starts_with:05|unique:admins',
            'email' => 'nullable|email|unique:admins',
            'image' => 'nullable|image',
            'password' => 'required|min:6|confirmed',
            'permission_id' => 'required|array|min:1',
            'permission_id.*' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $data = [
            'name' => $request->get('name'),
            'is_active' => true,
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ];
        try {
            DB::beginTransaction();
                $admin = $this->adminService->create($data,$request->image);
                $admin->permissions()->sync($request->get('permission_id'));
            DB::commit();
            return responseApi(200, translate('create Admin success'),new NotificationResource($admin));
        }catch (\Exception $exception){
            DB::rollBack();
            \Illuminate\Support\Facades\Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }

    /**
     * Show the specified resource.
     * @param
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function show($id): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $admin=  Admin::where('id', $id)->first();
        if(!$admin){
            return responseApiFalse(404, translate('Admin not found'));
        }
        return responseApi(200, translate('create Admin success'),new AdminWithPermissionsResource($admin));
    }


    /**
     * Update the specified resource in storage.
     * @param $id
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function update( $id,Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $admin=  Admin::where('id', $id)->first();
        if(!$admin){
            return responseApiFalse(404, translate('Admin not found'));
        }
        $validator = validator($request->all(), [
            'phone' => 'required|digits:10|starts_with:05|unique:admins,phone,'.$admin->id,
            'email' => 'nullable|email|unique:admins,email,'.$admin->id,
            'image' => 'nullable|mimes:jpeg,jpg,png,gif',
            'is_active' => 'required|in:0,1',
            'password' => 'nullable|min:6|confirmed',
            'permission_id' => 'required|array|min:1',
            'permission_id.*' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        try {
            DB::beginTransaction();
            $admin = $this->adminService->update($request,$admin);
            $admin->permissions()->sync($request->get('permission_id'));
            DB::commit();
            return responseApi(200, translate('update Admin success'),new AdminWithPermissionsResource($admin));
        }catch (\Exception $exception){
            DB::rollBack();
            \Illuminate\Support\Facades\Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    /**
     * Update status.
     * @param $id
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function updateStatus( $id): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $admin=  Admin::where('id', $id)->first();
        if(!$admin){
            return responseApiFalse(404, translate('Admin not found'));
        }

        try {
            DB::beginTransaction();
             $this->adminService->updateStatus($admin);
            DB::commit();
            return responseApi(200, translate('update Admin success'));
        }catch (\Exception $exception){
            DB::rollBack();
            \Illuminate\Support\Facades\Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Application|Response|ResponseFactory
     */
    public function destroy(int $id): Application|Response|ResponseFactory
    {
        if($id == auth('admin')->id()){
            return responseApiFalse(404, translate("can't delete your own admin"));
        }
        Admin::destroy($id);
        return responseApi(200, translate('destroy success'));
    }
    /**
     * restore the specified resource from storage.
     * @param int $id
     * @return Application|Response|ResponseFactory
     */
    public function restore(int $id): Application|Response|ResponseFactory
    {
        Admin::where('id',$id)->restore();
        return responseApi(200, translate('restore success'));
    }
    /**
     * check password for admin.
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function checkPassword(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        if(\Hash::check($request->password, auth('admin')->user()->password)) {
            return responseApi(200, translate('password success'),);
        }
        return responseApiFalse(405, 'Password is incorrect');
    }
}
