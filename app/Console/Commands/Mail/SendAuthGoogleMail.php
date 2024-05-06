<?php

namespace App\Console\Commands\Mail;

use App\Entity\User\Status\Status;
use App\Entity\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\StopGoogleAuth;
use Illuminate\Support\Facades\Hash;

class SendAuthGoogleMail extends Command
{
    protected $signature = 'google:stop-auth {--sendOnly=false} {--email=}';

    protected $description = 'Sending letters to users about termination of registration and login via Google';

    public function handle()
    {
        $sendOnly = $this->option('sendOnly');
        $_email = $this->option('email');

        if (!$_email) {
            $query = User::query();
            if ($sendOnly === "false") {
                $query->whereNull('users.password');
            }
            $query
                ->where('users.email', 'like', '%' . '@gmail.com')
                ->join('user_status', function ($join) {
                    $join->on('users.id', 'like', 'user_status.user_id')
                        ->where('user_status.status', 'like', Status::STATUS_ACTIVE);
                })->select('users.*');
            $users = $query->get();

            foreach($users as $user) {
                $password = 'x34?Z' . $user->id;
                $hash = Hash::make($password);
                $user->update([
                    'password' => $hash,
                ]);
                $this->info('Пользователь - ' . $user->id . ' логин: ' . $user->email . ' пароль: ' . $password);
                Mail::to($user?->email)->send(new StopGoogleAuth([
                    'name' => $user?->firstname, 'email' => $user?->email, 'id' => $user?->id, 'password' => $password
                ]));
            }
        } else {
            Mail::to($_email)->send(new StopGoogleAuth([
                'name' => 'Your name', 'email' => $_email, 'id' => '#', 'password' => "your password"
            ]));
            $this->info('Тестовое письмо отправлено на - ' . $_email );
        }
    }
}
