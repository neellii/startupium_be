<?php
namespace App\Console\Commands\Mail;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mail as ExampleMail;

class SendEmailCommand extends Command
{
    protected $signature = 'email:send-email';
    protected $description = 'Test send email';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Mail::to('it.leha@gmail.com')->send(new ExampleMail(['name' => 'Andrey', 'verification_code' => 'SOME_VERIFICATION_CODE']));
    }
}
