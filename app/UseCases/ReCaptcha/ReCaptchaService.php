<?php
namespace App\UseCases\ReCaptcha;

class ReCaptchaService
{
    public function siteverify($token) {
        $withReCaptcha = config("app.with_re_captcha");
        if ($withReCaptcha === true) {
            $arrQuery = [
                "response" => $token ?? "",
                "secret" 	=> config('app.re_captcha_secret_key'),
            ];
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arrQuery);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $result = curl_exec($ch);
            curl_close($ch);

            if(!isset($result)){
                throw new \DomainException(config("constants.something_went_wrong"));
            }

            $res = json_decode($result, true);
            if($res["success"] === true && $res["score"] >= 0.5) {
                return true;
            } else {
                return $res;
            }
        }
        return true;
    }

}
