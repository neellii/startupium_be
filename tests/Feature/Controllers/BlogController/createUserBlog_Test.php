<?php

beforeEach(function () {
   $this->pathRight = '/api/blogs';
   $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
   $this->subjects = "[\"Qwerty User Subject\"]";
   $this->author = "{\"id\":" . $this->inna->id . ",\"title\":\"Inna \",\"slug\":\"\"}";
});

it('create user blog - using BlogController', function() {
    $title = 'Auth User Title';
    $description = 'Auth User Description';
    $response = $this->result->
        postJson($this->pathRight, [
            'author' => $this->author ,'title' => $title, 'description' => $description, 'subjects' => $this->subjects
        ]);

    $response->assertStatus(200);
    expect($response['data']['slug'])->toBeString();
    expect($response['data']['title'])->toBe($title);
    expect($response['data']['description'])->toBe($description);
    expect($response['data']['user']['id'])->toBe($this->inna->id);
});

it('create user blog with wrong json subjects - using BlogController', function() {
    $title = 'Auth User Title';
    $description = 'Auth User Description';
    $response = $this->result->
        postJson($this->pathRight, [
            'author' => $this->author, 'title' => $title, 'description' => $description, 'subjects' => "Hello"
        ]);

    $response->assertStatus(422);
    expect($response['message'])->toBe('Поле subjects обязательно для заполнения.');
});

it('create user blog no title - using BlogController', function() {
    $description = 'Auth User Description';
    $response = $this->result->
        postJson($this->pathRight, [
            'author' => $this->author , 'description' => $description, 'subjects' => $this->subjects
        ]);

    $response->assertStatus(422);
    expect($response['message'])->toBe('Поле Название обязательно для заполнения. (and 1 more error)');
});

it('create user blog no description - using BlogController', function() {
    $title = 'Auth User Title';
    $response = $this->result->
        postJson($this->pathRight, [
            'author' => $this->author ,'title' => $title, 'subjects' => $this->subjects
        ]);

    $response->assertStatus(422);
    expect($response['message'])->toBe('Поле Описание обязательно для заполнения.');
});
