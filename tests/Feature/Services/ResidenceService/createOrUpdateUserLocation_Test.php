<?php

use App\Entity\Residence\City;
use App\Entity\Residence\Country;
use App\Entity\Residence\Region;
use App\UseCases\Residence\ResidenceService;

beforeEach(function () {
    $this->service = new ResidenceService();
    $this->country1 = Country::query()->first();
    $this->region11 = Region::query()->where('country_id', $this->country1->id)->first();
    $this->region12 = Region::query()->where('country_id', $this->country1->id)->latest('id')->first();
    $this->city111 =  City::query()->where('region_id', $this->region11->id)->first();
    $this->city122 = City::query()->where('region_id', $this->region12->id)->first();

    $this->country2 = Country::query()->latest('id')->first();
    $this->region21 = Region::query()->where('country_id', $this->country2->id)->first();
    $this->region22 = Region::query()->where('country_id', $this->country2->id)->latest('id')->first();
    $this->city221 =  City::query()->where('region_id', $this->region21->id)->first();
    $this->city222 = City::query()->where('region_id', $this->region22->id)->first();
    $this->city22Null = City::query()->whereNull('region_id')->where('country_id', $this->country2->id)->first();
});

//  it('create user Location with new attributes', function () {
//     $this->service->createOrUpdateUserLocation($this->auth,
//         $this->country1->title, $this->region11->title, $this->city111->title,
//          $this->country1->id, $this->region11->id, $this->city111->id
//     );

//     $region = $this->auth->region()->first();
//     expect($region)->toBeInstanceOf(Region::class);
//     expect($region->id)->toBe($this->region11->id);
//     expect($region->title)->toBe($this->region11->title);

//     $country = $this->auth->country()->first();
//     expect($country)->toBeInstanceOf(Country::class);
//     expect($country->id)->toBe($this->country1->id);
//     expect($country->title)->toBe($this->country1->title);

//     $city = $this->auth->city()->first();
//     expect($city)->toBeInstanceOf(City::class);
//     expect($city->id)->toBe($this->city111->id);
//     expect($city->title)->toBe($this->city111->title);
// });

it('update user Location with country only', function () {
    $this->service->createOrUpdateUserLocation($this->inna,
        $this->country2->title, null, null,
        $this->country2->id, null, null
    );

    $city = $this->inna->city()->first();
    expect($city)->toBeNull();
    $region = $this->inna->region()->first();
    expect($region)->toBeNull();

    $country = $this->inna->country()->first();
    expect($country)->toBeInstanceOf(Country::class);
    expect($country->id)->toBe($this->country2->id);
    expect($country->title)->toBe($this->country2->title);
});

// it('update user Location with country, region=null, city', function () {
//     $this->service->createOrUpdateUserLocation($this->auth,
//     $this->country2->title, null, $this->city22Null->title,
//     $this->country2->id, null, $this->city22Null->id
// );

//     $city = $this->auth->city()->first();
//     expect($city)->toBeInstanceOf(City::class);
//     expect($city->title)->toBe($this->city22Null->title);
//     expect($city->id)->toBe($this->city22Null->id);

//     $region = $this->auth->region()->first();
//     expect($region)->toBeNull();

//     $country = $this->auth->country()->first();
//     expect($country)->toBeInstanceOf(Country::class);
//     expect($country->id)->toBe($this->country2->id);
//     expect($country->title)->toBe($this->country2->title);
// });

it('update user Location with country, region, city=null', function () {
    $this->service->createOrUpdateUserLocation($this->inna,
    $this->country2->title, $this->region22->title, null,
    $this->country2->id, $this->region22->id, null
);

    $city = $this->inna->city()->first();
    expect($city)->toBeNull();

    $region = $this->inna->region()->first();
    expect($region)->toBeInstanceOf(Region::class);
    expect($region->id)->toBe($this->region22->id);
    expect($region->title)->toBe($this->region22->title);

    $country = $this->inna->country()->first();
    expect($country)->toBeInstanceOf(Country::class);
    expect($country->id)->toBe($this->country2->id);
    expect($country->title)->toBe($this->country2->title);
});

// it('update user Location with country=null, region, city', function () {
//     $this->service->createOrUpdateUserLocation($this->auth,
//      null, $this->region22->title, $this->city222->title,
//      null, $this->region22->id, $this->city222->id,
//     );

//     $city = $this->auth->city()->first();
//     expect($city)->toBeNull();

//     $region = $this->auth->region()->first();
//     expect($region)->toBeNull();

//     $country = $this->auth->country()->first();
//     expect($country)->toBeNull();
// });

// it('update user Location with country and different region, city', function () {
//     $this->service->createOrUpdateUserLocation($this->auth,
//     $this->country1->title, $this->region22->title, $this->city222->title,
//     $this->country1->id, $this->region22->id, $this->city222->id,
// );

//     $city = $this->auth->city()->first();
//     expect($city)->toBeNull();

//     $region = $this->auth->region()->first();
//     expect($region)->toBeNull();

//     $country = $this->auth->country()->first();
//     expect($country)->toBeInstanceOf(Country::class);
//     expect($country->title)->toBe($this->country1->title);
//     expect($country->id)->toBe($this->country1->id);
// });

it('update user Location with unknown country', function () {
    $this->service->createOrUpdateUserLocation($this->inna, "dkfjgdgklfd", null, null, null, null, null);

    $city = $this->inna->city()->first();
    expect($city)->toBeNull();

    $region = $this->inna->region()->first();
    expect($region)->toBeNull();

    $country = $this->inna->country()->first();
    expect($country)->toBeNull();
});
