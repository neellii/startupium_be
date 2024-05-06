<?php

use App\Entity\Blog\Blog;
use App\Entity\Comment\BlogComment;

beforeEach(function () {
    $this->data = [
        'title' => "Add comment reply",
        'description' => 'Awesome  blog, wow',
        'slug' => 'add-comment-reply',
    ];
    $this->blog = Blog::query()->create($this->data);
    $this->commentData = [
        'comment' => "Add comment reply",
        'user_id' => $this->inna->id,
        'blog_id' => $this->blog->id,
    ];
    $this->comment = BlogComment::query()->create($this->commentData);

    $this->path = '/api/blog-comments/' . $this->comment->id . '/replies';
    $this->pathWrong = '/api/blog-comments/asd/replies';
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
});

afterEach(function() {
    $this->blog->delete();
});

it('Create comment reply - using BlogCommentController', function () {
    $message = 'How Are you?';
    $response = $this->result->postJson($this->path, [
        'message' => $message, 'commentId' => $this->comment->id
    ]);
    $response->assertStatus(201);
    expect($response['data']['blog']['id'])->toBe($this->blog->id);
    expect($response['data']['parent']['id'])->toBe($this->comment->id);
    expect($response['data']['total'])->toBe(2);
    expect($response['data']['message'])->toBe($message);
    expect($response['data']['author']['id'])->toBe($this->inna->id);
});

it('Create comment reply to un-existing comment - using BlogCommentController', function () {
    $message = 'How Are you?';
    $response = $this->result->postJson($this->pathWrong, [
        'message' => $message, 'commentId' => 'asd'
    ]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.comment_not_found'));

});
