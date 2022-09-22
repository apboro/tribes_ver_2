<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">
<head>
    @include('common.template.head')
</head>

<body
    class=""
    data-col=""
    @if(session()->has('admin_id')) data-admin="true" @endif
>
    <!-- Header-->
    @include('common.template.header2')

    <!-- Content -->
    <main class="">
        @include('common.profile.index')

        @yield('content')
    </main>

    <!-- Footer-->
    @include('common.template.footer')
    
    <!-- Service container -->
    @include('common.template.service_container')
    
    <!-- Scripts-->
    <script src="{{ mix('js/app.js') }}" defer></script>
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
</body>
</html>
