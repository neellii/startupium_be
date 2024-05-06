<?php

namespace App\Http\Controllers\Api\Blog;

use App\Entity\Blog\Blog;
use App\Http\Resources\Blog\BlogCredentialsResource;
use DomainException;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response\Response;
use App\UseCases\Blog\BlogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\CreateBlogRequest;
use App\Http\Requests\Blog\CreateDraftRequest;
use App\Http\Requests\Blog\DeleteBlogRequest;
use App\Http\Requests\Blog\PublishDraftRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Http\Resources\Blog\BlogBelongsToListResource;
use App\Http\Resources\Blog\BlogBelongsToResource;
use App\Http\Resources\Blog\BlogResource;
use App\UseCases\Blog\BlogSubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    private $service;
    private $blogSubjectService;
    public function __construct(BlogService $service, BlogSubjectService $blogSubjectService)
    {
        $this->service = $service;
        $this->blogSubjectService = $blogSubjectService;
    }

    // данные для обновления
    public function blogCredentials(string $slug): JsonResponse {
        $blog = $this->service->getBlogCredentials($slug);
        return Response::HTTP_OK(new BlogCredentialsResource($blog));
    }

    // все блоги
    public function blogs(Request $request): JsonResponse {
        $blogs = $this->service->getBlogs(
            $request['sortBlogsBy'], $request['searchBlogsBy'], $request['titleBlog']
        );
        return Response::HTTP_OK(BlogResource::collection($blogs));
    }

    // блоги проекта
    public function projectBlogs(Request $request, $projectId): JsonResponse {
        $project = findActiveProject($projectId);
        $blogs = $this->service->getProjectBlogs($project->id, $request['sortBlogsBy'], $request['searchBlogsBy']);
        return Response::HTTP_OK(new BlogBelongsToListResource($project, $blogs));
    }

    // черновики проекта
    public function projectDrafts($projectId): JsonResponse {
        $project = findAuthActiveProject($projectId);
        $blogs = $this->service->getProjectDrafts($project);
        return Response::HTTP_OK(new BlogBelongsToListResource($project, $blogs));
    }

    // блоги пользователя
    public function userBlogs(Request $request, $userId): JsonResponse {
        $user = findActiveUser($userId);
        $blogs = $this->service->getUserBlogs($user->id, $request['sortBlogsBy'], $request['searchBlogsBy']);
        return Response::HTTP_OK(new BlogBelongsToListResource($user, $blogs));
    }

    // черновики пользователя
    public function userDrafts($userId): JsonResponse {
        $user = authUser();
        if (strval($userId) !== strval($user->id)) {
            throw new DomainException(config('constants.user_not_found'));
        }
        $blogs = $this->service->getUserDrafts($user);
        return Response::HTTP_OK(new BlogBelongsToListResource($user, $blogs));
    }

    // статья блога проекта
    public function anyProjectBlog($projectId = "", $blog_slug = ""): JsonResponse {
        $auth = findAuthUser();
        $project = findActiveProject($projectId);
        $blog = findProjectBlogBySlugAsNull($blog_slug, $project->id);
        if ($blog?->status === Blog::STATUS_DRAFT && strval($auth?->id) !== strval($project?->user?->id)) {
            throw new DomainException(config("constants.content_not_found"));
        }
        return Response::HTTP_OK(new BlogBelongsToResource($project, $blog));
    }

    // статья блога пользователя
    public function anyUserBlog($userId = "", $blog_slug = ""): JsonResponse {
        $auth = findAuthUser();
        $user = findActiveUser($userId);
        $blog = findUserBlogBySlugAsNull($blog_slug, $user->id);
        if ($blog?->status === Blog::STATUS_DRAFT && strval($auth?->id) !== strval($user?->id)) {
            throw new DomainException(config("constants.content_not_found"));
        }
        return Response::HTTP_OK(new BlogBelongsToResource($user, $blog));
    }

    // удалить блог проекта
    // tests/Feature/Controllers/BlogController/deleteProjectBlog_Test.php
    public function deleteForProject(DeleteBlogRequest $request, $project_id, $blog_slug): JsonResponse {
        $project = findAuthActiveProject($project_id);
        $blog = $this->service->deleteBlog(findProjectBlogBySlug($blog_slug, $project->id));
        return Response::HTTP_OK(new BlogResource($blog));
    }

    // удалить блог пользователя
    // tests/Feature/Controllers/BlogController/deleteUserBlog_Test.php
    public function deleteForUser(DeleteBlogRequest $request, $userId, $blog_slug): JsonResponse {
        $auth = authUser();
        if (strval($userId) !== strval($auth->id)) {
            throw new DomainException(config('constants.user_not_found'));
        }
        $blog = $this->service->deleteBlog(findUserBlogBySlug($blog_slug, $auth->id));
        return Response::HTTP_OK(new BlogResource($blog));
    }

    // опубликовать блог проекта
    public function publishProjectDraft(PublishDraftRequest $request, $projectId): JsonResponse {
        $blog = DB::transaction(function() use($request, $projectId) {
            $project = findAuthActiveProject($projectId);
            $blog = findProjectBlogBySlug($request['slug'], $project->id);
            $this->blogSubjectService->createUpdateSubjects($request['subjects'], $blog);

            return $this->service->publishDraft($request['title'], $request['description'], $blog);
        });
        return Response::HTTP_OK(new BlogResource($blog));
    }

    // опубликовать блог пользователя
    public function publishUserDraft(PublishDraftRequest $request, $userId): JsonResponse {
        $blog = DB::transaction(function() use($request, $userId) {
            $auth = authUser();
            if (strval($userId) !== strval($auth->id)) {
                throw new DomainException(config('constants.user_not_found'));
            }
            $blog = findUserBlogBySlug($request['slug'], $auth->id);
            $this->blogSubjectService->createUpdateSubjects($request['subjects'], $blog);

            return $this->service->publishDraft($request['title'], $request['description'], $blog);
        });
        return Response::HTTP_OK(new BlogResource($blog));
    }

    // создать блог/статью
    public function createBlog(CreateBlogRequest $request): JsonResponse {
        $auth = authUser();
        $blog = null;
        $project_id = null;
        $user_id = null;
        $author = $request['author'];
        if (!$author['id']) {
            throw new DomainException(config('constants.something_went_wrong'));
        }

        $authorIds = $this->service->getAuthorBlog($author['id'], $auth->id);
        $project_id = $authorIds['project_id'];
        $user_id = $authorIds['user_id'];

        if ($user_id || $project_id) {
            $blog = DB::transaction(function() use($request, $project_id, $user_id) {
                $blog = $this->service->createBlog(
                    $request['title'], $request['description'], $request['slug'], $project_id, $user_id
                );
                $this->blogSubjectService->createUpdateSubjects($request['subjects'], $blog);
                return $blog;
            });
        } else {
            throw new DomainException(config('constants.content_not_found'));
        }

        return Response::HTTP_OK(new BlogResource($blog));
    }

    // создать черновик блога или перенести существующий в черновик
    public function createDraft(CreateDraftRequest $request): JsonResponse {
        $auth = authUser();
        $blog = null;
        $project_id = null;
        $user_id = null;
        $author = $request['author'];
        if (!$author['id']) {
            throw new DomainException(config('constants.something_went_wrong'));
        }

        $authorIds = $this->service->getAuthorBlog($author['id'], $auth->id);
        $project_id = $authorIds['project_id'];
        $user_id = $authorIds['user_id'];

        if ($project_id || $user_id) {
            $blog = Blog::query()->where('slug', 'like', $request['slug'])->first();
            $blog = DB::transaction(function() use($request, $blog, $project_id, $user_id) {
                if (!$blog) {
                    $blog = $this->service->createBlog(
                        $request['title'], $request['description'], $request['slug'], $project_id, $user_id, Blog::STATUS_DRAFT
                    );
                } else {
                    // переносим существующий в черновик
                    $blog = $this->service->updateBlog(
                        $request['title'], $request['description'], generateDraftSlug(), $blog, Blog::STATUS_DRAFT, $user_id, $project_id);
                }
                $this->blogSubjectService->createUpdateSubjects($request['subjects'], $blog);
                return $blog;
            });
        }
        else {
            throw new DomainException(config('constants.content_not_found'));
        }
        return Response::HTTP_OK(new BlogResource($blog));
    }

    // обновить блог/статью
    public function updateBlog(UpdateBlogRequest $request): JsonResponse {
        $auth = authUser();
        $project_id = null;
        $user_id = null;
        $author = $request['author'];
        if (!$author['id']) {
            throw new DomainException(config('constants.something_went_wrong'));
        }

        $blog = findBlogBySlug($request['slug']);

        $authorIds = $this->service->getAuthorBlog($author['id'], $auth->id);
        $project_id = $authorIds['project_id'];
        $user_id = $authorIds['user_id'];

        if ($project_id || $user_id) {
            $blog = DB::transaction(function() use($request, $blog, $user_id, $project_id) {
                if ($blog->status === Blog::STATUS_DRAFT) {
                    $blog = $this->service->updateBlog(
                        $request['title'], $request['description'], "", $blog, null, $user_id, $project_id);
                } else {
                    if (count($request['subjects']) >= 1 && $request['description']) {
                        $blog = $this->service->updateBlog(
                            $request['title'], $request['description'], generateSlug($request['title']), $blog, null, $user_id, $project_id);
                    } else {
                        throw new DomainException(config('constants.transmit_incorrect_data'));
                    }
                }
                $this->blogSubjectService->createUpdateSubjects($request['subjects'], $blog);
                return $blog;
            });
        } else {
            throw new DomainException(config('constants.content_not_found'));
        }

        return Response::HTTP_OK(new BlogResource($blog));
    }
}
