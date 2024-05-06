<?php

use App\Entity\Blog\Blog;

beforeEach(function () {
    $this->data = [
        'title' => "Delete existing blog to favorite",
        'description' => 'Awesome project blog',
        'slug' => 'delete-existing-blog-to-favorite',
    ];
    $this->blog = Blog::query()->create($this->data);
    $this->path = '/api/blogs/favorite';
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
    $this->inna->addToBlogFavorites($this->blog->id);
});

afterEach(function() {
    $this->blog->delete();
});

it('Delete any slug From Favorite', function () {
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeTrue();
    $response = $this->result->deleteJson($this->path, ['slug' => $this->data['slug']]);
    $response->assertStatus(200);
    expect($response['data']['id'])->toBe($this->blog->id);
    expect($response['data']['slug'])->toBe($this->data['slug']);
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeFalse();
});

it('Delete un-existing slug To Favorite', function () {
    $response = $this->result->deleteJson($this->path, ['slug' => "qqqqq-qqqqq-wwwww"]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.content_not_found'));
});

it('Delete any slug To Favorite at unauthenticated user', function () {
    $response = $this->withHeader('Authorization', 'Bearer ')->deleteJson(
        $this->path, ['slug' => $this->data['slug']]
    );
    $response->assertStatus(401);
    expect($response['message'])->toBe(config('constants.unauthenticated'));
});

it('Delete existing slug To Favorite one more', function () {
    $response = $this->result->deleteJson($this->path, ['slug' => $this->data['slug']]);
    $response->assertStatus(200);
    $response = $this->result->deleteJson($this->path, ['slug' => $this->data['slug']]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.blog_already_out_favorites'));

});


