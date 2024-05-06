<?php
namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
            'readAt' => $this->read_at,
            'createdAt' => $this->created_at
        ];
    }
}
