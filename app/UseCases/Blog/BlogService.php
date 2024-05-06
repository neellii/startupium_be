<?php

namespace App\UseCases\Blog;

use App\Entity\Blog\Blog;
use App\Entity\Blog\Subject;
use App\Entity\Project\Project;
use App\Entity\User\User;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogService {

    public function getBlogCredentials(string $slug = ""): Blog {
        $auth = authUser();
        $blog = Blog::query()->where('slug', 'like', $slug)->first();
        if (!$blog) {
            throw new \DomainException(config('constants.content_not_found'));
        }
        if (!(($blog?->project?->user?->id === $auth->id) || ($blog?->user?->id === $auth->id))) {
            throw new \DomainException(config('constants.content_not_found'));
        }
        return $blog;
    }
    public function getBlogs($sortBy = "", $searchBy = "", $titleBlog = "") {
        $query = Blog::query()->whereIn('blogs.status', [Blog::STATUS_MODERATION, Blog::STATUS_ACTIVE]);
        if ($titleBlog) {
            $query->where('blogs.title', 'like', '%' . $titleBlog . '%');
        }
        if ($sortBy === "popular") {
            $query->leftJoin('blog_comments', 'blogs.id', '=', 'blog_comments.blog_id');
            $query->groupBy('blogs.id');
            $query->orderByDesc('comments_count');
            $query->selectRaw('blogs.*, count(blog_comments.id) as comments_count');
        } else {
            $query->orderByDesc('created_at');
            $query->selectRaw('blogs.*');
        }
        if ($searchBy) {
            $subjects = Subject::query()
                ->where('title', 'like', $searchBy)
                ->join('blog_to_subjects_ref', 'blog_to_subjects_ref.blog_subject_id', 'like', 'blog_subjects.id');
            $query->joinSub($subjects, 'subjects', function (JoinClause $join) {
                $join->on('blogs.id', 'like', 'subjects.blog_id');
            });
        }
        return $query->paginate(config('constants.blogs_per_page'));
    }

    public function getProjectBlogs($projectId, $sortBy = "", $searchBy = ""): LengthAwarePaginator {
        $query = Blog::query()
            ->where('blogs.project_id', 'like', $projectId)
            ->whereIn('blogs.status', [Blog::STATUS_MODERATION, Blog::STATUS_ACTIVE]);
        if ($sortBy === "popular") {
            $query->leftJoin('blog_comments', 'blogs.id', '=', 'blog_comments.blog_id');
            $query->groupBy('blogs.id');
            $query->orderByDesc('comments_count');
            $query->selectRaw('blogs.*, count(blog_comments.id) as comments_count');
        } else {
            $query->orderByDesc('created_at');
            $query->selectRaw('blogs.*');
        }
        if ($searchBy) {
            $subjects = Subject::query()
                ->where('title', 'like', $searchBy)
                ->join('blog_to_subjects_ref', 'blog_to_subjects_ref.blog_subject_id', 'like', 'blog_subjects.id');
            $query->joinSub($subjects, 'subjects', function (JoinClause $join) {
                $join->on('blogs.id', 'like', 'subjects.blog_id');
            });
        }
        return $query->paginate(config('constants.blogs_per_page'));
    }

    public function getUserBlogs($userId, $sortBy = "", $searchBy = ""): LengthAwarePaginator {
        $query = Blog::query()
            ->where('blogs.user_id', 'like', $userId)
            ->whereIn('blogs.status', [Blog::STATUS_MODERATION, Blog::STATUS_ACTIVE]);
        if ($sortBy === "popular") {
            $query->leftJoin('blog_comments', 'blogs.id', '=', 'blog_comments.blog_id');
            $query->groupBy('blogs.id');
            $query->orderByDesc('comments_count');
            $query->selectRaw('blogs.*, count(blog_comments.id) as comments_count');
        } else {
            $query->orderByDesc('created_at');
            $query->selectRaw('blogs.*');
        }
        if ($searchBy) {
            $subjects = Subject::query()
                ->where('title', 'like', $searchBy)
                ->join('blog_to_subjects_ref', 'blog_to_subjects_ref.blog_subject_id', 'like', 'blog_subjects.id');
            $query->joinSub($subjects, 'subjects', function (JoinClause $join) {
                $join->on('blogs.id', 'like', 'subjects.blog_id');
            });
        }
        return $query->paginate(config('constants.blogs_per_page'));
    }

    public function getUserDrafts(User $user): LengthAwarePaginator {
        return Blog::query()
            ->where("user_id", 'like', $user->id)
            ->whereNull('project_id')
            ->whereIn('status', [Blog::STATUS_DRAFT])
            ->orderByDesc('created_at')
            ->paginate(config('constants.blogs_per_page'));
    }

    public function getProjectDrafts(Project $project): LengthAwarePaginator {
        return Blog::query()
            ->where("project_id", 'like', $project->id)
            ->whereNull('user_id')
            ->orderByDesc('created_at')
            ->whereIn('status', [Blog::STATUS_DRAFT])
            ->paginate(config('constants.blogs_per_page'));
    }

    public function createBlog(
        string $title, $description = null, $slug = null, $projectId = null, $userId = null, $status = Blog::STATUS_MODERATION
    ): Blog|null {
        $data = [
            'title' => $title,
            'slug' => $slug,
            'user_id' => $userId,
            'project_id' => $projectId,
            'description' => $description,
            'status' => $status
        ];
        try {
            $blog = Blog::query()->create($data);
        }
        catch(Exception $ex) {
            // if value slug - duplicate
            if ($ex->getCode() === '23000') {
                $data['slug'] = $slug . "-" . uniqid();
                $blog = Blog::query()->create($data);
            }
            return $blog;
        }
        return $blog;
    }

    public function updateBlog(
        string $title, $description = null, $slug = null, Blog $blog, $status = null, $user_id = null, $project_id = null
    ): Blog {
        $data = [
            'description' => $description,
            'user_id' => $user_id,
            'project_id' => $project_id,
            'status' => $status ? $status : $blog->status
        ];

        if ($blog->status !== Blog::STATUS_DRAFT && $status === Blog::STATUS_DRAFT) {
            $data['slug'] = $slug;
        }
        if ($blog->status === Blog::STATUS_DRAFT) {
            $data['slug'] = $blog->slug;
        }

        if ($title === $blog->title) {
            $blog->update($data);
        } else {
            try {
                $data['title'] = $title;
                $data['slug'] = $slug;
                $blog->update($data);
            }
            catch(Exception $ex) {
                // if value slug - duplicate
                if ($ex->getCode() === '23000') {
                    $data['slug'] = $slug . "-" . uniqid("", true);
                    $blog->update($data);
                }
            }
        }
        return $blog;
    }

    public function deleteBlog(Blog $blog): Blog {
        $blog->delete();
        return $blog;
    }

    public function publishDraft(string $title, string $description, Blog $blog): Blog {
        $data = [
            'title' => $title,
            'slug' => generateSlug($title, 500),
            'description' => $description,
            'status' => Blog::STATUS_MODERATION
        ];
        try {
            $blog->update($data);
        }
        catch(Exception $ex) {
            // if value slug - duplicate
            if ($ex->getCode() === '23000') {
                $data['slug'] = $data['slug'] . "-" . uniqid("", true);
                $blog->update($data);
            }
        }
        return $blog;
    }

    public function getAuthorBlog($authorId, $authId) {
        if (strval($authorId) === strval($authId)) {
           return [
                'user_id' => $authId,
                'project_id' => null
           ];
        } else {
            $projectId = Project::query()
                ->where('id', 'like', $authorId)
                ->whereIn('status',
                    [Project::STATUS_ACTIVE, Project::STATUS_MODERATION]
                )
                ->where('user_id', 'like', $authId)->select(['id'])
                ->first();
            if ($projectId) {
                return [
                    'user_id' => null,
                    'project_id' => $authorId
               ];
            }
            return [
                'user_id' => null,
                'project_id' => null
           ];
        }
    }


}
