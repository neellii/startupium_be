<?php
namespace App\UseCases\Profile;

use App\Entity\User\Avatar\Avatar;
use App\Entity\User\Image\Image;
use App\Entity\User\Status\Status;
use App\Entity\User\User;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Jobs\Avatar\RemoveUserAvatar;
use App\Jobs\User\RemoveUser;
use Carbon\Carbon;
use DomainException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    // обновляем данные пользователя ( имя, фамилию )
    // если ключ email - проверяем существует ли
    public function updateData(Request $request): User
    {
        $user = authUser();
        if (!$this->emailCheck($request)) { // если не email обновляем другие данные
            $user->update([$request['key'] => $request['value']]);
        }
        return $user;
    }

    // меняем пароль и возвращаем дата смены пароля
    public function changePassword(UpdatePasswordRequest $request): string
    {
        $user = authUser();
        $password_changed_at = Carbon::now();
        $user->update([
            'password' => Hash::make($request['newPassword']),
            'password_changed_at' => $password_changed_at
        ]);
        return $password_changed_at;
    }

    // меняем email и переводим пользователя в статус не активен
    // отправляем новый код на новую почту для подтверждения
    public function updateEmail($request): void
    {
        $user = authUser();
        DB::transaction(function () use ($user, $request) {
            $user->status->changeStatus(Status::STATUS_WAIT);
            $user->update([
                'psuid' => null,
                'email' => $request['email'],
                'email_verified_at' => null,
                'verification_code' => sha1(time())
            ]);
            $user->tokens()->where("user_id", $user->id)->delete();
        });
        sendEmail($user);
    }

    // создаем пароль и возвращаем дату создания
    public function createPassword(NewPasswordRequest $request): string
    {
        $user = authUser();
        $password_changed_at = Carbon::now();
        if ($user && !$user->password) {
            $user->update([
                'password' => Hash::make($request['password']),
                'password_changed_at' => $password_changed_at
            ]);
            return $password_changed_at;
        }
        throw new \DomainException(config('constants.something_went_wrong'));
    }

    // удаляем пользователя
    public function removeUser(User $user): void
    {
       DB::transaction(function () use ($user) {
            $user->update(['email' => null, 'psuid' => null]);
            $user->status->changeStatus(Status::STATUS_DELETED);
            $user->delete(); // soft delete
            $user->projects()->delete(); //soft delete
            $user->tokens()->where("user_id", $user->id)->delete();
        });
        RemoveUserAvatar::dispatch($user->id);
        RemoveUser::dispatch($user->id);
        //RemoveFromIndex::dispatch($user->id); // projects
    }

    // загрузка аватара пользователя
    public function uploadAvatar(Request $request): string | null
    {
        $user = authUser();
        return DB::transaction(function () use ($request, $user) {
            $data = $request['avatar'];
            $ava = Avatar::query()->where('user_id', $user->id)->first();
            // пустый данные - автор удаляет аву
            if (!boolval($data)) {
                $ava?->delete();
                return;
            }

            // если медиа url вылиден, то ничего менять не нужно
            $mediaUrl = getValidMediaUrl($user, $data);
            if (boolval($mediaUrl)) {
                return;
            }

            $this->validateBase64Image($data);
            $collectionName = Avatar::USER_AVATARS . '' . $user->id . '';

            $hasMedia = $ava?->hasMedia($collectionName);
            if ($ava && $hasMedia) {
                $ava->delete();
            }

            $ava = Avatar::query()->where('user_id', $user->id)->first();
            if (!$ava) {
                $ava = Avatar::query()->make([
                    'url' => null
                ]);
                $ava->user()->associate($user);
                $ava->save();
            }
            $ava->addMediaFromBase64($data)->toMediaCollection($collectionName);
            $url = $ava->getMedia($collectionName)->last()?->getUrl();
            $ava->update([
                'url' => $url
            ]);

            return $ava?->url;
        });
    }

    // загрузка картинки в проект
    public function uploadImage(Request $request): string
    {
        $user = authUser();
        return DB::transaction(function () use ($request, $user) {
            $image = Image::query()->make([
                'url' => null
            ]);
            $image->user()->associate($user);
            $image->save();

            $collectionName = Image::USER_IMAGES . '' . $user->id . '';
            $data = $request['image'];
            if (isset($data)) {
                $image->addMediaFromBase64($data)->toMediaCollection($collectionName);
            }
            $url = $image->getMedia($collectionName)->first()->getUrl();
            $image->update([
                'url' => $url
            ]);
            return $url;
        });
    }

    public function postProfile(Request $request) {
        $user = authUser();
        $user->update([
            'bio' => $this->modifiedBio($request['bio']),
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'] ?? "",
            'desired_position' => $request['desiredPosition'],
        ]);
    }

    public function updatePersonData(Request $request) {
        $user = authUser();
        $user->update([
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'] ?? "",
            'desired_position' => $request['desiredPosition'],
        ]);
        return $user;
    }

    public function updateBioData(Request $request) {
        $user = authUser();
        $user->update([
            'bio' => $this->modifiedBio($request['bio']),
        ]);
        return $user;
    }

    // проверяем, что email не существует в противном случае бросаем ошибку
    private function emailCheck(Request $request): bool
    {
        if ($request['key'] === 'email') {
            $this->emailValidate($request);
            if (findUserByEmail($request['value'])) {
                throw new \DomainException(config('constants.email_exists'));
            }
            return true;
        }
        return false;
    }

    private function emailValidate(Request $request): void
    {
        $request->validate([
            'value' => ['required', 'string', 'email', 'max:255']
        ]);
    }

    private function validateBase64Image($text): bool {
        try {
            $strs = explode(",", $text);
            $startWith = str_starts_with($strs[0], 'data:image/');
            $hasBase64 = base64_decode($strs[1], true);

            if (!boolval($hasBase64) || !$startWith) {
                throw new DomainException(config("constants.something_went_wrong"));
            }
        } catch (Exception $ex) {
            throw new DomainException(config("constants.something_went_wrong"));
        }
        return true;
    }

    private function modifiedBio ($bio): string|null {
        return $bio === '<p><br></p>' ? null : $bio;
    }
}
