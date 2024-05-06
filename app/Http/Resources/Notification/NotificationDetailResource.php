<?php
namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => $this->data,
            'readAt' => $this->read_at,
            'createdAt' => $this->created_at
        ];
    }
}
