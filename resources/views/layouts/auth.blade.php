<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">

<meta name="csrf-token" content="{{ csrf_token() }}">
@auth
    <meta name="api-token" content="{{ Auth::user()->api_token }}">
@endauth

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">

    <title>{{ env('APP_NAME') }}</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../ico/favicon.ico">

    @yield("og")

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

{{--    <script src="//code.jivo.ru/widget/Fp7nrxvh0u" async></script>--}}
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="horizontal-layout horizontal-menu blank-page navbar-floating footer-static" data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-basic px-2 container" style="display: block">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    @include('common.template.service_container')

    <script src="{{ mix('js/app.js') }}" defer></script>
    @if(!env('APP_DEBUG'))
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(88949577, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/88949577" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    @endif
</body>
<!-- END: Body-->

</html>
