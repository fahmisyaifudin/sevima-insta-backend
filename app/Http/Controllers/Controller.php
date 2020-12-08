<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function successResponse($data = null)
    {
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    protected function errorResponse($message, $status)
    {
        return response()->json([
            'message' => $message
        ], $status);
    }
}
