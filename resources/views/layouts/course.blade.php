<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">
<head><title>{{ env('APP_NAME') }}</title></head>
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
    style="padding-top: 100px;"
    @if(session()->has('admin_id')) data-admin="true" @endif
>

    @include('common.template.header')

    
    <div id="app" class="app-content content">
        </div>
        
</body>
</html>
