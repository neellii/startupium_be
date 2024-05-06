<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Entity\Blog\Subject;
use App\Entity\Residence\Country;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class)->beforeEach(function () {
    $this->artisan('migrate')->assertOk();
    $this->artisan('passport:install')->assertOk();
    // Add Residence
    if (!Country::query()->whereNotNull('title')->first()) {
        $this->artisan('db:seed --class=CountryTableSeeder')->assertOk();
        $this->artisan('db:seed --class=RegionsTableSeeder')->assertOk();
        $this->artisan('db:seed --class=CityTableSeeder')->assertOk();
    }
    // Blog Subjects
    if (!Subject::query()->whereNotNull('title')->first()) {
        $this->artisan('db:seed --class=BlogSubjectTableSeeder')->assertOk();
    }

    $inna = [
        'id' => '101',
        'firstname' => 'Inna',
        'email' => 'inna@mail.ru',
        'password' => Hash::make("123456Az?"),
        'email_verified_at' => now()
    ];
    $andrey = [
        'id' => '102',
        'firstname' => 'Andrey',
        'lastname' => 'Kirkorov',
        'email' => 'andrey@mail.ru',
        'password' => Hash::make("123456Az?"),
        'email_verified_at' => now()
    ];

    //Create Auth user
    $this->inna = null;
    if (!$this->inna) {
        $this->inna = $this->findOrCreateUser($inna);
        $this->projectInna = $this->createProject($this->inna);
        $this->draftProjectInna = $this->createDraftProject($this->inna);
    }
    // create any user
    $this->andrey = null;
    if (!$this->andrey) {
        $this->andrey = $this->findOrCreateUser($andrey);
        $this->projectAndrey = $this->createProject($this->andrey);
        $this->draftProjectAndrey = $this->createDraftProject($this->andrey);
    }
    // Login Auth user
    $this->tokenInna = $this->login($this->inna);
    $this->tokenAndrey = $this->login($this->andrey);

})->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
