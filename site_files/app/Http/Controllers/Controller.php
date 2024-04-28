<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendError($errors, $code)
    {
        return response()->json(['message' => sizeof($errors) > 0 ? $errors[0]['message'] : 'Invalid Input!',
            'errors' => $errors],
            $code);
    }

    public function sendSuccess($message, $data,$code = 200)
    {
        return response()->json(['message' => $message,
            'data' => $data
        ],
            $code);
    }

    public function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }
}
