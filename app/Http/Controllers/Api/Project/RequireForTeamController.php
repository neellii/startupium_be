<?php

namespace App\Http\Controllers\Api\Project;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\CreateRequireForTeamRequest;
use App\Http\Requests\Tag\HideRequireForTeamRequest;
use App\Http\Requests\Tag\RemoveRequireForTeamRequest;
use App\Http\Requests\Tag\UpdateRequireTeamRequest;
use App\Http\Resources\Tag\RequireForTeamResource;
use App\Http\Resources\Tag\UpdateRequiredTeamResource;
use App\UseCases\Projects\RequireTeamTagsService;

class RequireForTeamController extends Controller
{
    private $service;
    public function __construct(RequireTeamTagsService $service) {
        $this->service = $service;
    }

    // all tags
    public function positions(string $projectId) {
        $tags = $this->service->getTags($projectId);
        return Response::HTTP_OK(RequireForTeamResource::collection($tags));
    }

    // create tag
    public function create(CreateRequireForTeamRequest $request, string $projectId) {
        $tag = $this->service->createPosition($request, $projectId);
        return Response::HTTP_OK(new RequireForTeamResource($tag));
    }

    // update tag
    public function update(UpdateRequireTeamRequest $request, string $projectId) {
        $tag = $this->service->updatePosition($request, $projectId);
        return Response::HTTP_OK(new UpdateRequiredTeamResource($tag));
    }

    // delete tag
    public function delete(RemoveRequireForTeamRequest $request, string $projectId) {
        $tag = $this->service->deletePosition($request, $projectId);
        return Response::HTTP_OK(new RequireForTeamResource($tag));
    }

    public function switchTag(HideRequireForTeamRequest $request, string $projectId) {
        $tag = $this->service->switchVisibleUnVisibleTag($request, $projectId);
        return Response::HTTP_OK(new RequireForTeamResource($tag));
    }
}
