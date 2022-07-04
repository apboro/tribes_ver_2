<div class="main-menu menu-light menu-fixed menu-shadow offcanvas offcanvas-start mt-3" id="offcanvas" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
    
    <!-- Horizontal menu content-->
    <div class="navbar-container main-menu-content ps" data-menu="menu-container">
        <!-- include ../../../includes/mixins-->
        <ul class="navigation navigation-main mt-1" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ request()->is('*community*') ? 'active' : ''  }}">
                <a class="nav-link d-flex align-items-center" href="{{ route('community.list') }}">
                    <i data-feather="file-text" class="font-medium-2"></i>
                    <span data-i18n="Dashboards">
                        {{ __('base.communities') }}
                    </span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('*payment*') ? 'active' : ''  }} mt-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('payment.list') }}">
                    <i data-feather="dollar-sign" class="font-medium-2"></i>
                    <span data-i18n="Apps">
                        {{ __('base.finance') }}
                    </span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('*courses*') ? 'active' : ''  }} mt-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('course.list') }}">
                    <i data-feather="book-open" class="font-medium-2"></i>
                    <span data-i18n="Apps">
                        {{ __('base.mediaProducts') }}
                    </span>
                </a>
            </li>

{{--            <li class="nav-item {{ request()->is('*audience*') ? 'active' : ''  }} mt-1">--}}
{{--                <a class="nav-link d-flex align-items-center" href="{{ route('audience.list') }}">--}}
{{--                    <i data-feather="users" class="font-medium-2"></i>--}}
{{--                    <span data-i18n="Apps">--}}
{{--                        {{ __('base.audience') }}--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <li class="nav-item {{ request()->is('*faq*') ? 'active' : ''  }} mt-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('faq.index') }}">
                    <i data-feather="help-circle" class="font-medium-2"></i>
                    <span data-i18n="User Interface">
                        {{ __('base.help') }}
                    </span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('*education*') ? 'active' : ''  }} mt-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('education.index') }}">
                    <i data-feather="file" class="font-medium-2"></i>
                    <span data-i18n="Forms &amp; Tables">
                        {{ __('base.education') }}
                    </span>
                </a>
            </li>
        </ul>

        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>

        <div class="ps__rail-y" style="top: 0px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </div>
</div>