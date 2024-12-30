<?php

namespace Modules\Customer\App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use GPBMetadata\Google\Api\Log;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Admin\App\Models\Admin;
use Modules\Auth\App\Models\User;
use Modules\Customer\App\resources\Dashboard\CustomerResource;
use Modules\Customer\Services\CustomerService;
use function GuzzleHttp\Promise\all;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Admin::class );
        $this->middleware('auth.gard:admin');
    }

    /**
     * Show customers.
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function index(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
       $customers= $this->customerService->index($request);
        return responseApi(200, translate('return success'),CustomerResource::collection($customers));

    }
    /**
     * Show deleted customers.
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function IndexDeleted(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
       $customers= $this->customerService->IndexDeleted($request);
        return responseApi(200, translate('return success'),CustomerResource::collection($customers));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function store(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:150',
            'phone' => 'required|digits:10|starts_with:05|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $data = [
            'name' => $request->get('name'),
            'status' => $request->is_active,
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'activation_at' => now(),
        ];
        try {
            DB::beginTransaction();
                $customer = $this->customerService->create($data,$request->image);

            DB::commit();
            return responseApi(200, translate('create Customer success'),new CustomerResource($customer));
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
        $customer=  User::where('id', $id)->first();
        if(!$customer){
            return responseApiFalse(404, translate('Customer not found'));
        }
        return responseApi(200, translate('create Customer success'),new CustomerResource($customer));
    }


    /**
     * Update the specified resource in storage.
     * @param $id
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function update( $id,Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $customer=  User::where('id', $id)->first();
        if(!$customer){
            return responseApiFalse(404, translate('Customer not found'));
        }
        $validator = validator($request->all(), [
            'name' => 'required|string|max:150',
            'phone' => 'required|digits:10|starts_with:05|unique:users,phone,'.$customer->id,
            'email' => 'nullable|email|unique:users,email,'.$customer->id,
            'is_active' => 'required|in:0,1',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        try {
            DB::beginTransaction();
            $customer = $this->customerService->update($request,$customer);

            DB::commit();
            return responseApi(200, translate('update Customer success'),new CustomerResource($customer));
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
        $customer=  User::where('id', $id)->first();
        if(!$customer){
            return responseApiFalse(404, translate('Customer not found'));
        }

        try {
            DB::beginTransaction();
             $this->customerService->updateStatus($customer);
            DB::commit();
            return responseApi(200, translate('update Customer success'));
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
            return responseApiFalse(404, translate("can't delete your own customer"));
        }
        User::destroy($id);
        return responseApi(200, translate('destroy success'));
    }
    /**
     * restore the specified resource from storage.
     * @param int $id
     * @return Application|Response|ResponseFactory
     */
    public function restore(int $id): Application|Response|ResponseFactory
    {
        User::where('id',$id)->restore();
        return responseApi(200, translate('restore success'));
    }

}
