<?php

beforeEach(function () {
    $this->result = $this->withHeader("Accept", 'application/json');
    $this->path = '/api/blogs';

});

afterEach(function() {
    //
});

it('get_blogs_using_BlogController', function() {
    $response = $this->result->getJson($this->path);
    $response->assertStatus(200);
});
