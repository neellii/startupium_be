<?php
namespace App\UseCases\Socials;

use App\Entity\User\Socials\Socials;
use Illuminate\Http\Request;

class SocialsService
{
    public function createOrUpdate(Request $request) {
        $socilas = json_decode($request['socials'], true);
        $user = authUser();
            $user->socials()->delete();
            /** @var Socials $social */
            foreach ($socilas as $social) {
                Socials::query()->create(
                    [
                        'user_id' => $user->id,
                        'title' => $social['title'],
                        'url' => $social['url'],
                    ]
                );
            }

    }

}
