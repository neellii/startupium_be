<?php
namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->groupBy(['data.author.id', 'data.type']);
    }
}
