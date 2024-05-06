<?php
namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivateSettingsDetail extends JsonResource
{
    public function toArray($request)
    {
        return [
            //'pageIndexing' => $this->pageIndexing
        ];
    }
}
