<?php

namespace App\Http\Controllers\Api\Feedback;

use App\Exceptions\ReCaptcha\ReCaptchaException;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\UseCases\Feedback\FeedbackService;
use App\UseCases\ReCaptcha\ReCaptchaService;
use App\UseCases\Telegram\TelegramService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    private $service;
    private $telegramService;
    private $reCaptchaService;
    public function __construct(FeedbackService $service, TelegramService $telegramService, ReCaptchaService $reCaptchaService)
    {
        $this->service = $service;
        $this->telegramService = $telegramService;
        $this->reCaptchaService = $reCaptchaService;
    }

    public function createFeedback(Request $request) {
        $this->validator($request->all())->validate();
        $response = $this->reCaptchaService->siteverify($request['token']);
        if ($response === true) {
            $this->service->create($request);
            $result = $this->telegramService->sendQueryTelegram($request);
            return Response::HTTP_OK($result);
        } else {
            throw new ReCaptchaException($response);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'text' => ['required', 'string'],
        ]);
    }
}
