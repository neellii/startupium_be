<?php

use App\Entity\Blog\Blog;
use App\UseCases\Blog\BlogService;

beforeEach(function () {
    $this->data = [
        'title' => "Very very cool blog",
        'description' => 'Awesome blog',
        'slug' => 'very-very-cool-blog',
        'status' => Blog::STATUS_MODERATION,
    ];
    $this->blog = Blog::query()->create($this->data);
    $this->service = new BlogService();
});

it('update blog', function() {
    $title = 'New very very cool blog';
    $description = 'New Awesome blog';
    $slug = 'dlkjfk s djf sldkjf ls';

    $updated = $this->service->updateBlog($title, $description, $slug, $this->blog);
    expect($updated->title)->toBe($title);
    expect($updated->slug)->toBe($slug);
    expect($updated->description)->toBe($description);

    $this->blog->delete();
});

it('update blog only description', function() {
    $description = 'New Awesome blog';

    $updated = $this->service->updateBlog($this->data['title'], $description, $this->data['slug'], $this->blog);
    expect($updated->title)->toBe($this->data['title']);
    expect($updated->slug)->toBe($this->data['slug']);
    expect($updated->description)->toBe($description);

    $this->blog->delete();
});

it('update blog with duplicate slug', function() {
    $data = [
        'title' => 'New very very cool blog',
        'description' => 'New Awesome blog',
        'slug' =>'duplicate-slug',
        'status' => Blog::STATUS_MODERATION,
    ];

    $duplicate = Blog::query()->create($data);
    $updated = $this->service->updateBlog("New bla bla", 'bla bla', $this->data['slug'], $duplicate);
    expect($updated->title)->toBe("New bla bla");
    expect($updated->slug)->toContain($this->data['slug']);
    expect($updated->description)->toBe('bla bla');

    $duplicate->delete();
    $this->blog->delete();
});

