<?php
namespace App\Http\Resources\Message;

use App\Entity\Chat\Message;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Message $this */
        return [
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'text' => $this->text,
            'createdAt' => $this->created_at
        ];
    }
}
