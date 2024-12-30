<?php

namespace Modules\Customer\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\HigherOrderWhenProxy;
use Modules\Auth\App\Models\User;
use App\Traits\FCMNotificationTrait;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CustomerService
{
    use FCMNotificationTrait;

    /**
     * all users and filter
     *
     * @param Request $request
     * @return array|Builder[]|Collection|HigherOrderWhenProxy[]
     */

    public function index (Request $request)
    {

        return User::query()->when($request->search , function ($q) use ($request) {
            $q->where('name','like','%'.$request->search.'%')
                ->orwhere('id','like',$request->search.'%')
                ->orwhere('email','like',$request->search.'%');
        })->get();

    }

    /**
     * all users deleted and filter
     *
     * @param Request $request
     * @return array|Builder[]|Collection|HigherOrderWhenProxy[]|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */

    public function IndexDeleted (Request $request)
    {

        $users = User::onlyTrashed()
            ->when($request->search , function ($q) use ($request) {
                $q->where('name','like','%'.$request->search.'%')
                    ->orwhere('id','like',$request->search.'%')
                    ->orwhere('email','like',$request->search.'%');
            })->where('deleted_at', '!=',null)->get();
        return $users;

    }
    /**
     * update  User
     *
     * @param Request $request
     * @param User $user
     * @return User
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(Request $request ,User $user): User
    {
        $data = [
            'name' => $request->get('name'),
            'status' => $request->is_active,
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
        ];
        if($request->has('password')){
            $data['password'] = bcrypt($request->get('password'));
        }
        $user->update($data);
        if ($request->has('image')) {
            $user->clearMediaCollection('images');
            $user->addMediaFromRequest('image')->toMediaCollection('images');
        }
        return $user;
    }

    /**
     * Adding a New User
     *
     * @param array $data
     * @param file|null $image
     * @return User
     */
    public function create(array $data , $image = null): User
    {
        $user=User::create($data);
        if ($image) {
            $user->addMedia($image)->toMediaCollection('images');
        }
        return $user;
    }
    /**
     * update status for user
     *
     * @param User $user
     */
    public function updateStatus(User $user ): void
    {
        $user->status = ($user->status - 1) * -1;
        $user->save();
    }




}
