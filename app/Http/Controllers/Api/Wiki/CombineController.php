<?php

namespace App\Http\Controllers\Api\Wiki;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\UseCases\Wiki\WikiArticleService;
use App\UseCases\Wiki\WikiSectionService;
use Exception;

class CombineController extends Controller
{
    protected $sectionService;
    protected $articleService;
    public function __construct(WikiSectionService $sectionService, WikiArticleService $articleService)
    {
        $this->sectionService = $sectionService;
        $this->articleService = $articleService;
    }

    public function getCombine(string $project_id) {
        $project = findProjectWithSubscriber($project_id);
        $sections = $this->sectionService->getGroupedSections($project);
        $articles = $this->articleService->getGroupedArticles($project);
        $_articles = array_keys($articles->all());
        foreach($_articles as $article) {
            try {
               $sections[$article] = array_merge($sections[$article]->all(), $articles[$article]->all());
            }
            catch(Exception $e) {
                $sections[$article] = $articles[$article];
            }
        }
        return Response::HTTP_OK(['data' => $sections]);
    }
}
