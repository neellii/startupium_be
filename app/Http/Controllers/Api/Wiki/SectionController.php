<?php

namespace App\Http\Controllers\Api\Wiki;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wiki\WikiSectionDeleteRequest;
use App\Http\Requests\Wiki\WikiSectionRequest;
use App\Http\Requests\Wiki\WikiSectionUpdateRequest;
use App\Http\Resources\Wiki\WikiSectionDetailResource;
use App\Http\Resources\Wiki\WikiSectionListResource;
use App\UseCases\Wiki\WikiSectionService;

class SectionController extends Controller
{
    protected $service;
    public function __construct(WikiSectionService $service)
    {
        $this->service = $service;
    }
    public function getSections(string $project_id) {
        $sections = $this->service->getSections($project_id);
        return Response::HTTP_OK(WikiSectionListResource::collection($sections));
    }

    public function create(WikiSectionRequest $request, string $project_id) {
        $section = $this->service->createSection($request, $project_id);
        return Response::HTTP_CREATED(new WikiSectionDetailResource($section));
    }

    public function delete(WikiSectionDeleteRequest $request, string $project_id) {
        $section = $this->service->deleteSection($request, $project_id);
        return Response::HTTP_OK(new WikiSectionDetailResource($section));
    }

    public function update(WikiSectionUpdateRequest $request, string $project_id) {
        $section = $this->service->updateSection($request, $project_id);
        return Response::HTTP_OK(new WikiSectionDetailResource($section));
    }
}
