<?php

use App\Entity\Blog\Blog;
use App\Entity\Blog\Subject;

beforeEach(function () {
    $this->data1 = [
        'id' => 'Create-with-id',
        'title' => "Create new blog 1",
        'description' => 'Awesome project blog',
        'slug' => 'create-new-blog-1',
        'user_id' => $this->inna->id,
    ];
    $this->blog1 = Blog::query()->create($this->data1);
    // favorites
    $this->inna->addToBlogFavorites($this->blog1);
    $this->andrey->addToBlogFavorites($this->blog1);
    // subject
    $this->subject = Subject::query()->latest("id")->first();
    $this->blog1->subjects()->attach($this->subject);

    $this->result = $this->withHeader("Accept", 'application/json');
    $this->path = '/api/users/' . $this->inna->id . '/blogs' . '/' . $this->data1['slug'];

});

afterEach(function() {
    $this->blog1->delete();
});

it('get_user_blog_using_BlogController', function() {
    $response = $this->result->getJson($this->path);
    $response->assertStatus(200);
    expect($response['data']['id'])->toBe($this->data1['id']);
    expect($response['data']['title'])->toBe($this->data1['title']);
    expect($response['data']['description'])->toBe($this->data1['description']);
    expect($response['data']['slug'])->toBe($this->data1['slug']);
    expect($response['data']['user']['id'])->toBe($this->inna->id);
    expect($response['data']['user']['firstname'])->toBe($this->inna->firstname);
    expect($response['data']['user']['lastname'])->toBe("");
    expect($response['data']['user']['avatarUrl'])->toBeNull();
    expect($response['data']['commentsCount'])->toBe(0);
    expect($response['data']['favoritesCount'])->toBe(2);
    expect($response['data']['subject'])->toBe($this->subject?->title);
});
