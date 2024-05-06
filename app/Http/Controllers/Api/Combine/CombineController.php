<?php

namespace App\Http\Controllers\Api\Combine;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Combine\CombineResource;
use App\UseCases\Combine\CombineService;

class CombineController extends Controller
{
    private $service;

    public function __construct(CombineService $service)
    {
        $this->middleware("auth.pagination");
        $this->service = $service;
    }

    public function combine() {
        $data = $this->service->fetchUsersProjects();
        return Response::HTTP_OK(CombineResource::collection($data));
    }
}
