<?php
namespace App\UseCases\Residence;

use Illuminate\Http\Request;
use App\Entity\Project\Project;
use App\Entity\Residence\City;
use App\Entity\Residence\Country;
use App\Entity\Residence\Region;
use App\Entity\User\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ResidenceService
{
    // refers to project_residence_ref table
    public function createOrUpdateProjectLocation(Project $project,
        string|null $country = "",string|null $region = "", string|null $city = "",
        string|null $countryId, string|null $regionId, string|null $cityId
    ) {

        $_country = findCountryByTitleAndId($country, $countryId); // country or null
        $_region = findRegionById($regionId, $_country?->id); // region or null
        $_city = findCityById($cityId, $_country?->id, $regionId); // city or null

        $project->country()->detach();
        $project->country()->attach($_country?->id, ['city_id' => $_city?->id, 'region_id' => $_region?->id]);
    }

    // refers to user_residence_ref table
    public function createOrUpdateUserLocation(User $user,
        string|null $country = "",string|null $region = "", string|null $city = "",
        string|null $countryId, string|null $regionId, string|null $cityId
    ) {

        $_country = findCountryByTitleAndId($country, $countryId); // country or null
        $_region = findRegionById($regionId, $_country?->id); // region or null
        $_city = findCityById($cityId, $_country?->id, $regionId); // city or null

        $user->country()->detach();
        $user->country()->attach($_country?->id, ['city_id' => $_city?->id, 'region_id' => $_region?->id]);
    }

    public function getCountries(): LengthAwarePaginator {
        return Country::query()->paginate(config('constants.countries_per_page'));
    }

    public function getResultsCountries(Request $request): LengthAwarePaginator {
        $result = $request['search_query'] ?? "";
        return Country::query()
            ->where('title', 'like', '%' . $result . '%')
            ->paginate(config('constants.countries_per_page'));
    }

    public function getCitiesAndRegions(Request $request): LengthAwarePaginator {
        $data = $request['country'] ?? "";
        if (!$data) {
            return Region::query()->paginate(config('constants.regions_per_page'));
        }
        $country = Country::query()->where('title', 'like', $data)->first();
        return Region::query()
            ->where('regions.country_id', 'like', $country?->id ?? "")
            ->join('cities', 'cities.region_id', 'regions.id')
            ->select('cities.id', 'regions.id as region_id', 'cities.aria','cities.title as city', 'regions.title as region')
            ->paginate(config('constants.cities_per_page'));
    }

    public function getResultsCitiesAndRegions(Request $request): LengthAwarePaginator {
        $data = $request['country'] ?? "";
        $result = $request['search_query'] ?? "";
        if (!$data) {
            return Region::query()
                ->where('title', 'like', '%' . $result . '%')
                ->paginate(config('constants.regions_per_page'));
        }
        $country = Country::query()->where('title', 'like', $data)->first();
        $cities = City::query()
            ->where('country_id', 'like', $country?->id ?? "")
            ->where('title', 'like', '%' . $result . '%')
            ->whereNull('region_id')
            ->paginate(config('constants.cities_per_page'));
        $citiesInRegions = Region::query()
            ->where('regions.country_id', 'like', $country?->id ?? "")
            ->where('cities.title', 'like', '%' . $result . '%')
            ->join('cities', 'cities.region_id', 'regions.id')
            ->select('cities.id', 'regions.id as region_id', 'cities.aria','cities.title as city', 'regions.title as region')
            ->paginate(config('constants.cities_per_page'));

        return $this->merge($cities, $citiesInRegions);
    }

    private function merge(LengthAwarePaginator $collection1, LengthAwarePaginator $collection2)
    {
        $path = "";
        $perPage = 0;
        $total = $collection1->total() - $collection2->total();
        $items = array_merge($collection1->items(), $collection2->items());

        if ($total >= 0) {
            $total = $collection1->total();
            $path = $collection1->path();
            $perPage = $collection1->perPage();
        } else {
            $total = $collection2->total();
            $path = $collection2->path();
            $perPage = $collection2->perPage();
        }

        $paginator = new LengthAwarePaginator($items, $total, $perPage, null,
            ['path' => $path]
        );
        return $paginator;
    }


    // Deprecated
    private function getCitiesOld(Request $request): LengthAwarePaginator {
        $dataC = $request['country'] ?? "";
        $dataR = $request['region'] ?? "";
        if (!$dataC && !$dataR) {
            return City::query()->paginate(config('constants.cities_per_page'));
        }
        if (!$dataC && $dataR) {
            $region = Region::query()->where('title', 'like', $dataR)->first();
            return City::query()->where('region_id', 'like', $region?->id ?? "")
                ->paginate(config('constants.cities_per_page'));
        }
        if ($dataC && !$dataR) {
            $country = Country::query()->where('title', 'like', $dataC)->first();
            return City::query()->where('country_id', 'like', $country?->id ?? "")
                ->paginate(config('constants.cities_per_page'));
        }

        $country = Country::query()->where('title', 'like', $dataC)->first();
        $region = Region::query()->where('title', 'like', $dataR)->first();
        return City::query()
            ->where('country_id', 'like', $country?->id ?? "")
            ->where('region_id', 'like', $region?->id ?? "")
            ->paginate(config('constants.cities_per_page'));
    }

    // Deprecated
    private function getResultsCitiesOld(Request $request): LengthAwarePaginator {
        $dataC = $request['country'] ?? "";
        $dataR = $request['region'] ?? "";
        $result = $request['search_query'] ?? "";

        if (!$dataC && !$dataR) {
            return City::query()
                ->where('title', 'like', '%' . $result . '%')
                ->paginate(config('constants.cities_per_page'));
        }
        if (!$dataC && $dataR) {
            $region = Region::query()->where('title', 'like', $dataR)->first();
            return City::query()
                ->where('region_id', 'like', $region?->id ?? "")
                ->where('title', 'like', '%' . $result . '%')
                ->paginate(config('constants.cities_per_page'));
        }
        if ($dataC && !$dataR) {
            $country = Country::query()->where('title', 'like', $dataC)->first();
            return City::query()
                ->whereNull('region_id')
                ->where('country_id', 'like', $country?->id ?? "")
                ->where('title', 'like', '%' . $result . '%')
                ->paginate(config('constants.cities_per_page'));
        }

        $country = Country::query()->where('title', 'like', $dataC)->first();
        $region = Region::query()->where('title', 'like', $dataR)->first();
        return City::query()
            ->where('country_id', 'like', $country?->id ?? "")
            ->where('region_id', 'like', $region?->id ?? "")
            ->where('title', 'like', '%' . $result . '%')
            ->paginate(config('constants.cities_per_page'));

    }
}
