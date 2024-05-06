<?php

namespace App\Http\Controllers\Api\Wiki;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wiki\WikiArticleCreateCopyRequest;
use App\Http\Requests\Wiki\WikiArticleCreateRequest;
use App\Http\Requests\Wiki\WikiUpdateArticleRequest;
use App\Http\Resources\Wiki\WikiArticleDetailResource;
use App\Http\Resources\Wiki\WikiArticleListResource;
use App\UseCases\Wiki\WikiArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $service;
    public function __construct(WikiArticleService $service)
    {
        $this->service = $service;
    }

    public function getArticles(Request $request, string $project_id) {
        $articles = $this->service->getArticlesBySection($request, $project_id);
        return Response::HTTP_OK(WikiArticleListResource::collection($articles));
    }

    public function defaultArticle(Request $request, string $project_id) {
        $article = $this->service->getArticleDefault($request, $project_id);
        if ($article) {
            return Response::HTTP_OK(new WikiArticleDetailResource($article));
        } else {
            return Response::HTTP_OK(['data' => ""]);
        }

    }

    public function create(WikiArticleCreateRequest $request, string $project_id) {
        $article = $this->service->createArticle($request, $project_id);
        return Response::HTTP_CREATED(new WikiArticleDetailResource($article));
    }

    public function createCopy(WikiArticleCreateCopyRequest $request, string $project_id) {
        $article = $this->service->createArticleCopy($request, $project_id);
        return Response::HTTP_CREATED(new WikiArticleDetailResource($article));
    }

    // not used
    public function addToDefault(WikiArticleCreateCopyRequest $request, string $project_id) {
        $article = $this->service->createDefaultArticle($request, $project_id);
        return Response::HTTP_CREATED(new WikiArticleDetailResource($article));
    }

    public function delete(Request $request, string $project_id) {
        $article = $this->service->deleteArticle($request, $project_id);
        return Response::HTTP_OK(new WikiArticleDetailResource($article));
    }

    public function update(WikiUpdateArticleRequest $request, string $project_id) {
        $article = $this->service->updateArticle($request, $project_id);
        return Response::HTTP_OK(new WikiArticleDetailResource($article));
    }
}
