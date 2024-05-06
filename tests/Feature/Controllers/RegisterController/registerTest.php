<?php

use App\Entity\User\User;

beforeEach(function () {
    $data = [
        'id' => 55,
        'firstname' => "Max",
        'lastname' => 'Filipov',
        'email' => 'max@mail.ru',
        'password' => '123456Az?',
        'verification_code' => sha1(time()),
        'password_confirmation' => '123456Az?'
    ];
    $user = User::query()->where('id', 'like', $data['id'])->first();
    $this->data = $data;
    $this->user = $user;
});

it('registering new user or user already exists', function () {
    $user = $this->user;
    if ($user?->id !== $this->data['id']) {
        $response = $this->post('/api/register', $this->data);
        $result = $response['data'];
        expect($result['success'])->toBeBool(true);
        expect($result['email'])->toBe($this->data['email']);
        expect($result['emailVerified'])->toBeBool(false);
        expect($result['message'])->toBe(config('constants.account_successfully_created'));
    }
});

it('registering an existing user', function () {
    $response = $this->post('/api/register', $this->data);
    expect($response->exception->status)->toBe(422);
    expect($response->exception->getMessage())->toBe("Такое значение поля E-Mail адрес уже существует.");
});
