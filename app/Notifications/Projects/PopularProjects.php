<?php
namespace App\Notifications\Projects;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PopularProjects extends Notification implements ShouldQueue
{
    use Queueable;
    private $projects;

    public function __construct(array $projects)
    {
        $this->projects = $projects;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Популярные проекты')
            ->view('emails.notification.popular-projects', ['projects' => $this->projects]);
    }
}
