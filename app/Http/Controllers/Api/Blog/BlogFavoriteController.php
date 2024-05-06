<?php

namespace App\Http\Controllers\Api\Blog;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogFavoriteRequest;
use App\Http\Resources\Blog\BlogResource;
use App\UseCases\Blog\BlogFavoritesService;

class BlogFavoriteController extends Controller
{
    private $service;
    public function __construct(BlogFavoritesService $service)
    {
        $this->service = $service;
    }
    public function addToFavorites(BlogFavoriteRequest $request) {
        $blog = $this->service->addToFavorites(findBlogBySlug($request['slug']), authUser());
        return Response::HTTP_OK(new BlogResource($blog));
    }

    public function deleteFromFavorites(BlogFavoriteRequest $request) {
        $blog = $this->service->deleteFromFavorites(findBlogBySlug($request['slug']), authUser());
        return Response::HTTP_OK(new BlogResource($blog));
    }
}
