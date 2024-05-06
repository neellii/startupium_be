<?php
namespace App\Http\Controllers\Api\Settings;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateRequest;
use App\Http\Resources\Settings\SendByEmailSettings as SettingsSendByEmail;

class SendByEmailSettings extends Controller
{
    public function update(UpdateRequest $request)
    {
        $key = $request['key'];
        $value = $request['value'];
        $user = authUser();
        $sendByEmailSettings = $user->sendByEmailSettings()->get()->first();
        $sendByEmailSettings->update([
            $key => $value
        ]);
        return Response::HTTP_CREATED(new SettingsSendByEmail($sendByEmailSettings));
    }
}
