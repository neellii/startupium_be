<?php

use App\Entity\Blog\Blog;
use App\Entity\Comment\BlogComment;

beforeEach(function () {
    $this->data = [
        'title' => "Delete comment reply",
        'description' => 'Awesome  blog, wow',
        'slug' => 'delete-comment-reply',
    ];
    $this->blog = Blog::query()->create($this->data);
    $this->commentData = [
        'comment' => "Delete comment for update",
        'user_id' => $this->inna->id,
        'blog_id' => $this->blog->id,
    ];
    $this->comment = BlogComment::query()->create($this->commentData);
    $this->commentAData = [
        'comment' => "Delete Andrey comment for update",
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

it('Delete blog comment - using BlogCommentController', function () {
    $comment = BlogComment::query()->where('id', 'like', $this->comment->id)->first();
    expect($comment->user_id)->toBe($this->comment->user_id);
    expect($comment->id)->toBe($this->comment->id);

    $response = $this->result->deleteJson($this->path, [
        'commentId' => $this->comment->id
    ]);
    expect($response['data']['id'])->toBe($this->comment->id);
    expect($response['data']['message'])->toBe($this->commentData['comment']);
    expect($response['data']['blog']['id'])->toBe($this->blog->id);
    expect($response['data']['author']['id'])->toBe($this->inna->id);

    $comment = BlogComment::query()->where('id', 'like', $this->comment->id)->first();
    expect($comment?->id)->toBeNull();
});

it('Delete not own blog comment - using BlogCommentController', function () {
    $commentA = BlogComment::query()->where('id', 'like', $this->commentA->id)->first();
    expect($commentA->user_id)->toBe($this->commentA->user_id);
    expect($commentA?->id)->toBe($this->commentA->id);


    $newMessage = 'Hello world, blog comment';
    $response = $this->result->deleteJson($this->pathA, [
        'message' => $newMessage, 'commentId' => $this->commentA->id
    ]);

    $response->assertStatus(400);
    expect($response['message'])->toBe(config('constants.comment_not_found'));

    $commentA = BlogComment::query()->where('id', 'like', $this->commentA->id)->first();
    expect($commentA->user_id)->toBe($this->commentA->user_id);
    expect($commentA?->id)->toBe($this->commentA->id);
});
