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
    @include('common.template.header')

    <!-- Content -->
    <main class="main">
        <div class="container">

            <div class="community-tab__header">
                <a href="http://tribes/community/1/tariff" class="button-back community-tab__prev-page-btn">
                    <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
                </a>
                <p class="community-tab__prev-page-title">Мои проекты</p>
                <h2 class="community-tab__title">Добавление сообщества</h2>
            </div>

            <div class="community-messenger">
                <div class="community-messenger__item">
                    <div class="community-messenger__item-image">
                        <img src="/images/icons/social/telegram2.png" alt="Telegram">
                    </div>
                    <div class="community-messenger__item-description">
                        <h3 class="community-messenger__title">Telegram</h3>
                        <p class="community-messenger__text">Что добавляем?</p>
                        <div class="community-messenger__buttons">
                            <a class="button-empty button-empty--telegram" href="">Канал</a>
                            <a class="button-empty button-empty--telegram" href="">Группа (чат)</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
