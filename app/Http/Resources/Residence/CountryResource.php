<?php

namespace App\Http\Resources\Residence;

use App\Entity\Residence\Country;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /** @var Country $this */
    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            'title' => $this?->title
        ];
    }
}
