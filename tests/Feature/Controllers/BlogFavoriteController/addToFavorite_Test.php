<?php

use App\Entity\Blog\Blog;

beforeEach(function () {
    $this->data = [
        'title' => "Add existing blog to favorite",
        'description' => 'Awesome project blog',
        'slug' => 'add-existing-blog-to-favorite',
        'subjects' => "[\"Qwerty Subject inna\",\"Hello Subject inna\"]"
    ];
    $this->blog = Blog::query()->create($this->data);
    $this->path = '/api/blogs/favorite';
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
});

afterEach(function() {
    $this->blog->delete();
});

it('Add any slug To Favorite', function () {
    $response = $this->result->postJson($this->path, ['slug' => $this->data['slug']]);
    $response->assertStatus(200);
    expect($response['data']['id'])->toBe($this->blog->id);
    expect($response['data']['slug'])->toBe($this->data['slug']);
    expect($this->inna->hasInBlogFavorites($this->blog->id))->toBeTrue();
});

it('Add un-existing slug To Favorite', function () {
    $response = $this->result->postJson($this->path, ['slug' => "qqqqq-qqqqq-wwwww"]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.content_not_found'));
});

it('Add any slug To Favorite at unauthenticated user', function () {
    $response = $this->withHeader('Authorization', 'Bearer ')->postJson(
        $this->path, ['slug' => $this->data['slug']]
    );
    $response->assertStatus(401);
    expect($response['message'])->toBe(config('constants.unauthenticated'));
});

it('Add existing slug To Favorite one more', function () {
    $response = $this->result->postJson($this->path, ['slug' => $this->data['slug']]);
    $response->assertStatus(200);
    $response = $this->result->postJson($this->path, ['slug' => $this->data['slug']]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.blog_already_in_favorites'));

});


