<?php

use App\Entity\Blog\Blog;
use App\Entity\Blog\Subject;

beforeEach(function () {
    $this->data1 = [
        'id' => 'Create-with-id-project',
        'title' => "Create new blog project",
        'description' => 'Awesome project blog',
        'slug' => 'create-new-blog-id-project',
        'project_id' => $this->projectInna->id,
    ];
    $this->blog1 = Blog::query()->create($this->data1);
    // favorites
    $this->inna->addToBlogFavorites($this->blog1);
    $this->andrey->addToBlogFavorites($this->blog1);
    // subject
    $this->subject = Subject::query()->latest("id")->first();
    $this->blog1->subjects()->attach($this->subject);

    $this->result = $this->withHeader("Accept", 'application/json');
    $this->path = '/api/projects/' . $this->projectInna->id . '/blogs' . '/' . $this->data1['slug'];

});

afterEach(function() {
    $this->blog1->delete();
});

it('get_project_blog_using_BlogController', function() {
    $response = $this->result->getJson($this->path);
    $response->assertStatus(200);
    expect($response['data']['id'])->toBe($this->data1['id']);
    expect($response['data']['title'])->toBe($this->data1['title']);
    expect($response['data']['description'])->toBe($this->data1['description']);
    expect($response['data']['slug'])->toBe($this->data1['slug']);
    expect($response['data']['project']['id'])->toBe($this->projectInna->id);
    expect($response['data']['project']['title'])->toBe($this->projectInna->title);
    expect($response['data']['project']['slug'])->toBe($this->projectInna->slug);
    expect($response['data']['commentsCount'])->toBe(0);
    expect($response['data']['favoritesCount'])->toBe(2);
    expect($response['data']['subject'])->toBe($this->subject?->title);
});
