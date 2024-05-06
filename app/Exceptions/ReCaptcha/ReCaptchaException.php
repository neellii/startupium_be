<?php

namespace App\Exceptions\ReCaptcha;

use Exception;
use Illuminate\Http\Response;

class ReCaptchaException extends Exception
{
    protected $message;
    public function __construct($message)
    {
        parent::__construct();
        $this->message = $message;
    }
    public function render()
    {
        return response()->json(["message" => $this->message], Response::HTTP_CONFLICT);
    }
}
