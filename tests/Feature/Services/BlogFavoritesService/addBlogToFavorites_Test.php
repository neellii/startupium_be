<?php

use App\Entity\Blog\Blog;
use App\UseCases\Blog\BlogFavoritesService;

beforeEach(function () {
    $this->data = [
        'title' => "Add blog to favorites",
        'description' => 'Awesome project blog, wow',
        'slug' => 'add-blog-to-favorites',
    ];
    $this->blog = Blog::query()->create($this->data);
    $this->service = new BlogFavoritesService();
});

afterEach(function() {
    $this->blog->delete();
});

// Tests
it('add blog to favorites first time', function() {
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeFalse();
    $this->service->addToFavorites($this->blog, $this->inna);
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeTrue();
});

it('add existing blog to favorites', function() {
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeFalse();
    $this->service->addToFavorites($this->blog, $this->inna);

    try {
        $this->service->addToFavorites($this->blog, $this->inna);
    }
    catch (Exception $ex) {
        expect($ex->getMessage())->toBe(config('constants.blog_already_in_favorites'));
    }
});

it('delete existing blog from favorites', function() {
    $this->service->addToFavorites($this->blog, $this->inna);
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeTrue();
    $this->service->deleteFromFavorites($this->blog, $this->inna);
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeFalse();
});

it('delete un-existing blog from favorites', function() {
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeFalse();
    try {
        $this->service->deleteFromFavorites($this->blog, $this->inna);
    }
    catch (Exception $ex) {
        expect($ex->getMessage())->toBe(config('constants.blog_already_out_favorites'));
    }
});
