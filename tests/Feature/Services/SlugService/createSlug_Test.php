<?php

use App\UseCases\Slug\SlugService;

beforeEach(function () {
    $this->service = new SlugService();
});

it('create Slug', function() {
    $slug = $this->service->generate("Если при проверке Slug он уже есть в базе, то выводится ошибка валидации.");
    expect($slug)->toBeString();
});

it('create Slug 10', function() {
    $slug = $this->service->generate("Если при проверке Slug он уже есть в базе, то выводится ошибка валидации.", 10);
    expect($slug)->toBeString();
    expect(strlen($slug))->toBe(10);
});
