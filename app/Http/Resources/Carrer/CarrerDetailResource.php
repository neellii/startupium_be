<?php

namespace App\Http\Resources\Carrer;

use App\Entity\Carrer\Carrer;
use Illuminate\Http\Resources\Json\JsonResource;

class CarrerDetailResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Carrer $this */
        return [
            'id' => $this->id,
            'duty' => $this->duty,
            'position' => $this->position,
            'company' => $this->company,
            'last_date_at' => $this->last_date_at,
            'start_date_at' => $this->start_date_at,
        ];
    }
}
