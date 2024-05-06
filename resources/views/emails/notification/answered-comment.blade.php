<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DurDom</title>
</head>
<body>
    <h2> Вам ответили на комментарий к проекту</h2>
    <h4><a href={{config('app.origin') . '/project/' . $project->id}}>{{$project->title}}</a></h4>
    <p>Пользователь <a href={{config('app.origin') . '/profile/' . $user->id}}>{{$user->lastname}} {{$user->firstname}}</a></p>
    <h3> Текст </h3>
    <p>{{$text}}</p>
</body>
</html>
