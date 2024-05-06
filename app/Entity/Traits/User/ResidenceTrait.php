<?php

namespace App\Entity\Traits\User;

use App\Entity\Residence\City;
use App\Entity\Residence\Country;
use App\Entity\Residence\Region;

trait ResidenceTrait {

    public function city()
    {
        return $this->belongsToMany(City::class, 'user_residence_ref');
    }

    public function region()
    {
        return $this->belongsToMany(Region::class, 'user_residence_ref');
    }

    public function country()
    {
        return $this->belongsToMany(Country::class, 'user_residence_ref')
            ->withPivot(['city_id', 'region_id']);
    }

    public function updateCity($cur_city_id, $new_city_id): void
    {
        $this->city()->updateExistingPivot($cur_city_id, ['city_id' => $new_city_id]);
    }

    public function updateCountry($cur_country_id, $new_country_id): void
    {
        $this->country()->updateExistingPivot($cur_country_id, ['country_id' => $new_country_id]);
    }

    public function updateRegion($cur_region_id, $new_region_id): void
    {
        $this->region()->updateExistingPivot($cur_region_id, ['region_id' => $new_region_id]);
    }
}
