<?php
namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class SendByEmailSettings extends JsonResource
{
    public function toArray($request)
    {
        return [
            'commentAnswer' => $this->commentAnswer,
            'likeProject' => $this->likeProject,
            'popularProjects' => $this->popularProjects,
            'newMessage' => $this->newMessage
        ];
    }
}
