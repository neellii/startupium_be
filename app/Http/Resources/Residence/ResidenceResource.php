<?php

namespace App\Http\Resources\Residence;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id
        ];
        if ($this->title) {
            // title - это город или вместе с регионом и областью
            $data['title'] = $this->title;
        } else {
            $result = "";
            $data['region'] = $this->region;
            $data['aria'] = $this->aria;
            $data['regionId'] = $this->region_id;
            $data['city'] = $this->city;
            if ($this->region) {
                $result .= $this->region;
            }
            if ($this->aria) {
                $result .= ' ' . $this->aria;
            }
            if ($result) {
                $result .= ', ';
            }
            // title - это город или вместе с регионом и областью
            $data['title'] = $result . $this->city;
        }
        return $data;
    }
}
