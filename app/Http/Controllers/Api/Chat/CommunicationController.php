<?php

namespace App\Http\Controllers\Api\Chat;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\SendMessageRequest;
use App\Http\Resources\Message\CommunicationListResource;
use App\Http\Resources\Message\CommunicationResource;
use App\UseCases\Centrifugo\CentrifugoService;
use App\UseCases\Chat\CommunicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{

    private $communicationService;
    private $centrifugoService;
    public function __construct(CommunicationService $communicationService, CentrifugoService $centrifugoService)
    {
        $this->centrifugoService = $centrifugoService;
        $this->communicationService = $communicationService;
    }

    // cipher messages
    public function messages(Request $request): JsonResponse {
        $withCipher = config('crypto.with_cipher');
        $messages = $this->communicationService->getMessages($request);
        if ($withCipher === true) {
            return Response::HTTP_OK(['cdata' => handleEncrypted(new CommunicationResource($messages))]);
        }
        return Response::HTTP_OK(['data' => new CommunicationResource($messages)]);
    }

    // create message значение / зашифрованное значение
    public function sendMessage(SendMessageRequest $request): JsonResponse {
        $withCipher = config('crypto.with_cipher');
        $message = $this->communicationService->create($request);
        if ($withCipher === true) {
            $cdata = handleEncrypted(new CommunicationListResource($message));
            $this->centrifugoService->sendCommunicationMessage($cdata, $message->project_id);
            return Response::HTTP_OK(['cdata' => $cdata]);
        } else {
            $this->centrifugoService->sendCommunicationMessage($message, $message->project_id);
            return Response::HTTP_OK(new CommunicationListResource($message));
        }
    }
}

