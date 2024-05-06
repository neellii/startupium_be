<?php

beforeEach(function () {
    $this->result = $this->withHeader("Accept", 'application/json');
    $this->path = '/api/users' . '/' . $this->inna->id . '/blogs';

});

afterEach(function() {
    //
});

it('get_user_blogs_using_BlogController', function() {
    $response = $this->result->getJson($this->path);
    $response->assertStatus(200);
});
