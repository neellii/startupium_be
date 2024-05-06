<?php

use App\Entity\Blog\Blog;

beforeEach(function () {
    $this->dataInna = [
        'title' => "Inna blog user delete",
        'description' => 'Awesome project blog user delete',
        'slug' => 'inna-blog-user-delete',
        'user_id' => $this->inna->id,
        'subjects' => "[\"Qwerty Subject inna\",\"Hello Subject inna\"]"
    ];
    $this->dataAndrey = [
        'title' => "Andrey blog user delete",
        'description' => 'Awesome project blog user delete',
        'slug' => 'andrey-blog-user-delete',
        'user_id' => $this->andrey->id,
        'subjects' => "[\"Qwerty Subject inna\",\"Hello Subject inna\"]"
    ];
    $this->blogInna = Blog::query()->create($this->dataInna);
    $this->blogAndrey = Blog::query()->create($this->dataAndrey);

    $this->pathInna = '/api/users/' . $this->inna->id . '/blogs' . "/" . $this->dataInna['slug'];
    $this->pathAndrey = '/api/users/' . $this->andrey->id . '/blogs' . "/" . $this->dataAndrey['slug'];
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
});

it('delete user blog - using BlogController', function() {
    $response = $this->result->deleteJson($this->pathInna);

    $response->assertStatus(200);
    expect($response['data']['slug'])->toBe($this->dataInna['slug']);
    expect($response['data']['title'])->toBe($this->dataInna['title']);
    expect($response['data']['description'])->toBe($this->dataInna['description']);
    //expect(count($response['data']['subjects']))->toBe(0);

    $deleted = Blog::query()->where('id', 'like', $response['data']['id'])->first();
    expect($deleted)->toBeNull();

    $this->blogAndrey->delete();
});

it('delete any user blog - using BlogController', function() {
    $response = $this->result->deleteJson($this->pathAndrey);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.user_not_found'));

    $this->blogInna->delete();
    $this->blogAndrey->delete();
});

it('delete own non-existent user blog - using BlogController', function() {
    $path = '/api/users/' . $this->inna->id . '/blogs' . "/" . 'qqqqq';
    $response = $this->result->deleteJson($path);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.content_not_found'));

    $this->blogInna->delete();
    $this->blogAndrey->delete();
});

it('delete user blog at unauthenticated user - using BlogController', function() {
    $response = $this->withHeader('Authorization', 'Bearer ')->deleteJson($this->pathInna);

    $response->assertStatus(401);
    expect($response['message'])->toBe(config('constants.unauthenticated'));

    $this->blogInna->delete();
    $this->blogAndrey->delete();
});
