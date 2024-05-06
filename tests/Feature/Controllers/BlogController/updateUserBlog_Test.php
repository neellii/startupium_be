<?php

use App\Entity\Blog\Blog;

beforeEach(function () {
    $this->dataInna = [
        'title' => "Inna blog",
        'description' => 'Awesome project blog',
        'slug' => 'inna-blog',
        'user_id' => $this->inna->id,
        'status' => Blog::STATUS_MODERATION,
        'subjects' => "[\"Qwerty Subject inna\"]",
        'author' => "{\"id\":" . $this->inna->id . ",\"title\":\"Inna \",\"slug\":\"\"}",
    ];
    $this->blogInna = Blog::query()->create($this->dataInna);

    $this->path = "/api/blogs";
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
});
afterEach(function() {
    $this->blogInna->delete();
});

it('update user blog - using BlogController', function() {
    $title = "New Title";
    $description = "New description";
    $subjects ="[\"Qwerty Subject Victor\"]";
    $author = "{\"id\":" . $this->inna->id . ",\"title\":\"Inna \",\"slug\":\"\"}";

    $response = $this->result->putJson(
        $this->path, [
            'author' => $author, 'slug' => $this->dataInna['slug'], 'title' => $title, 'description' => $description, 'subjects' => $subjects
        ]);
    $response->assertStatus(200);
    expect($response['data']['slug'])->toBe('new-title');
    expect($response['data']['title'])->toBe($title);
    expect($response['data']['description'])->toBe($description);
    expect($response['data']['user']['id'])->toBe($this->inna->id);
});

it('update other user blog - using BlogController', function() {
    $title = "New Title";
    $description = "New description";
    $subjects ="[\"Qwerty Subject Victor\"]";
    $author = "{\"id\":" . $this->andrey->id . ",\"title\":\"ProjectInna \",\"slug\":\"\"}";

    $response = $this->result->putJson($this->path, [
        'author' => $author, 'slug' => $this->dataInna['slug'], 'title' => $title, 'description' => $description, 'subjects' => $subjects
    ]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.content_not_found'));
});

it('update author in user blog - using BlogController', function() {
    $title = "New Title";
    $description = "New description";
    $subjects ="[\"Qwerty Subject Victor\"]";
    $author = "{\"id\":" . $this->projectInna->id . ",\"title\":\"ProjectInna \",\"slug\":\"\"}";

    $response = $this->result->putJson($this->path, [
        'author' => $author, 'slug' => $this->dataInna['slug'], 'title' => $title, 'description' => $description, 'subjects' => $subjects
    ]);
    $response->assertStatus(200);
    expect($response['data']['slug'])->toBe('new-title');
    expect($response['data']['title'])->toBe($title);
    expect($response['data']['description'])->toBe($description);
    expect($response['data']['user']['id'])->toBeNull();
    expect($response['data']['project']['id'])->toBe($this->projectInna->id);
});
