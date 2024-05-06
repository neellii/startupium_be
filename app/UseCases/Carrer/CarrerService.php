<?php
namespace App\UseCases\Carrer;

use App\Entity\Carrer\Carrer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CarrerService
{
    // Используется на странице регистрации (заполнения инфы о пользователе)
    public function createOrUpdate(Request $request) {
        $carrers = json_decode($request['careers'], true);
        $user = authUser();
            $user->carrers()->delete();
            /** @var Carrer $carrer */
            foreach ($carrers as $carrer) {
                Carrer::query()->create(
                    [
                        'user_id' => $user->id,
                        'duty' => $carrer['duty'],
                        'company' => $carrer['company'],
                        'position' => $carrer['position'],
                        'last_date_at' => $carrer['last_date_at'],
                        'start_date_at' => $carrer['start_date_at']
                    ]
                );
            }

    }

    // Редактирование пользователя (создать карьеру)
    public function createCarrer(Request $request) {
        $user = authUser();
        /** @var Carrer $data */
        $data = $this->validateAndGetData($request);
        $carrer = Carrer::query()->make(
            [
                'duty' => $data['duty'],
                'company' => $data['company'],
                'position' => $data['position'],
                'last_date_at' => $data['last_date_at'],
                'start_date_at' => $data['start_date_at']
            ]);
        $carrer->user()->associate($user);
        $carrer->saveOrFail();
        return $carrer;
    }

    // Редактирование пользователя (удалить карьеру)
    public function deleteCarrer(Request $request): Carrer {
        $id = $request['id'] ?? "";
        $carrer = $this->findCarrer($id);
        $carrer->delete();
        return $carrer;
    }

    // Редактирование пользователя (обновить карьеру)
    public function updateCarrer(Request $request): Carrer {
        /** @var Carrer $data */
        $data = $this->validateAndGetData($request);
        $carrer = $this->findCarrer($data['id']);
        $carrer->update([
            'duty' => $data['duty'],
            'company' => $data['company'],
            'position' => $data['position'],
            'last_date_at' => $data['last_date_at'],
            'start_date_at' => $data['start_date_at']
        ]);
        return $carrer;
    }

    private function validateAndGetData(Request $request) {
        $carrer = json_decode($request['career'], true);
        [
            'id' => $carrer['id'] ?? "",
            'duty' => $carrer['duty'] ?? throw new \DomainException(config('constants.something_went_wrong')),
            'position' => $carrer['position'] ?? throw new \DomainException(config('constants.something_went_wrong')),
            'position' => $carrer['position'] ?? throw new \DomainException(config('constants.something_went_wrong')),
            'last_date_at' => $carrer['last_date_at'] ?? throw new \DomainException(config('constants.something_went_wrong')),
            'start_date_at' => $carrer['start_date_at'] ?? throw new \DomainException(config('constants.something_went_wrong')),
        ];
        return $carrer;
    }

    private function findCarrer(string $id): Carrer {
        try {
            return Carrer::query()
                ->where('id', $id)
                ->where('user_id', authUser()->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new \DomainException("Ничего не найдено.");
        }
    }

}
