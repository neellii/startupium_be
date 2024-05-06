<?php

namespace App\Http\Resources\Residence;

use App\Entity\Residence\City;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /** @var City $this */
    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            'title' => $this?->title,
            'aria' => $this?->aria
        ];
    }
}
