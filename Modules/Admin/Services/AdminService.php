<?php

namespace Modules\Admin\Services;

use Illuminate\Http\Request;
use Modules\Admin\App\Models\Admin;
use Modules\Auth\App\Models\User;
use App\Traits\FCMNotificationTrait;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class AdminService
{
    use FCMNotificationTrait;

    /**
     * all admins and filter
     *
     * @param Request $request
     * @return Admin
     */

    public function index (Request $request)
    {

        return Admin::query()->when($request->search , function ($q) use ($request) {
            $q->where('name','like','%'.$request->search.'%')
                ->orwhere('id','like',$request->search.'%')
            ->orwhere('email','like',$request->search.'%');
        })->when($request->id, function ($q) use ($request) {
            $q->where('id',$request->id);
        })->when($request->email, function ($q) use ($request) {
            $q->where('email',$request->email);
        })->get();

    }

    /**
     * all admins deleted and filter
     *
     * @param Request $request
     * @return Admin
     */

    public function IndexDeleted (Request $request)
    {

        $admins = Admin::onlyTrashed()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->id, function ($q) use ($request) {
                $q->where('id', $request->id);
            })
            ->when($request->email, function ($q) use ($request) {
                $q->where('email', $request->email);
            })->where('deleted_at', '!=',null)->get();
        return $admins;

    }
    /**
     * update  Admin
     *
     * @param array $data
     * @param Admin $admin
     * @param null $image
     * @return Admin
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(Request $request ,Admin $admin, $image = null): Admin
    {
        $data = [
            'name' => $request->get('name'),
            'is_active' => $request->is_active,
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
        ];
        if($request->has('password')){
            $data['password'] = bcrypt($request->get('password'));
        }
        $admin->update($data);
        if ($request->has('image')) {
            $admin->clearMediaCollection('images');
            $admin->addMediaFromRequest('image')->toMediaCollection('images');
        }
        return $admin;
    }

    /**
     * Adding a New Admin
     *
     * @param array $data
     * @param file|null $image
     * @return Admin
     */
    public function create(array $data , $image = null): Admin
    {
     $admin=Admin::create($data);
        if ($image) {
            $admin->addMedia($image)->toMediaCollection('images');
        }
        return $admin;
    }
    /**
     * update status for admin
     *
     * @param Admin $admin
     */
    public function updateStatus(Admin $admin ): void
    {
        $admin->is_active = ($admin->is_active - 1) * -1;
        $admin->save();
    }




}
