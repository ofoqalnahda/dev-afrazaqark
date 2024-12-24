<?php

// ---------------- Api response -------------------
if (!function_exists('responseApi')) {
    function responseApi($code, $message = '', $data = null)
    {
        return response([
            'status' =>'success',
            'code' => $code != null ? $code : 200,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
// ---------------- Api response -------------------
if (!function_exists('responseApiFalse')) {
    function responseApiFalse($code= null, $message = '', $data = null)
    {
        return response([
            'status' => 'failed' ,
            'code' => $code != null ? $code : 500,
            'message' => $message,
            'data' => $data,
        ]);
    }
}

// ---------------- Locales -------------------
if (!function_exists('locales')) {
    function locales()
    {
        return config('app.locales');
    }
}

// ---------------- Admin Type -------------------
if (!function_exists('majorAdmin')) {
    function majorAdmin()
    {
        if (auth('admin')->user()->type == 'major') return true;
        return false;
    }
}

// ---------------- Positions -------------------
if (!function_exists('positions')) {
    function positions()
    {
        return [
            'left' => trans('store.left'),
            'right' => trans('store.right'),
            'center' => trans('store.center'),
        ];
    }
}

// ---------------- Boolean values -------------------
if (!function_exists('booleanValues')) {
    function booleanValues()
    {
        return [
            0 => trans('store.no'),
            1 => trans('store.yes'),
        ];
    }
}

// ---------------- Payment Methods -------------------
if (!function_exists('paymentMethods')) {
    function paymentMethods()
    {
        return [
            'cash' => trans('store.cash'),
            'visa' => trans('store.visa'),
            'coins' => trans('store.coins'),
        ];
    }
}

// ---------------- Menu active -------------------
if (!function_exists('active')) {
    function active($array)
    {
        $route = explode('.', request()->route()->getName())[0];
        if (in_array($route, $array)) return true;
        return false;
    }
}
function store_file($file,$path)
{
    $name = time().$file->getClientOriginalName();
    return $value = $file->storeAs($path, $name, 'uploads');
}
function delete_file($file)
{
    if($file!='' and !is_null($file) and Storage::disk('uploads')->exists($file)){
        unlink('uploads/'.$file);
    }
}
function display_file($name)
{
    return asset('uploads').'/'.$name;
} function remove_invalid_charcaters($str)
{
    return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
}


function translate($key)
{
    $local = app()->getLocale();

    try {
        $lang_array = include(base_path('lang/' . $local . '/translation.php'));
        $processed_key = ucfirst(str_replace('_', ' ', remove_invalid_charcaters($key)));

        if (!array_key_exists($key, $lang_array)) {
            $lang_array[$key] = $processed_key;
            $str = "<?php return " . var_export($lang_array, true) . ";";
            file_put_contents(base_path('lang/' . $local . '/translation.php'), $str);
            $result = $processed_key;
        } else {
            $result = __('translation.' . $key);
        }
    } catch (\Exception $exception) {
        $result = __('translation.' . $key);
    }

    return $result;
}


function uploadFile($request,$name_file,$model)
{
    if ($request->hasFile($name_file)) {
        $model->addMedia($request->file($name_file))->toMediaCollection($name_file);
    }
}
