<?php

use App\Entity\Blog\Blog;

beforeEach(function () {
    $this->data = [
        'title' => "Add comment to blog",
        'description' => 'Awesome  blog, wow',
        'slug' => 'add-comment-to-blog',
    ];
    $this->blog = Blog::query()->create($this->data);
    $this->path = '/api/blog-comments/' . $this->data['slug'];
    $this->pathWrong = '/api/blog-comments/sdsds-sdsdsds-sdsdsd';
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
});

afterEach(function() {
    $this->blog->delete();
});

it('Create comment to existing blog - using BlogCommentController', function () {
    $message = 'How Are you?';
    $response = $this->result->postJson($this->path, [
        'message' => $message, 'slug' => $this->data['slug']
    ]);
    $response->assertStatus(201);
    expect($response['data']['blog']['id'])->toBe($this->blog->id);
    expect($response['data']['parent'])->toBeNull();
    expect($response['data']['total'])->toBe(1);
    expect($response['data']['message'])->toBe($message);
    expect($response['data']['author']['id'])->toBe($this->inna->id);
});

it('Create comment to un-existing blog - using BlogCommentController', function () {
    $message = 'How Are you?';
    $response = $this->result->postJson($this->pathWrong, [
        'message' => $message, 'slug' => $this->data['slug']
    ]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.content_not_found'));
});

it('Create comment to blog without message - using BlogCommentController', function () {
    $response = $this->result->postJson($this->path, [
        'slug' => $this->data['slug']
    ]);
    $response->assertStatus(422);
    expect($response['message'])->toBe(config('constants.transmit_incorrect_data'));
});

it('Create comment to blog without slug - using BlogCommentController', function () {
    $message = 'How Are you?';
    $response = $this->result->postJson($this->path, [
        'message' => $message
    ]);
    $response->assertStatus(422);
    expect($response['message'])->toBe(config('constants.transmit_incorrect_data'));
});
