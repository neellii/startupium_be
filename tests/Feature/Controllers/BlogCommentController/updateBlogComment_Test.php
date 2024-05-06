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
        'comment' => "Add comment for update",
        'user_id' => $this->inna->id,
        'blog_id' => $this->blog->id,
    ];
    $this->comment = BlogComment::query()->create($this->commentData);
    $this->commentAData = [
        'comment' => "Add Andrey comment for update",
        'user_id' => $this->andrey->id,
        'blog_id' => $this->blog->id,
    ];
    $this->comment = BlogComment::query()->create($this->commentData);
    $this->commentA = BlogComment::query()->create($this->commentAData);

    $this->path = '/api/blog-comments/' . $this->comment->id;
    $this->pathA = '/api/blog-comments/' . $this->commentA->id;
    $this->result = $this->withHeader('Authorization', 'Bearer ' . $this->tokenInna);
});

afterEach(function() {
    $this->blog->delete();
});

it('Update blog comment - using BlogCommentController', function () {
    $newMessage = 'Hello world, blog comment';
    $response = $this->result->putJson($this->path, [
        'message' => $newMessage, 'commentId' => $this->comment->id
    ]);
    $response->assertStatus(200);
    expect($response['data']['id'])->toBe($this->comment->id);
    expect($response['data']['message'])->toBe($newMessage);
    expect($response['data']['blog']['id'])->toBe($this->blog->id);
    expect($response['data']['author']['id'])->toBe($this->inna->id);
});

it('Update not own blog comment - using BlogCommentController', function () {
    $newMessage = 'Hello world, blog comment';
    $response = $this->result->putJson($this->pathA, [
        'message' => $newMessage, 'commentId' => $this->commentA->id
    ]);
    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.comment_not_found'));
});
