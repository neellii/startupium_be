<?php
namespace App\Http\Resources\User;

use App\Entity\User\User;
use App\Http\Resources\Settings\NotificationSettingsDetail;
use App\Http\Resources\Settings\PrivateSettingsDetail;
use App\Http\Resources\Settings\SendByEmailSettings;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => lastnameFormat($this->lastname),
            'email' => $this->email,
            'createdAt' => $this->created_at,
            'avatarUrl' => $this->getAvatarUrl(),
            'settings' => [
                'notificationSettings' => new NotificationSettingsDetail($this->notificationSettings()->first()),
                'privateSettings' => new PrivateSettingsDetail($this->privateSettings()->first()),
                'sendByEmailSettings' => new SendByEmailSettings($this->sendByEmailSettings()->first())
            ],
            'passwordChangedAt' => $this->password_changed_at,
            'isProtected' => $this->password ? true : false
        ];
    }
}
