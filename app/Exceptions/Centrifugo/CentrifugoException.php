<?php

namespace App\Exceptions\Centrifugo;

use Exception;
use Illuminate\Http\Response;

class CentrifugoException extends Exception
{
    public function render($request)
    {
        return response()->json(["message" => config("constants.something_went_wrong")], Response::HTTP_BAD_REQUEST);
    }
}
