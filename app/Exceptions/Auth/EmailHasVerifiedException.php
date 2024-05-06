<?php
namespace App\Exceptions\Auth;

use Exception;
use App\Entity\User\User;
use Illuminate\Http\Response;

class EmailHasVerifiedException extends Exception
{
    protected $user;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    public function render($request)
    {
        if ($this->user->last_email_at->diffInMinutes(now()) >= 30) {
            return response()
                ->json(
                    [
                        'message' => config('constants.email_has_not_verified'),
                        'isEmailResend' => true],
                    Response::HTTP_BAD_REQUEST
                );
        }
        return response()
            ->json(['message' => config('constants.email_has_not_verified')], Response::HTTP_BAD_REQUEST);
    }
}
