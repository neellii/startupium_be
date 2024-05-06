<?php

namespace App\Http\Resources\Residence;

use App\Entity\Residence\Region;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    /** @var Region $this */
    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            'title' => $this?->title,
        ];
    }
}
