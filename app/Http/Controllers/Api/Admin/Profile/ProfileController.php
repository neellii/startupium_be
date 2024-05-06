<?php

namespace App\Http\Controllers\Api\Admin\Profile;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function clearPosition() {
        $user = authUser();
        $user->update([
            'desired_position' => null
        ]);
        return Response::HTTP_OK(['success' => true]);
    }
}
