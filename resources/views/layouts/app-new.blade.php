<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">
<head>
    @include('common.template.head')
</head>

<body>
    <!-- Header-->
    @include('common.template.header')

    <!-- Main Menu-->
    @include('common.template.main_menu')

    <!-- Content -->
    <main class="app-content content">
        <div class="content-overlay"></div>
        @yield('content')
    </main>

    <!-- Footer-->
    @include('common.template.footer2')
    
    <!-- Service container -->
    @include('common.template.service_container')

    <!-- Scripts
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @if(!env('APP_DEBUG'))
    <!-- Yandex.Metrika counter -->
    <!-- <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(88949577, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script> -->
    <noscript><div><img src="https://mc.yandex.ru/watch/88949577" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    @endif
</body>
</html>
