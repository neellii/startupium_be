<?php
namespace App\Http\Resources\Message;

use App\Entity\Chat\Message;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageReportListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Message $this */
        return [
            'id' => $this->id
        ];
    }
}
