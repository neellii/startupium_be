<?php

namespace App\Exceptions\MediaLibrary;

use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\DiskCannotBeAccessed;

class AccessException extends DiskCannotBeAccessed
{
    public function render()
    {
        return response()->json(["message" => 'Отказано в доступе.'], Response::HTTP_FORBIDDEN);
    }
}
