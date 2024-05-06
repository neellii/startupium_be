<?php

namespace App\Http\Controllers\Api\Project;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\UseCases\Projects\ComplaintService;
use App\Http\Requests\Project\CreateComplaintRequest;

class ComplaintController extends Controller
{
    private $service;

    public function __construct(ComplaintService $service)
    {
        $this->service = $service;
    }

    // пожаловаться на проект с указанием причины жалобы
    public function add(CreateComplaintRequest $request, string $projectId): JsonResponse
    {
        $this->service->add($request, $projectId);
        return Response::HTTP_OK(['success' => true]);
    }

    // удалить эалобу на проект
    public function remove(string $projectId): JsonResponse
    {
        $this->service->remove($projectId);
        return Response::HTTP_OK(['success' => true]);
    }
}
