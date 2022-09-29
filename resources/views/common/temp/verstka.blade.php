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


            <!-- START добавление сообщества -->
            <div class="community-messenger">
                <div class="community-messenger__item-wrap">
                    <div class="community-messenger__item">
                        <div class="community-messenger__item-image">
                            <img src="/images/icons/social/telegram2.png" alt="Telegram">
                            <h3 class="community-messenger__title">Telegram</h3>
                        </div>
                        <div class="community-messenger__item-description">
                            <p class="community-messenger__text">Что добавляем?</p>
                            <div class="community-messenger__buttons">
                                <a href="#" class="button-empty button-empty--telegram" href="">Канал</a>
                                <a href="#" class="button-empty button-empty--telegram" href="">Группа (чат)</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END добавление сообщества -->



            <!-- START подключение Телеграмм-канала, бота -->
            <div class="channel-connection">
                <div class="channel-connection__instructions-adding-bot">
                    <div class="channel-connection__add-bot">
                        <p>Добавьте бота в администраторы канала и выдайте ему права на приглашение подписчиков</p>
                        <div class="channel-connection__copy">
                            {{ __('community.our_bot') }}
                            <span class="channel-connection__copy--titleBot">{{ '@' . env('TELEGRAM_BOT_NAME') }}</span>  — 
                            <span
                                class="channel-connection__copy--text"
                                onclick="copyText('{{ '@' . env('TELEGRAM_BOT_NAME') }}')"
                            >
                                {{ __('base.copy') }}
                            </span>
                        </div>
                    </div>

                    <p class="channel-connection__interpretation">В результатах поиска  могут отображаться и другие боты похожие на наш. Обратите внимание на аватар. </p>

                    <div class="channel-connection__img-bot">
                        <p>У нашего бота он такой:</p>
                        <img src="/images/robot.png" alt="Robot-bot">
                    </div>
                </div>

                <div class="channel-connection__add-channel">

                    <div class="channel-connection__add-channel-wrap">
                        <div class="channel-connection__connected-community">
                            <div class="channel-connection__image">
                                <img src="/images/avatars/1.png">
                            </div>
                            <div class="channel-connection__description">
                                <p class="channel-connection__channel">Канал Димы Коваля</p>
                                <div class="channel-connection__messenger">
                                    <img src="/images/icons/social/telegram.png">
                                    <p class="profile__text">мессенджер</p>
                                </div>
                            </div>
                        </div>
                        <span class="channel-connection__connected">Подключено</span>
                    </div>
                    <a href="#" class="button-empty button-empty--primary">Перейти к списку подключённых сообществ</a>
                </div>
            </div>
            <!-- END подключение Телеграмм-канала, бота -->


            <!-- START страница папок с проектами, создать проект -->
            <div class="page-projects">
                <div class="page-projects__folder-wrap">
                    <div class="page-projects__folder-top">
                        <div class="parallelogram"></div>
                        <div class="parallelogram pink"></div>
                    </div>
                    <div class="page-projects__folder">
                        <div class="page-projects__folder--top">
                            <p class="page-projects__folder-project">Проект</p>
                            <h5 class="page-projects__folder-project-name">Секретный канал It`s Time</h5>
                        </div>
                        <div class="page-projects__folder--bottom">
                            <div class="page-projects__folder-images">
                                <div class="page-projects__folder-image">
                                    <img src="/images/avatars/1.png" alt="Avatar">
                                </div>
                            </div>
                            <p class="page-projects__folder-communities-qty">Сообществ: 4</p>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END страница папок с проектами, создать проект -->


            <!-- START создание проекта -->
            <div class="project-creation">
                <div class="project-creation__communities">
                    <div class="project-creation__communities-main">
                        <p class="project-creation__communities-another">Другие сообщества</p>
                        <div class="project-creation__communities-list-another">
                        

                            <!-- START список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->
                            <div id="profile-list" class="profile__list">
                                <a href="#" class="profile__item-wrap" id="community_1" style="width: 100%">
                                    <label for="community-item_1" class="profile__item">
                                        <div class="profile__item-image">
                                            <img class="profile__image" src="/images/avatars/1.png">
                                        </div>
                                        <div class="profile__item-text">
                                            <p class="profile__channel">Tech in UK</p>
                                            <div class="profile__messenger">
                                                <img src="/images/icons/social/telegram.png">
                                                <p class="profile__text">мессенджер</p>
                                            </div>
                                        </div>
                                    </label>
                                </a>
                                <a href="#" class="profile__item-wrap" id="community_1" style="width: 100%">
                                    <label for="community-item_1" class="profile__item">
                                        <div class="profile__item-image">
                                            <img class="profile__image" src="/images/avatars/2.png">
                                        </div>
                                        <div class="profile__item-text">
                                            <p class="profile__channel">Tech in UK</p>
                                            <div class="profile__messenger">
                                                <img src="/images/icons/social/telegram.png">
                                                <p class="profile__text">мессенджер</p>
                                            </div>
                                        </div>
                                    </label>
                                </a>
                                <a href="#" class="profile__item-wrap" id="community_1" style="width: 100%">
                                    <label for="community-item_1" class="profile__item">
                                        <div class="profile__item-image">
                                            <img class="profile__image" src="/images/avatars/3.png">
                                        </div>
                                        <div class="profile__item-text">
                                            <p class="profile__channel">Tech in UK</p>
                                            <div class="profile__messenger">
                                                <img src="/images/icons/social/telegram.png">
                                                <p class="profile__text">мессенджер</p>
                                            </div>
                                        </div>
                                    </label>
                                </a>
                            </div>
                            <!-- END список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->

                        </div>
                    </div>
                    <div class="project-creation__communities-footer">
                        <p class="project-creation__project-communities">Выбрано: <span class="qty">1</span></p>
                        <button class="button-empty button-empty--primary">Добавить</button>
                    </div>
                </div>
                <div class="project-creation__project">
                    <div class="project-creation__project--top">
                        <div class="project-creation__project-name">
                            <label for="projects-name">Название проекта</label>
                            <select name="" id="projects-name">
                                <option value="">Tech in UK</option>
                                <option value="">Tech in UK 2</option>
                                <option value="">Tech in UK 3</option>
                            </select>
                        </div>
                        <button class="button-filled button-filled--primary project-creation__save">Сохранить</button>
                    </div>
                    <div class="project-creation__project--bottom">
                        <div class="project-creation__project-header">
                            <p class="project-creation__project-communities">Сообщества проекта: <span class="qty">0</span></p>
                            <label><input type="checkbox" name="community" class="chk-all">Выбрать все</label>
                        </div>
                        <div class="project-creation__project-main">
                            <p class="project-creation__project-main--empty">Здесь находится список сообществ проекта, выберите сообщества из общего списка (слева) и добавьте их в свой проект.</p>
                            <div class="project-creation__list-communities">




                            <!-- START список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->
                            <div id="profile-list" class="profile__list">
                                <a href="#" class="profile__item-wrap" id="community_1" style="width: 33.3%">
                                    <label for="community-item_1" class="profile__item">
                                        <div class="profile__item-image">
                                            <img class="profile__image" src="/images/avatars/1.png">
                                        </div>
                                        <div class="profile__item-text">
                                            <p class="profile__channel">Tech in UK</p>
                                            <div class="profile__messenger">
                                                <img src="/images/icons/social/telegram.png">
                                                <p class="profile__text">мессенджер</p>
                                            </div>
                                        </div>
                                    </label>
                                </a>
                                <a href="#" class="profile__item-wrap" id="community_1" style="width: 33.3%">
                                    <label for="community-item_1" class="profile__item">
                                        <div class="profile__item-image">
                                            <img class="profile__image" src="/images/avatars/2.png">
                                        </div>
                                        <div class="profile__item-text">
                                            <p class="profile__channel">Tech in UK</p>
                                            <div class="profile__messenger">
                                                <img src="/images/icons/social/telegram.png">
                                                <p class="profile__text">мессенджер</p>
                                            </div>
                                        </div>
                                    </label>
                                </a>
                                <a href="#" class="profile__item-wrap" id="community_1" style="width: 33.3%">
                                    <label for="community-item_1" class="profile__item">
                                        <div class="profile__item-image">
                                            <img class="profile__image" src="/images/avatars/3.png">
                                        </div>
                                        <div class="profile__item-text">
                                            <p class="profile__channel">Tech in UK</p>
                                            <div class="profile__messenger">
                                                <img src="/images/icons/social/telegram.png">
                                                <p class="profile__text">мессенджер</p>
                                            </div>
                                        </div>
                                    </label>
                                </a>
                                <a href="#" class="profile__item-wrap" id="community_1" style="width: 33.3%">
                                    <label for="community-item_1" class="profile__item">
                                        <div class="profile__item-image">
                                            <img class="profile__image" src="/images/avatars/3.png">
                                        </div>
                                        <div class="profile__item-text">
                                            <p class="profile__channel">Tech in UK</p>
                                            <div class="profile__messenger">
                                                <img src="/images/icons/social/telegram.png">
                                                <p class="profile__text">мессенджер</p>
                                            </div>
                                        </div>
                                    </label>
                                </a>
                            </div>
                            <!-- END список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->



                            </div>
                        </div>
                        <div class="project-creation__project-footer">
                            <p class="project-creation__project-communities">Выбрано сообществ: <span class="qty">0</span></p>
                            <button class="button-empty button-empty--primary">Убрать из проекта</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END создание проекта -->

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
