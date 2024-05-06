<?php
namespace App\Http\Resources\User;

use App\Entity\User\Skill\Skill;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Skill $this*/
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
        ];
    }
}
