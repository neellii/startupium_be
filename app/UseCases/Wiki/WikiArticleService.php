<?php
namespace App\UseCases\Wiki;

use DomainException;
use Illuminate\Http\Request;
use App\Entity\Project\Project;
use App\Entity\Wiki\WikiArticle;
use App\Entity\Wiki\WikiSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Gate;

class WikiArticleService
{
    public function getArticles(string $project_id, string $section_id = "") {
        /** @var Project $project */
        $project = findAuthProject($project_id);
        $query = WikiArticle::query()
            ->where('project_id', $project->id);
        $section_id != null ?
            $query->where('section_id', $section_id) :
            $query->whereNull('section_id');
        return $query->orderByDesc('created_at')->get();
    }

    public function getGroupedArticles(Project $project) {
        return $project->wikiArticles()
            ->where('project_id', $project?->id)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('section_id');

    }

    public function getArticlesBySection(Request $request, string $project_id) {
        $title = $request['section'];
        /** @var Project $project */
        $project = findProjectWithSubscriber($project_id);
        $articles = WikiArticle::query();
        if ($title) {
            $section = WikiSection::query()
                ->where('title', $title)
                ->where('project_id', $project->id);
            $articles->joinSub($section, 'section', function (JoinClause $join) {
                $join->on('wiki_articles.section_id', '=', 'section.id');
            })->selectRaw('wiki_articles.*');
        } else {
            $articles->where('project_id', $project->id);
            $articles->whereNull('section_id');
        }
        return $articles->orderByDesc('created_at')->get();
    }

    public function getArticleDefault(Request $request, string $project_id): WikiArticle | null {
        $articleId = $request['article'] ?? "";
        $project = findPivotProjectWithSubscriber($project_id);
        $article = "";
        if ($articleId) {
            $article = $this->findArticleById($articleId, $project);
        } else {
            $article = $project->defaultArticle()->wherePivot('project_id', 'like', $project->id)->first();
        }
        return $article;
    }

    public function createArticle(Request $request, string $project_id): WikiArticle {
        $title = $request['title'];
        $text = $request['text'];
        $hasDefault = $request['hasDefault'];

        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('create-article', $project?->pivot?->role_id);
        $section = $this->findSectionById($request['sectionId'] ?? "", $project);

        $_article = $this->findArticleByTitle($title, $project, $section);
        if ($_article) {
            throw new DomainException(config('constants.wiki_article_already_exists'));
        }

        return DB::transaction(function () use ($project, $title, $text, $section, $hasDefault) {
            $article = WikiArticle::query()->make([
                'title' => $title,
                'text' => $text,
            ]);
            $article->project()->associate($project);
            $article?->wikiSection()->associate($section);
            $article->saveOrFail();
            if ($hasDefault) {
                $project->addToDefaultArticle($article?->id);
            }
            return $article;
        });
    }

    public function createArticleCopy(Request $request, string $project_id): WikiArticle {
        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('create-article', $project?->pivot?->role_id);
        $article = $this->findArticleById($request['articleId'], $project);
        $section = $this->findSectionById($request['sectionId'] ?? "", $project);
        return DB::transaction(function () use ($project, $article, $section) {
            $article->update([
                'number_of_copies' => $article->number_of_copies + 1,
            ]);
            $article = WikiArticle::query()->make([
                'title' => $article?->title . $article->number_of_copies,
                'text' => $article?->text,
            ]);
            $article->project()->associate($project);
            $article?->wikiSection()->associate($section);
            $article->saveOrFail();
            return $article;
        });
    }

    public function createDefaultArticle(Request $request, string $project_id): WikiArticle {
        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('create-article', $project?->pivot?->role_id);
        $article = $this->findArticleById($request['articleId'], $project);

        $project->addToDefaultArticle($article->id);
        return $article;
    }

    public function deleteArticle(Request $request, string $project_id): WikiArticle {
        $id = $request['articleId'] ?? "";
        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('delete-article', $project?->pivot?->role_id);
        $article = $this->findArticleById($id, $project);
        $article->delete();
        return $article;
    }

    public function updateArticle(Request $request, string $project_id) {
        $id = $request['articleId'];
        $title = $request['title'];
        $text = $request['text'];
        $sectionId = $request['sectionId'] ?? "";
        $hasDefault = $request['hasDefault'];

        $project = findPivotProjectWithSubscriber($project_id);
        Gate::authorize('edit-article', $project?->pivot?->role_id);
        $section = $this->findSectionById($sectionId, $project);

        $article = $this->findArticleById($id, $project);
        return DB::transaction(function () use ($title, $text, $section, $article, $hasDefault, $project) {
            if ($hasDefault) {
                $project->addToDefaultArticle($article?->id);
            } else {
                if ($article->hasDefaultArticle()) {
                    $project->removeFromDefaultArticle();
                }
            }
            $article?->wikiSection()->associate($section);
            $article->update([
                'title' => $title,
                'text' => $text,
            ]);
            return $article;
        });
    }

    private function findArticleById(string $id, $project) {
        $article = $project->wikiArticles()
            ->where('id', 'like', $id)
            ->where('project_id', 'like', $project->id)
            ->first();
        if (!$article) {
            throw new DomainException(config('constants.wiki_article_not_found'));
        }
        return $article;
    }

    private function findSectionById(string $id, $project) {
        $section = $project->wikiSections()
            ->where('id', 'like', $id)
            ->where('project_id', 'like', $project?->id)
            ->first();
        return $section;
    }

    private function findArticleByTitle(string $title = "", Project $project, WikiSection | null $section) {
        $article = "";
        if ($section) {
            $article = $project->wikiArticles()
                ->where('title', 'like', $title)
                ->where('project_id', 'like', $project?->id)
                ->where('section_id', 'like', $section?->id)
                ->first();
        } else {
            $article = $project->wikiArticles()
                ->where('title', 'like', $title)
                ->where('project_id', 'like', $project?->id)
                ->whereNull('section_id')
                ->first();
        }
        return $article;
    }
}
