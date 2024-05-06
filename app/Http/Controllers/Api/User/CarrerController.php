<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Carrer\CreateCarrerRequest;
use App\Http\Requests\Carrer\DeleteCarrerRequest;
use App\Http\Resources\Carrer\CarrerDetailResource;
use App\UseCases\Carrer\CarrerService;
use Illuminate\Http\JsonResponse;

class CarrerController extends Controller
{
    private $service;
    public function __construct(CarrerService $service)
    {
        $this->service = $service;
    }


    // создать карьеру пользователя
    public function createCareer(CreateCarrerRequest $request): JsonResponse
    {
        $carrer = $this->service->createCarrer($request);
        return Response::HTTP_OK(new CarrerDetailResource($carrer));
    }

    // удалить карьеру пользователя
    public function deleteCareer(DeleteCarrerRequest $request): JsonResponse
    {
        $carrer = $this->service->deleteCarrer($request);
        return Response::HTTP_OK(new CarrerDetailResource($carrer));
    }

    // обновить карьеру пользователя
    public function updateCareer(CreateCarrerRequest $request): JsonResponse
    {
        $carrer = $this->service->updateCarrer($request);
        return Response::HTTP_OK(new CarrerDetailResource($carrer));
    }
}
