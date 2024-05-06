<?php

namespace App\Http\Controllers\Api\Residence;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Residence\CountryResource;
use App\Http\Resources\Residence\ResidenceResource;
use App\UseCases\Residence\ResidenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResidenceController extends Controller
{
    private $service;
    public function __construct(ResidenceService $service)
    {
        $this->service = $service;
    }

    public function countries(): JsonResponse {
        $countries = $this->service->getCountries();
        return Response::HTTP_OK(CountryResource::collection($countries));
    }

    public function searchCountries(Request $request) {
        $countries = $this->service->getResultsCountries($request);
        return Response::HTTP_OK(CountryResource::collection($countries));
    }

    public function cities(Request $request): JsonResponse {
        $cities = $this->service->getCitiesAndRegions($request);
        return Response::HTTP_OK(ResidenceResource::collection($cities));
    }

    public function searchCities(Request $request): JsonResponse {
        $cities = $this->service->getResultsCitiesAndRegions($request);
        return Response::HTTP_OK(ResidenceResource::collection($cities));
    }
}
