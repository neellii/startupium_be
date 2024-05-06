<?php
namespace App\UseCases\Telegram;

use Illuminate\Http\Request;

class TelegramService
{
    public function sendQueryTelegram(Request $request) {
        $arrQuery = [
            "parse_mode" => "html",
            "chat_id" 	=> config("app.chat_id"),
            "text" 	=> $this->createMessage($request['text']),
        ];
        $url = "https://api.telegram.org/bot" . config("app.token") . "/sendMessage";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrQuery);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        if(!isset($result)){
            throw new \DomainException("Telagram-bot error.");
        }

        $res = json_decode($result, true);
        if ($res["ok"]) {
          return ['ok' => true];
        } else {
            return $res;
        }
    }

    private function createMessage($text): string {
      $user = findAuthUser();
      if (!$user) {
        return "<code>Feedback</code> \n<b>От пользователя <u>Аноним</u></b> \n" . "$text";
      }
      return "<code>Feedback</code> \n<b>От пользователя <a href='https://startupium.ru/profile/$user->id'>$user->firstname $user->lastname</a></b> \n" .
        "$text";
    }

}
