<?php
namespace App\UseCases\Centrifugo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Entity\Project\Project;
use App\Entity\Centrifugo\CentrifugoInterface;
use App\Entity\Chat\Message;
use App\Entity\Comment\Comment;
use App\Entity\User\User;
use App\Http\Resources\Message\CommunicationListResource;
use App\Http\Resources\Notification\ApplyNotificationResource;
use App\Http\Resources\Notification\CommentNotificationResource;
use App\Http\Resources\Notification\MessageNotificationResource;
use App\Http\Resources\Notification\ProjectNotificationResource;

class CentrifugoService
{
    private $centrifugo;
    public function __construct(CentrifugoInterface $centrifugo)
    {
        $this->centrifugo = $centrifugo;
    }

    // получаем токен соединения
    public function getConnectionToken(Request $request): string {
        $user = authUser();
        return $this->centrifugo->generateConnectionToken($user->id, Carbon::now()->addMinutes(30), []);
    }

    private function getNotificationChannel(string $id): string {
        if (!$id) return null;
        return "notification.channel." . $id;
    }
    private function getCommunicationChannel(string $id): string {
        if (!$id) return null;
        return "communication.channel." . $id;
    }

    private function getChatChannel(string $id): string {
        if (!$id) return null;
        return "chat.notification.channel." . $id;
    }

    public function sendCommunicationMessage($cdata, string $id): void  {
        $withCipher = config('crypto.with_cipher');
        $channel = $this->getCommunicationChannel($id);
        if ($withCipher === true) {
            $this->centrifugo->publish($channel, [
                'cdata' => $cdata
            ]);
        } else {
            $this->centrifugo->publish($channel, [
                'data' => new CommunicationListResource($cdata)
            ]);
        }
    }

    public function notifyProjectToFavorites(Project $project) {
        $author = $project->user;
        $auth = authUser();
        if ($author->notificationSettings()->get()->first()?->showLikes && $author->id !== $auth->id) {
            $channel = $this->getNotificationChannel($author->id);
            $this->centrifugo->publish($channel, [
                    "notification" => new ProjectNotificationResource($project, $auth, Project::PROJECT_TO_FAVORITES)
                ]
            );
        }
    }

    public function notifyProjectFromFavorites(Project $project) {
        $author = $project->user;
        $auth = authUser();
        if ($author->notificationSettings()->get()->first()?->showLikes && $author->id !== $auth->id) {
            $channel = $this->getNotificationChannel($author->id);
            $this->centrifugo->publish($channel, [
                    "notification" => new ProjectNotificationResource($project, $auth, Project::PROJECT_FROM_FAVORITES)
                ]
            );
        }
    }

    public function notifyProjectToBookmarks(Project $project) {
        $author = $project->user;
        $auth = authUser();
        if ($author->notificationSettings()->get()->first()?->showBookmarks && $author->id !== $auth->id) {
            $channel = $this->getNotificationChannel($author->id);
            $this->centrifugo->publish($channel, [
                    "notification" => new ProjectNotificationResource($project, $auth, Project::PROJECT_TO_BOOKMARKS)
                ]
            );
        }
    }

    public function notifyProjectFromBookmarks(Project $project) {
        $author = $project->user;
        $auth = authUser();
        if ($author->notificationSettings()->get()->first()?->showBookmarks && $author->id !== $auth->id) {
            $channel = $this->getNotificationChannel($author->id);
            $this->centrifugo->publish($channel, [
                    "notification" => new ProjectNotificationResource($project, $auth, Project::PROJECT_FROM_BOOKMARKS)
                ]
            );
        }
    }

    public function notifyPostComment(Comment $comment) {
        $author = $comment->project->user;
        $auth = authUser();
        if ($author->notificationSettings()->get()->first()?->showComments && $author->id !== $auth->id) {
            $channel = $this->getNotificationChannel($author->id);
            $this->centrifugo->publish($channel, [
                    "notification" => new CommentNotificationResource($comment, Comment::POST_COMMENT)
                ]
            );
        }
    }

    public function notifyReplyToComment(Comment $comment) {
        $author = $comment->reply->user;
        $auth = authUser();
        if ($author->notificationSettings()->get()->first()?->showCommentsAnswer && $author->id !== $auth->id) {
            $channel = $this->getNotificationChannel($author->id);
            $this->centrifugo->publish($channel, [
                    "notification" => new CommentNotificationResource($comment, Comment::REPLY_TO_COMMENT)
                ]
            );
        }
    }

    public function notifyApplyProject(Project $project, User $subscriber) {
        $channel = $this->getNotificationChannel($project?->user?->id);
        $this->centrifugo->publish($channel, [
            "notification" => new ApplyNotificationResource($project, $subscriber)
        ]);
    }

    public function notifyPostMessage(Message $message) {
        $userId = $message->to;
        $auth = authUser();
        if ($userId !== $auth->id) {
            // channel for contact
            $channel = $this->getChatChannel($userId);
            $this->centrifugo->publish($channel, [
                    "notification" => new MessageNotificationResource($message, $auth)
                ]
            );
        }
    }

}
