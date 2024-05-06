<?php

use App\Entity\Blog\Blog;

beforeEach(function () {
    $this->dataInna = [
        'title' => "Inna blog delete",
        'description' => 'Awesome project blog delete',
        'slug' => 'inna-blog-delete',
        'project_id' => $this->projectInna->id,
        'subjects' => "[\"Qwerty Subject inna\",\"Hello Subject inna\"]"
    ];
    $this->dataAndrey = [
        'title' => "Andrey blog delete",
        'description' => 'Awesome project blog delete',
        'slug' => 'andrey-blog-delete',
        'project_id' => $this->projectAndrey->id,
        'subjects' => "[\"Qwerty Subject inna\",\"Hello Subject inna\"]"
    ];
    $this->blogInna = Blog::query()->create($this->dataInna);
    $this->blogAndrey = Blog::query()->create($this->dataAndrey);

    $this->pathInna = '/api/projects/' . $this->projectInna->id . '/blogs' . "/" . $this->dataInna['slug'];
    $this->pathAndrey = '/api/projects/' . $this->projectAndrey->id . '/blogs' . "/" . $this->dataAndrey['slug'];
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
});

it('delete own project blog - using BlogController', function() {
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

it('delete any project blog - using BlogController', function() {
    $response = $this->result->deleteJson($this->pathAndrey);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.project_not_found'));

    $this->blogInna->delete();
    $this->blogAndrey->delete();
});

it('delete own non-existent project blog - using BlogController', function() {
    $path = '/api/projects/' . $this->projectInna->id . '/blogs' . "/" . 'qqqqq';
    $response = $this->result->deleteJson($path);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.content_not_found'));

    $this->blogInna->delete();
    $this->blogAndrey->delete();
});

it('delete project blog at unauthenticated user - using BlogController', function() {
    $response = $this->withHeader('Authorization', 'Bearer ')->deleteJson($this->pathInna);

    $response->assertStatus(401);
    expect($response['message'])->toBe(config('constants.unauthenticated'));

    $this->blogInna->delete();
    $this->blogAndrey->delete();
});
