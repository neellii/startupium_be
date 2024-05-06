<?php
namespace App\Http\Controllers\Api\User;

use App\Helpers\Response\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckPasswordRequest;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\PostProfileRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\User\UpdatePersonRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdateUserDataRequest;
use App\Http\Resources\User\AnyUserResource;
use App\Http\Resources\User\AuthUserResource;
use App\Http\Resources\User\ExtendedProfileResource;
use App\Http\Resources\User\ProfileResource;
use App\Http\Resources\User\UpdateAnyUserResource;
use App\Http\Resources\User\UpdateEmailDataResource;
use App\Http\Resources\User\UpdateUserDataResource;
use App\UseCases\Carrer\CarrerService;
use App\UseCases\Profile\ProfileService;
use App\UseCases\Quality\QualityService;
use App\UseCases\Residence\ResidenceService;
use App\UseCases\RoleInProject\RoleInProjectService;
use App\UseCases\Skill\SkillService;
use App\UseCases\Socials\SocialsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    private $service;
    private $skillService;
    private $carrerService;
    private $qualitiesService;
    private $socialsService;
    private $residenceService;
    private $roleInProjectService;

    public function __construct(
        ProfileService $service,
        SkillService $skillService,
        CarrerService $carrerService,
        QualityService $qualitiesService,
        SocialsService $socialsService,
        ResidenceService $residenceService,
        RoleInProjectService $roleInProjectService
        )
    {
        $this->service = $service;
        $this->skillService = $skillService;
        $this->carrerService = $carrerService;
        $this->socialsService = $socialsService;
        $this->qualitiesService = $qualitiesService;
        $this->residenceService = $residenceService;
        $this->roleInProjectService = $roleInProjectService;
    }
    // пока для теста
    public function getExtenedProfile(): JsonResponse
    {
        return Response::HTTP_OK(new ExtendedProfileResource(authUser()));
    }

    /**
     * @OA\Get(
     *      path="/user",
     *      operationId="userProfile",
     *      tags={"User"},
     *      description="Get user profile",
     *      security={{"apiAuth":{}}},
     *      summary="Данные об авторизованном пользователе",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *     )
     *     ),
     */
    // Инфо об авторизованном пользователе
    public function getProfile(): JsonResponse
    {
        return Response::HTTP_OK(new AuthUserResource(authUser()));
    }

    /**
     * @OA\Get(
     *      path="/user/profile-settings",
     *      operationId="userProfileSettings",
     *      tags={"User"},
     *      description="Get user profile settings",
     *      security={{"apiAuth":{}}},
     *      summary="Подробные данные об авторизованном пользователе",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *     )
     *     ),
     */
    // подробные данные об авторизованном пользователе
    public function getProfileSettings(): JsonResponse
    {
        return Response::HTTP_OK(new ProfileResource(authUser()));
    }

    /**
     * @OA\Put(
     *      path="/user",
     *      operationId="updateUserProfile",
     *      tags={"User"},
     *      description="Update user profile",
     *      security={{"apiAuth":{}}},
     *      summary="Обновить имя, фамилию",
     *       @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials key - firstname | lastname ",
     *          @OA\JsonContent(
     *              @OA\Property(property="value", type="string", format="string", example="value"),
     *              @OA\Property(property="key", type="string", format="string", example="firstname | lastname"),
     *              ),
     *          ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *       @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *     ),
     */
    // Обновление данных пользователя
    public function updateProfileOld(UpdateUserDataRequest $request): JsonResponse
    {
        $user = $this->service->updateData($request);
        if ($request['key'] === 'email') {
            // если ключ email, проверяем его, отправлем json с ключем isEmailChecked = true
            // следующим запросом в методе ( checkPassword - ниже)
            // проверяем пароль и меняем email
            return Response::HTTP_OK(new UpdateEmailDataResource($user));
        }
        return Response::HTTP_OK(new UpdateUserDataResource($user));
    }

    // меняем пароль
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $password_changed_at = $this->service->changePassword($request);
        return Response::HTTP_CREATED([
            'passwordChangedAt' => $password_changed_at,
            'message' => config('constants.password_successfully_changed')]);
    }

    // Проверяем пароль и меняем email - переделать???
    public function checkPassword(CheckPasswordRequest $request): JsonResponse
    {
        $this->service->updateEmail($request);
        return Response::HTTP_OK([
            'success' => true,
            'message' => config('constants.mail_successfully_changed')]);
    }

    // новый пароль
    public function createPassword(NewPasswordRequest $request): JsonResponse
    {
        $password_changed_at = $this->service->createPassword($request);
        return Response::HTTP_CREATED([
            'passwordChangedAt' => $password_changed_at,
            'isProtected' => true,
            'message' => config('constants.account_protected')
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/user",
     *      operationId="userDelete",
     *      tags={"User"},
     *      description="Delete user profile",
     *      security={{"apiAuth":{}}},
     *      summary="Удалить пользователя",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="successfullyDeleted", type="boolean", example="true"),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *       ),
     *     )
     *     ),
     */
    // удаление профиля пользователя
    public function deleteProfile(): JsonResponse
    {
        $this->service->removeUser(authUser());
        return Response::HTTP_OK(['success' => true, 'message' => config('constants.user_successfully_deleted')]);
    }

    // заполнение профиля пользователя
    public function postProfile(PostProfileRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $this->service->postProfile($request);
            $this->service->uploadAvatar($request);
            $this->skillService->createOrUpdate($request);
            $this->carrerService->createOrUpdate($request);
            $this->socialsService->createOrUpdate(($request));
            $this->qualitiesService->createOrUpdate($request);
            $this->roleInProjectService->createOrUpdate($request);

            $_country = json_decode($request['country'], true);
            $_city = json_decode($request['city'], true);
            $this->residenceService->createOrUpdateUserLocation(authUser(),
                $_country['title'] ?? null, $request['region'], $request['city'],
                $_country['id'] ?? null, $_city['regionId'] ?? null, $_city['id'] ?? null
            );
        });
        return Response::HTTP_OK(new UpdateAnyUserResource(authUser()));
    }

    // Обновляем данные профиля (скиллы, качества, о себе)
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = null;
        $user = DB::transaction(function () use ($request) {
            $user = null;
            $this->skillService->createOrUpdate($request);
            $this->qualitiesService->createOrUpdate($request);
            $user = $this->service->updateBioData($request);
            return $user;
        });
        return Response::HTTP_OK(new AnyUserResource($user));
    }

    // Обновляем данные профиля (Карьеру пользователя)
    public function updateCarrers(PostProfileRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $this->carrerService->createOrUpdate($request);
        });
        return Response::HTTP_OK(['ok' => $request['country'] && $request['city']]);
    }

    // Обновляем данные профиля (имя, фамилию, роли в проекте, соц. сети, занимаемая должность в проеке)
    public function updatePerson(UpdatePersonRequest $request): JsonResponse
    {
        $user = null;
        $user = DB::transaction(function () use ($request) {
            $user = null;
            $this->roleInProjectService->createOrUpdate($request);
            $this->socialsService->createOrUpdate(($request));
            $this->service->uploadAvatar($request);
            $user = $this->service->updatePersonData($request);

            $_country = json_decode($request['country'], true);
            $_city = json_decode($request['city'], true);
            $this->residenceService->createOrUpdateUserLocation(authUser(),
                $_country['title'] ?? null, $request['region'], $request['city'],
                $_country['id'] ?? null, $_city['regionId'] ?? null, $_city['id'] ?? null
            );
            return $user;
        });
        return Response::HTTP_OK(new UpdateAnyUserResource($user));
    }

    // загрузка аватара для пользователя
    public function uploadAvatar(Request $request): JsonResponse
    {
        $url = $this->service->uploadAvatar($request);
        return Response::HTTP_OK(['url' => $url]);
    }

    // загрузка изображений в проект
    public function uploadImage(Request $request): JsonResponse
    {
        $url = $this->service->uploadImage($request);
        return Response::HTTP_OK(['url' => $url]);
    }
}
