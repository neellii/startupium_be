<?php

use App\Entity\Blog\Blog;
use App\UseCases\Blog\BlogSubjectService;

beforeEach(function () {
    $this->data = [
        'title' => "Beautiful blog",
        'description' => 'Awesome project blog, wow',
        'slug' => 'beautiful-blog',
    ];
    $this->service = new BlogSubjectService();
    $this->subjects = ['Hello World', 'How Are You?'];
    $this->blog = Blog::query()->create($this->data);
});

afterEach(function() {
    $this->blog->delete();
});

// Tests
it('create blog subjects', function() {
    $this->service->createUpdateSubjects($this->subjects, $this->blog);

    $subjects = $this->blog->subjects()->get();
    expect(count($subjects))->toBe(count($this->subjects));
});

