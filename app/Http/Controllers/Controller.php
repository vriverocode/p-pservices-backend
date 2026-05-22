<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    //
    use AuthorizesRequests;
    use ValidatesRequests;

    public function returnSuccess($code, $data)
    {
        return response()->json([
            'code' => $code,
            'data' => $data
        ], $code);
    }

    public function returnFail($code, $error)
    {
        return response()->json([
            'code' => $code,
            'error' => $error
        ], $code);
    }
}
