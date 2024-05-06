<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/google.css') }}" rel="stylesheet">
    <title>Startupium</title>
</head>
<body>
<div class="container">
    <h2>Уважаемый (ая) {{ $name }}</h2>
    <p>Напоминаем, что 31 июля 2023 года президент подписал Федеральный закон от 31.07.2023 N 406-ФЗ
        «О внесении изменений в Федеральный закон «Об информации,
         информационных технологиях и о защите информации» и Федеральный закон «О связи» (406-ФЗ).
         Таким образом с 1 декабря 2023 года авторизация и регистрация на сайтах через Google, Apple и другие иностранные сервисы запрещены.</p>
    <h2>Уведомляем вас</h2>
    <p>Что на сайт <a href={{config('app.origin')}}>Startupium</a> теперь невозможно войти через Google</p>
    <p>Так как ваш <a href={{config('app.origin')."/profile"."/".$id}}>аккаунт</a> на сайте не защищен, мы создали для вас временный пароль:</p>
    <div>
        <div class="credentials"><h4 class="empty">Логин: </h4><p class="empty">{{$email}}</p></div>
        <div class="credentials"><h4 class="empty">Пароль: </h4><p class="empty">{{$password}}</p></div>
    </div>
    <p>С уважением, администрация сайта.</p>

    <div
        style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;">
    <div
        style="line-height: 1.2; font-size: 12px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color: #555555; mso-line-height-alt: 14px;">
    <p style="font-size: 12px; line-height: 1.2; word-break: break-word; font-family: inherit; mso-line-height-alt: 14px; margin: 0;">
    <span style="font-size: 12px;">Вы получили это письмо, потому что создали аккаунт на сайте Startupium.ru.</span></p>
    </div>
</div>
</div>
</body>
</html>
