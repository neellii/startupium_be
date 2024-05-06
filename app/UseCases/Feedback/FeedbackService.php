<?php
namespace App\UseCases\Feedback;

use App\Entity\Feedback\Feedback;
use Illuminate\Http\Request;

class FeedbackService
{
    public function create(Request $request): Feedback {
        $user = findAuthUser();
        $feedback = Feedback::query()->make([
            'text' => $request['text'],
            'user_id' => $user?->id
        ]);
        //$feedback->user()->associate($user);
        $feedback->saveOrFail();

        // send event

        return $feedback;
    }
}
