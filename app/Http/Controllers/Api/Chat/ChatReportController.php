<?php
namespace App\Http\Controllers\Api\Chat;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageReportRequest;
use App\Http\Resources\Message\MessageReportListResource;
use App\UseCases\Chat\ChatReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatReportController extends Controller
{
    private $service;

    public function __construct(ChatReportService $service)
    {
        $this->service = $service;
    }

    public function add(SendMessageReportRequest $request, string $contactId, string $messageId): JsonResponse
    {
        $this->service->add($request, $contactId, $messageId);
        return Response::HTTP_OK([]);
    }

    public function remove(Request $request, string $contactId, string $messageId): JsonResponse
    {
        $this->service->remove($request, $contactId, $messageId);
        return Response::HTTP_OK([]);
    }

    public function reports(Request $request, string $contactId): JsonResponse
    {
        $messages = $this->service->reports($request, $contactId);
        return Response::HTTP_OK(MessageReportListResource::collection($messages));
    }
}
