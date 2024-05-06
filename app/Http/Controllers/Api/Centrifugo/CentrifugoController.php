<?php

namespace App\Http\Controllers\Api\Centrifugo;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\UseCases\Centrifugo\CentrifugoService;
use Illuminate\Http\Request;

class CentrifugoController extends Controller
{
    private $centrifugoService;

    public function __construct(CentrifugoService $centrifugoService)
    {
        $this->centrifugoService = $centrifugoService;
    }
    public function getConnectionToken(Request $request) {
      return Response::HTTP_OK(['token' => $this->centrifugoService->getConnectionToken($request)]);
    }
}
