<?php
namespace App\Http\Resources\Projects;

use App\Entity\Project\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectReportListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Project $this*/
        return [
            'id' => $this->id
        ];
    }
}
