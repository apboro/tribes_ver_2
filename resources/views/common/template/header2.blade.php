<header data-lock-element>
    <nav class="header-navbar navbar-expand-lg navbar navbar-fixed navbar-shadow navbar-brand-center d-flex justify-content-center" data-nav="brand-center">
        <div class="container-xxl w-100 d-flex">
    
            <!-- Horizontal menu content-->
            <div class="navbar-container main-menu-content d-none d-xl-block px-0" data-menu="menu-container">
                    <!-- Menu -->
                    <ul class="nav navbar-nav flex-nowrap" id="main-menu-navigation" data-menu="menu-navigation">

                        @if (request()->is('*follower*'))
                            <li class="nav-item {{ request()->is('*product*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('follower.products') }}">
                                    <i data-feather="file-text" class="font-medium-2"></i>
                                    <span data-i18n="Apps">
                                        {{ __('base.mediaProducts') }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('*faq*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('follower.faq.index') }}">
                                    <i data-feather="help-circle" class="font-medium-2"></i>
                                    <span data-i18n="User Interface">
                                        {{ __('base.help') }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('*education*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('follower.education.index') }}">
                                    <i data-feather="file" class="font-medium-2"></i>
                                    <span data-i18n="Forms &amp; Tables">
                                        {{ __('base.education') }}
                                    </span>
                                </a>
                            </li>

                        @else
                            <li class="nav-item {{ request()->is('*community*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('community.list') }}">
                                    <i data-feather="file-text" class="font-medium-2"></i>
                                    <span data-i18n="Dashboards">
                                        {{ __('base.communities') }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('*courses*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('course.list') }}">
                                    <i data-feather="book-open" class="font-medium-2"></i>
                                    <span data-i18n="Dashboards">
                                       {{ __('base.mediaProducts') }}
                                    </span>
                                </a>
                            </li>
                            
                            <li class="nav-item {{ request()->is('*payments*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('payment.list') }}">
                                    <i data-feather="dollar-sign" class="font-medium-2"></i>
                                    <span data-i18n="Apps">
                                        {{ __('base.finance') }}
                                    </span>
                                </a>
                            </li>

{{--                            <li class="nav-item {{ request()->is('*audience*') ? 'active' : ''  }}">--}}
{{--                                <a class="nav-link d-flex align-items-center" href="{{ route('audience.list') }}">--}}
{{--                                    <i data-feather="users" class="font-medium-2"></i>--}}
{{--                                    <span data-i18n="Apps">--}}
{{--                                        {{ __('base.audience') }}--}}
{{--                                    </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            
                            <li class="nav-item {{ request()->is('*faq*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('faq.index') }}">
                                    <i data-feather="help-circle" class="font-medium-2"></i>
                                    <span data-i18n="User Interface">
                                        {{ __('base.help') }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('*education*') ? 'active' : ''  }}">
                                <a class="nav-link d-flex align-items-center" href="{{ route('education.index') }}">
                                    <i data-feather="file" class="font-medium-2"></i>
                                    <span data-i18n="Forms &amp; Tables">
                                        {{ __('base.education') }}
                                    </span>
                                </a>
                            </li>
                        @endif

                        
                    </ul>
                </div>

            <div class="navbar-header d-none">
                    <ul class="nav navbar-nav">
                        <li class="nav-item">
                            <!-- Logo -->
                            <a class="navbar-brand" href="{{ route('community.list') }}">
                                <h2 class="brand-text px-0">Телеграм и дискорд бот</h2>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Headuser -->
                <div class="navbar-container d-flex align-items-center content px-0">
                    <div class="bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav d-xl-none">
                            <li class="nav-item">
                                <a class="nav-link menu-toggle" data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas">
                                    <i class="ficon" data-feather="menu"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-10 d-inline-block ms-auto">
                        <div class="d-flex justify-content-end align-items-center">
                            @php $locale = App\Http\Middleware\LocaleMiddleware::getLocale() @endphp
                            <ul class="nav navbar-nav align-items-center ms-auto">
                                <li class="nav-item dropdown dropdown-user">
{{--                                    <a--}}
{{--                                        class="nav-link dropdown-toggle dropdown-user-link"--}}
{{--                                        id="dropdown-lang"--}}
{{--                                        href="#"--}}
{{--                                        data-bs-toggle="dropdown"--}}
{{--                                        aria-haspopup="true"--}}
{{--                                        aria-expanded="false"--}}
{{--                                    >--}}
{{--                                        <span class="flag-icon">--}}
{{--                                            <img src="/ico/flag-{{ $locale ?? 'ru' }}.svg" alt="" class="w-100 h-100">--}}
{{--                                        </span>--}}
{{--                                    </a>--}}

                                    <!-- Drop menu -->
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-lang">
                                        <button class="dropdown-item d-flex align-items-center w-100" value="ru" onclick = "window.setLocale(this)">
                                            <span class="flag-icon me-1">
                                                <img src="/ico/flag-ru.svg" alt="" class="w-100 h-100">
                                            </span>
                                            Русский
                                        </button>

                                        <div class="dropdown-divider"></div>

                                        <button class="dropdown-item d-flex align-items-center w-100" value="en" onclick = "window.setLocale(this)">
                                            <span class="flag-icon me-1">
                                                <img src="/ico/flag-en.svg" alt="" class="w-100 h-100">
                                            </span>
                                            English
                                        </button>
                                    </div>
                                </li>
                            </ul>
                            @if(session()->has('admin_id'))
                            <span class="badge bg-secondary">
                                Режим администратора
                            </span>
                            @endif
                            <div class="d-inline-block">
                                @include('auth.headuser')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
