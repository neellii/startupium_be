<?php
namespace App\Http\Controllers\Api\Settings;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateRequest;
use App\Http\Resources\Settings\PrivateSettingsDetail;

class PrivateSettingsController extends Controller
{
    public function update(UpdateRequest $request)
    {
        $key = $request['key'];
        $value = $request['value'];
        $user = authUser();
        $privateSettings = $user->privateSettings()->get()->first();
        $privateSettings->update([
            $key => $value
        ]);
        return Response::HTTP_CREATED(new PrivateSettingsDetail($privateSettings));
    }
}
