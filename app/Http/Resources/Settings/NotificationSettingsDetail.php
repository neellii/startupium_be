<?php
namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationSettingsDetail extends JsonResource
{
    public function toArray($request)
    {
        return [
            'showCommentsAnswer' => $this->showCommentsAnswer,
            'showComments' => $this->showComments,
            'showLikes' => $this->showLikes,
            'showPublicProjects' => $this->showPublicProjects,
            'showRejectProjects' => $this->showRejectProjects,
            'showBookmarks' => $this->showBookmarks,
            'showReports' => $this->showReports
        ];
    }
}
