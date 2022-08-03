<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/redesignStyles.css') }}" rel="stylesheet">
    <script src="{{ mix('js/knowledge.js') }}" defer></script>
    @auth
        <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endauth
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="../../../ico/favicon.ico">
</head>

<body style="padding-top: 85px;">

    <header class="main-header">
        <div class="container">
            @include('common.template.assets.main_menu')

            <div class="main-header__auxiliary">
                @include('auth.headuser-v2')
            </div>
        </div>
    </header>
    <div id="app" class="app-content content"></div>
</body>
</html>
