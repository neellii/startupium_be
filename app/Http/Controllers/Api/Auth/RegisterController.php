<?php
namespace App\Http\Controllers\Api\Auth;

use App\Entity\Settings\NotificationSettings;
use App\Entity\Settings\PrivateSettings;
use App\Entity\Settings\SendByEmailSettings;
use App\Entity\User\Avatar\Avatar;
use App\Entity\User\Status\Status;
use App\Entity\User\User;
use App\Exceptions\ReCaptcha\ReCaptchaException;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Register\RegisterSuccessResource;
use App\UseCases\ReCaptcha\ReCaptchaService;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    private $reCaptchaService;
    public function __construct(ReCaptchaService $reCaptchaService)
    {
        $this->reCaptchaService = $reCaptchaService;
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return  User
     */
    protected function create(array $data): User
    {
        $response = $this->reCaptchaService->siteverify($data['token'] ?? "");
        if ($response === true) {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'id' => $data['id'] ?? 0,
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'] ?? null,
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'verification_code' => sha1(time())
                ]);

                // создаем аватар
                $avatar = Avatar::query()->make([
                    'url' => config('constants.free_icon')
                ]);
                $avatar->user()->associate($user);
                $avatar->save();

                // присваиваем статус в ожидании
                $status = Status::query()->make([
                    'status' => Status::STATUS_WAIT
                ]);
                $status->user()->associate($user);
                $status->save();

                // инициализируем настройки
                $notSettings = new NotificationSettings();
                $notSettings->user()->associate($user);
                $notSettings->save();
                $user->notificationSettings()->attach($notSettings->id);

                $privateSettings = new PrivateSettings();
                $privateSettings->user()->associate($user);
                $privateSettings->save();
                $user->privateSettings()->attach($privateSettings->id);

                $sendByEmailSettings = new SendByEmailSettings();
                $sendByEmailSettings->user()->associate($user);
                $sendByEmailSettings->save();
                $user->sendByEmailSettings()->attach($sendByEmailSettings->id);

                $user->rolesInProject()->create();

                return $user;
            });
        } else {
            throw new ReCaptchaException($response);
        }
    }

       /**
     * @OA\Post(
     ** path="/register",
     *   tags={"Auth"},
     *   summary="Регистрация пользователя",
     *   operationId="register",
     *   description="User Register here",
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass user credentials",
     *      @OA\JsonContent(
     *         required={"email","password", "firstname", "lastname", },
     *         @OA\Property(property="firstname", type="string", format="string", example="Ivan"),
     *         @OA\Property(property="lastname", type="string", format="string", example="Ivanov"),
     *         @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *         @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *         @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
     *      ),
    *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="emailVerified", type="boolean", example="false"),
     *              @OA\Property(property="message", type="string", example="Аккаунт успешно создан. Проверьте свою электронную почту."),
     *       ),
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *   @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *)
     **/
    public function register(Request $request): JsonResponse
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        if ($user !== null) {
            sendEmail($user);
            return Response::HTTP_CREATED(new RegisterSuccessResource($user));
        }
        throw new DomainException(config('constants.something_went_wrong'));
    }
}
