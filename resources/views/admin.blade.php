<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    @auth
        <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endauth
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'APP_NAME' }} | Администратор</title>

    <link href="{{ mix('admin/css/index.css') }}" rel="stylesheet">
    <script src="{{ mix('admin/js/index.js') }}" defer></script>

    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
</head>

<body >

    <div id="app" class="app-content content">
    </div>
</body>
</html>
