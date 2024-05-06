<?php

beforeEach(function () {
    $this->pathRight = '/api/blogs';
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
    $this->title = 'Auth Project Title';
    $this->description = 'Auth Project Description';
    $this->subjects = "[\"Qwerty Subject\"]";
    $this->author = "{\"id\":" . $this->projectInna->id . ",\"title\":\"ProjectInna \",\"slug\":\"\"}";
});

it('create project blog - using BlogController', function() {
    $response = $this->result->
        postJson($this->pathRight, [
            'author' => $this->author, 'title' => $this->title, 'description' => $this->description, 'subjects' => $this->subjects
        ]);

    $response->assertStatus(200);
    expect($response['data']['slug'])->toBeString();
    expect($response['data']['title'])->toBe($this->title);
    expect($response['data']['description'])->toBe($this->description);
    expect($response['data']['project']['id'])->toBe($this->projectInna->id);
});

it('create project blog with empty subjects - using BlogController', function() {
    $response = $this->result->
        postJson($this->pathRight, [
            'author' => $this->author, 'title' => $this->title, 'description' => $this->description
        ]);

    $response->assertStatus(422);
    expect($response['message'])->toBe('Поле subjects обязательно для заполнения.');
});

it('create project blog no title - using BlogController', function() {
    $response = $this->result->
    postJson($this->pathRight, [
        'author' => $this->author, 'description' => $this->description, 'subjects' => $this->subjects
    ]);

    $response->assertStatus(422);
    expect($response['message'])->toBe('Поле Название обязательно для заполнения. (and 1 more error)');
});

it('create project blog no description - using BlogController', function() {
    $response = $this->result->
    postJson($this->pathRight, [
        'author' => $this->author, 'title' => $this->title, 'subjects' => $this->subjects
    ]);

    $response->assertStatus(422);
    expect($response['message'])->toBe('Поле Описание обязательно для заполнения.');
});
