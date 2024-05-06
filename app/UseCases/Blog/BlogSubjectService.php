<?php

namespace App\UseCases\Blog;

use App\Entity\Blog\Blog;
use App\Entity\Blog\Subject;

class BlogSubjectService {

    // Тематика для блога
    public function createUpdateSubjects($subjects = [], Blog $blog): void {
        $blog->subjects()->detach();
        $this->saveSubjects($subjects, $blog);
    }

    // Тематика блога, новые позиции сохраняю
    private function saveSubjects($tags, Blog $blog): void
    {
        foreach ($tags as $title) {
            // ищу или создаю позицию
            $tag = Subject::query()->where("title", 'like', $title ?? "")->first();
            if (!$tag) {
                $tag = Subject::create(['title' => $title, 'status' => Subject::STATUS_MODERATION]);
            }
            // сохраняю
            $blog->subjects()->attach($tag);
        }
    }

}
