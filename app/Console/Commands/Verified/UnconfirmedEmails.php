<?php

namespace App\Console\Commands\Verified;

use App\Entity\User\User;
use Illuminate\Console\Command;

class UnconfirmedEmails extends Command
{
    protected $signature = 'delete:unconfirmed';
    protected $description = 'Delete unconfirmed emails';

    public function handle()
    {
        User::query()->whereNull('email_verified_at')->forceDelete();
    }
}
