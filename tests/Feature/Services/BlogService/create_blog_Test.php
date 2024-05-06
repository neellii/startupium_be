<?php

use App\Entity\Blog\Blog;
use App\UseCases\Blog\BlogService;

beforeEach(function () {
    $this->service = new BlogService();
});

it('create blog no project, no user', function() {
    $result = $this->service->createBlog("Hello", "andreY");
    expect($result->title)->toBe("Hello");
    expect($result->description)->toBe("andreY");
    expect($result->slug)->toBeEmpty();
    expect($result->user_id)->toBeEmpty();
    expect($result->project_id)->toBeEmpty();
    expect($result->status)->toBe(Blog::STATUS_MODERATION);

    $result->delete();
});

it('create blog - no slug, project, user', function() {
    $result = $this->service->createBlog("Hello", "andreY", null, $this->projectInna?->id, $this->inna?->id);
    expect($result->title)->toBe("Hello");
    expect($result->description)->toBe("andreY");
    expect($result->slug)->toBeEmpty();
    expect($result->user_id)->toBe($this->inna->id);
    expect($result->project_id)->toBe($this->projectInna->id);
    expect($result->status)->toBe(Blog::STATUS_MODERATION);

    $result->delete();
});

it('create blog - slug, project, user', function() {
    $result = $this->service->createBlog("Hello wOrld", "andreY", "hello-world", $this->projectInna?->id, $this->inna?->id);
    expect($result->title)->toBe("Hello wOrld");
    expect($result->description)->toBe("andreY");
    expect($result->slug)->toBe("hello-world");
    expect($result->user_id)->toBe($this->inna->id);
    expect($result->project_id)->toBe($this->projectInna->id);
    expect($result->status)->toBe(Blog::STATUS_MODERATION);

    $result->delete();
});

it('create blog - with duplicate entry slug', function() {
    $result = $this->service->createBlog("Hello wOrld", "andreY", "hello-world", $this->projectInna?->id, $this->inna?->id);
    $duplicate = $this->service->createBlog("Hello wOrld", "andreY", "hello-world", $this->projectInna?->id, $this->inna?->id);
    expect($result->title)->toBe($duplicate->title);
    expect($result->description)->toBe($duplicate->description);
    expect($duplicate->slug)->toContain($result->slug);
    expect($result->user_id)->toBe($duplicate->user_id);
    expect($result->project_id)->toBe($duplicate->project_id);
    expect($result->status)->toBe($duplicate->status);

    $result->delete();
    $duplicate->delete();
});

it('create draft blog', function() {
    $result = $this->service->createBlog("Hello", "andreY", null, null, null, Blog::STATUS_DRAFT);
    expect($result->description)->toBe("andreY");
    expect($result->slug)->toBeEmpty();
    expect($result->user_id)->toBeEmpty();
    expect($result->project_id)->toBeEmpty();
    expect($result->status)->toBe(Blog::STATUS_DRAFT);
    $result->delete();
});
