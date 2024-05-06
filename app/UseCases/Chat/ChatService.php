<?php
namespace App\UseCases\Chat;

use App\Entity\Centrifugo\CentrifugoInterface;
use App\Entity\Chat\Message;
use App\Entity\User\User;
use App\Events\Message\MessageEvent;
use App\Events\Message\RemoveMessageEvent;
use App\Events\Message\UpdateMessageEvent;
use App\Http\Requests\Chat\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    private $centrifugo;
    public function __construct(CentrifugoInterface $centrifugo)
    {
        $this->centrifugo = $centrifugo;
    }

    // контакты пользователя
    public function getContacts(): Collection
    {
        /** @var User $user */
        $user = authUser();
        $contacts = $user->chat()->withTrashed()->where('user_id', $user->id)->get();

        // получаем кол-во непрочитанных сообщений сгруппированных по
        // отправителю
        $unreadIds = Message::query()
            ->selectRaw('`from` as sender_id, count(`from`) as messages_count')
            ->where('to', $user->id)
            ->where('read', '=', false)
            ->groupBy('from')
            ->get();

        // добавляю в контакт кол-во непрочитанных сообщений
        $contacts = $contacts->map(function ($contact) use ($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $contact->id)->first();
            $contact->read = $contactUnread ? $contactUnread->messages_count : 0;
            return $contact;
        });
        return $contacts;
    }

    // кол-во не прочитанныx сообщений у авторизованного пользователя
    public function hasUnreadMessages() {
        return Message::query()
            ->selectRaw('`from` as sender_id, count(`from`) as messages_count')
            ->where('to', authUser()->id)
            ->where('read', '=', false)
            ->groupBy('from')
            ->exists();
    }

    // добавить пользователя в чат
    public function addToChat(Request $request): User
    {
        /** @var User $user */
        $user = authUser();
        $companion = findUser($request['id']);
        $this->chatWithMyself($user, $companion);
        $user->addToChat($companion->id);
        $companion->addToChat($user->id);
        return $companion;
    }

    // удалить из чата
    public function removeFromChat(Request $request): User
    {
        /** @var User $user */
        $user = authUser();
        $companion = findUserWithTrashed($request['id']);
        $this->chatWithMyself($user, $companion);
        $user->removeFromChat($companion->id);
        return $companion;
    }

    // получаем сообщения между авторизованным пользователем и собеседником
    public function getMessagesFor(string $contact_id): LengthAwarePaginator
    {
        // делаем сообщения прочитанными при получении
        Message::query()
            ->where('from', $contact_id)
            ->where('to', authUser()->id)
            ->update(['read' => true]);

        return Message::query()
            ->where(function ($query) use ($contact_id) {
                $query->where('from', auth()->id());
                $query->where('to', $contact_id);
            })
            ->orWhere(function ($query) use ($contact_id) {
                $query->where('to', auth()->id());
                $query->where('from', $contact_id);
            })
            ->orderByDesc('created_at')
            ->paginate(config('constants.messages_per_page'));
    }

    public function sendMessage(SendMessageRequest $request, string $contact_id): Message
    {
        $contact = findUser($contact_id);
        $message = Message::create([
            'to' => $contact->id,
            'from' => auth()->id(),
            'text' => $request['message']
        ]);

        // при новом сообщении добавляю пользователя в чат или нет, если он уже там
        $contact?->addToChat(auth()->id());

        $this->centrifugo->publish('messages.' . $message->to . $message->from, ['message' => $message]);
        //event(new MessageEvent($message, $contact, authUser()));
        return $message;
    }

    public function removeMessage(Request $request, string $contact_id, string $message_id): void
    {
        $auth = $request->user();
        $user = findUser($contact_id);
        if ($user && $auth->id === $user->id) {
            $message = findAuthMessage($message_id);
            $message->delete();

            $last_message = Message::query()->where(function ($query) use ($message) {
                $query->where('to', findUser($message->to)->id);
                $query->where('from', auth()->id());
            })->orderByDesc('created_at')->get()->first();
            if (!$last_message) {
                //broadcast(new RemoveMessageEvent($message, $message, findUser($message->to), $auth))->toOthers();
                return;
            }

            //broadcast(new RemoveMessageEvent($message, $last_message, findUser($message->to), $auth))->toOthers();
        } else {
            throw new \DomainException(config('constants.check_user'));
        }
    }

    public function updateMessage(SendMessageRequest $request, string $contact_id, string $message_id): Message
    {
        $auth = $request->user();
        $user = findUser($contact_id);
        if ($user && $auth->id === $user->id) {
            $message = findAuthMessage($message_id);
            $message->update(['text' => $request['message']]);

            //broadcast(new UpdateMessageEvent($message, findUser($message->to), Auth::user()))->toOthers();
            return $message;
        } else {
            throw new \DomainException(config('constants.check_user'));
        }
    }

    private function chatWithMyself(User $user, User $companion): void
    {
        if ($user->id === $companion->id) {
            throw new \DomainException('Чат сам с собой.');
        }
    }
}
