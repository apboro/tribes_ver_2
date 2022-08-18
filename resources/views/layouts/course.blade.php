<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/courseEditor.css') }}" rel="stylesheet">
    <script src="{{ mix('js/courseEditor.js') }}" defer></script>
    @auth
        <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endauth
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="../../../ico/favicon.ico">
</head>

<body
    style="padding-top: 85px;"
    @if(!session()->has('admin_id')) data-admin="true" @endif
>

    <header class="main-header">
        <div class="container">
            @include('common.template.assets.main_menu')
            
            @if(session()->has('admin_id'))
            <span style="
                display: inline-block;
                padding: 0.3rem 0.5rem;
                font-size: 85%;
                color: #FFF;
                border-radius: 0.358rem;
                text-align: center;
                white-space: nowrap;
                background-color: grey;
                "
                class="badge bg-secondary"
            >Режим администратора</span>
            @endif

            <div class="main-header__auxiliary">
                @include('auth.headuser-v2')
                            </div>
        </div>
    </header>

    
    <div id="app" class="app-content content">
        </div>
        
</body>
</html>
