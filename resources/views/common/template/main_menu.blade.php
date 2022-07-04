<div class="horizontal-menu-wrapper">
    <!-- Mobile menu -->
    @include('common.template.mobile_menu')

    {{--<div
        class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-light navbar-shadow menu-border container-xxl"
        role="navigation"
        data-menu="menu-wrapper"
        data-menu-type="floating-nav"
    >
        <!-- Horizontal menu content-->
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <!-- Menu -->
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item {{ request()->is('community*') ? 'active' : ''  }}">
                    <a class="nav-link d-flex align-items-center" href="{{ route('community.list') }}">
                        <i data-feather="file-text" class="font-medium-2"></i>
                        <span data-i18n="Dashboards">
                            {{ __('base.communities') }}
                        </span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('cash') ? 'active' : ''  }}">
                    <a class="nav-link d-flex align-items-center" href="{{ route('payment.list') }}">
                        <i data-feather="dollar-sign" class="font-medium-2"></i>
                        <span data-i18n="Apps">
                            {{ __('base.finance') }}
                        </span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('help') ? 'active' : ''  }}">
                    <a class="nav-link d-flex align-items-center" href="{{ route('help.index') }}">
                        <i data-feather="help-circle" class="font-medium-2"></i>
                        <span data-i18n="User Interface">
                            {{ __('base.help') }}
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="">
                        <i data-feather="file" class="font-medium-2"></i>
                        <span data-i18n="Forms &amp; Tables">
                            {{ __('base.education') }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>--}}
</div>
