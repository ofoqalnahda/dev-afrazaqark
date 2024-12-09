<?php

namespace Modules\Setting\App\Http\Controllers\Api;
use Modules\Setting\App\Http\resources\Api\ContactUsResource;
use App\Http\Controllers\Controller;
use Modules\Setting\App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ContactUsController extends Controller
{

    public function store(Request $request)
    {

        $validator = validator($request->all(), [
            'name' => 'required|string|max:200',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        try {

             ContactUs::create([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'message' => $request['message'],
            ]);
            return responseApi(200, translate('Contact message submitted successfully'));
        } catch (ValidationException $exception) {
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }


}
