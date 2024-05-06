<?php
namespace App\Exceptions;

use App\Exceptions\Auth\EmailHasVerifiedException;
use App\Exceptions\Centrifugo\CentrifugoConnectionException;
use App\Exceptions\Centrifugo\CentrifugoException;
use App\Exceptions\MediaLibrary\AccessException;
use App\Exceptions\ReCaptcha\ReCaptchaException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AccessException::class,
        ReCaptchaException::class,
        EmailHasVerifiedException::class,
        CentrifugoException::class,
        CentrifugoConnectionException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->json(['message' => config('constants.socket_exception_access_denied')], Response::HTTP_FORBIDDEN);
        }
        if ($exception instanceof QueryException) {
            return response()->json(['message' => config('constants.something_went_wrong')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($exception instanceof ValidationException) {
            return response()->json(['message' => $this->validation($exception)], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($exception instanceof \DomainException && $request->expectsJson()) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        if ($exception instanceof \DomainException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        if ($exception instanceof HttpException &&
            $request->expectsJson() &&
            $exception->getMessage() === 'Your email address is not verified.' &&
            $exception->getStatusCode() === Response::HTTP_FORBIDDEN) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => config('constants.too_many_requests'),
            ], $exception->getStatusCode());
        }
        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                'message' => config('constants.route_not_found'),
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => config('constants.access_denied'),
            ], Response::HTTP_FORBIDDEN);
        }
        if ($exception instanceof LockedHttpException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_LOCKED);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => config('constants.content_not_found'),
            ], Response::HTTP_NOT_FOUND);
        }
        return parent::render($request, $exception);
    }

    private function validation(ValidationException $exception): string
    {
        switch ($exception->validator->getMessageBag()->get('email')) {
            case [trans('validation.unique', ['attribute' => trans('validation.attributes.email')])]:
                return config('constants.unique_email');
            case [trans('auth.failed')]:
                return config('constants.login_error');
            default:
                return $exception->getMessage();
        }
    }
}
