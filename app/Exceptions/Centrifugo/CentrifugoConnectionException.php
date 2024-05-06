<?php

namespace App\Exceptions\Centrifugo;

use Illuminate\Http\Response;

final class CentrifugoConnectionException extends CentrifugoException
{
    public function render($request)
    {
        return response()->json(["message" => "Не удалось подключиться к сокет серверу."], Response::HTTP_BAD_REQUEST);
    }
}
