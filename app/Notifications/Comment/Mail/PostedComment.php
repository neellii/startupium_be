<?php
namespace App\Notifications\Comment\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PostedComment extends Notification implements ShouldQueue
{
}
