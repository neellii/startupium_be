<?php
namespace App\Http\Controllers\Api\Auth;

use DomainException;
use App\Entity\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use App\Http\Resources\Login\LoginSuccessResource;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;

class LoginController extends Controller
{
    //
    protected $user;

    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
        $this->user = new User;
    }

    /**
     * @OA\Post(
     ** path="/login",
     *   tags={"Auth"},
     *   summary="Авторизация по email и password",
     *   operationId="login",
     *   description="User Login here",
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass user credentials",
     *      @OA\JsonContent(
     *         required={"email","password"},
     *         @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *         @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *      ),
    *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="token", type="string", example="dhdfgfdgdfgjdfg.dfgdfgdfg"),
     *              @OA\Property(property="tokenType", type="string", example="Bearer"),
     *       ),
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *)
     **/
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::query()->where('email', $credentials['email'])->first();
        if ($user) {
            // если данные для входа не совпадают
            if (!auth()->attempt($credentials)) {
                $responseMessage = config('constants.login_error');
                throw new DomainException($responseMessage);
            }
            // если почта не подтверждена
            if (!$user->hasVerifiedEmail()) {
                throw new LockedHttpException(config("constants.email_has_not_verified"));
            }
            // если все хорошо!
            $accessToken = createAccessToken($user);
            $refreshToken = createRefreshToken($user); // refreshToken as id
            return Response::HTTP_OK(new LoginSuccessResource(auth()->user(), $accessToken, $refreshToken));
        } else {
            // если пользователь не найден
            $responseMessage = config('constants.user_not_found');
            throw new DomainException($responseMessage);
        }
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();

        $accessTokenResult = $user?->token();
        if ($accessTokenResult?->name === 'refreshToken') {
            throw new AuthenticationException();
        }

        $accessTokenResult->delete();
        return Response::HTTP_OK([
            'success' => true,
            'message' => 'successfully logged out.'
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        // в заголовке accessToken, в теле refresh token id
        /** @var User $user */
        $user = Auth::guard('api')->user();

        $accessTokenResult = $user?->token();
        if ($accessTokenResult?->name === 'refreshToken') {
            throw new AuthenticationException();
        }

        if ($accessTokenResult?->expires_at?->diffInMinutes(now()) <= config('constants.access_token_expires_in')) {
            throw new AuthenticationException();
        }


        $refreshTokenId = $request['token'];
        if (!$refreshTokenId) {
            throw new DomainException(config('constants.transmit_incorrect_data'));
        }
        $refreshTokenResult = $user?->tokens()
            ->where('id', 'like', $refreshTokenId)
            ->where('name', 'like', 'refreshToken')->first();

        // если разные пользователи
        if (!$refreshTokenResult || !$accessTokenResult || $refreshTokenResult?->user_id !== $accessTokenResult?->user_id) {
            throw new AuthenticationException();
        }

         // если истек
        if ($refreshTokenResult?->expires_at->diffInDays(now()) > config('constants.refresh_token_expires_in')) {
            throw new AuthenticationException();
        }

        $refreshTokenResult->delete();
        $accessTokenResult->delete();

        // создаем новую пару
        $accessToken = createAccessToken($user);
        $refreshToken = createRefreshToken($user);
        return Response::HTTP_OK(new LoginSuccessResource($user, $accessToken, $refreshToken));
    }
}
