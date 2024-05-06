<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:url" content="{{config('app.url')}}">
    <meta property="og:site_name" content="{{config('app.name', 'DurDom')}}">
    <meta property="og:title" content="Платформа для поиска команды">
    <meta property="og:description" content="Здесь можно найти команду для стартапа, присоединиться в уже существующий проект, найти инвестора и партнёра.">
    <meta property="og:image" content="{{url('/assets/png/Logo.png')}}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="170">
    <meta property="og:image:height" content="170">
</head>
<body>
<div id="root"></div>
</body>
</html>
