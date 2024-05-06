<?php
namespace App\Http\Resources\User;

use App\Entity\User\Technology\Technology;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnologyListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Technology $this*/
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
        ];
    }
}
