@auth
    <div class="dropdown-lk main-header__dropdown" data-plugin="Headuser">
        <button class="dropdown-lk__head" data-dropdown-btn onclick="Dropdown.toggle(this)">
            <!-- <span
                class="dropdown-lk__name"
                title="{{ Auth::user()->name }}"
            >
                Личный кабинет
            </span> -->

            <span class="dropdown-lk__avatar">
                <img src="/images/no-user-avatar.svg" alt="">
            </span>

            <i class="dropdown-lk__arrow">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon__fill" d="M3.53317 5.53317C3.6665 5.39984 3.79984 5.33317 3.99984 5.33317C4.19984 5.33317 4.33317 5.39984 4.4665 5.53317L7.99984 9.0665L11.5332 5.53317C11.7998 5.2665 12.1998 5.2665 12.4665 5.53317C12.7332 5.79984 12.7332 6.19984 12.4665 6.4665L8.4665 10.4665C8.19984 10.7332 7.79984 10.7332 7.53317 10.4665L3.53317 6.4665C3.2665 6.19984 3.2665 5.79984 3.53317 5.53317Z" fill="#4C4957"/>
                </svg>
            </i>
        </button>

        <ul class="dropdown-lk__list" data-dropdown-content>
            @if (request()->is('*follower*'))
                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('community.list') }}">
                        <span>{{ __('base.panel_author') }}</span>
                    </a>
                </li>

                <div class="dropdown-lk__divider"></div>

                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('follower.mobile.form') }}">
                        <span>{{ __('base.profile') }}</span>
                    </a>
                </li>
            @else
                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('follower.products') }}">
                        <span>Мои проекты</span>
                    </a> 
                </li>
                
                <div class="dropdown-lk__divider"></div>

                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('follower.products') }}">
                        <span>{{ __('base.panel_follower') }}</span>
                    </a> 
                </li>

                <div class="dropdown-lk__divider"></div>
            
                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('author.profile') }}">
                        <span>{{ __('base.profile') }}</span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="dropdown-lk__divider"></div>

                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="/manager/users">
                        <span>{{ __('auth.manager_panel') }}</span>
                    </a>
                </li>
            @endif

            @if(session()->has('admin_id'))
                <div class="dropdown-lk__divider"></div>

                <li class="dropdown-lk__item">
                    <a
                        class="dropdown-lk__link"
                        onclick="Headuser.loginAsAdmin()"
                    >
                        <span>{{ __('auth.back_as_admin') }}</span>
                    </a>
                </li>
            @endif

            <div class="dropdown-lk__divider"></div>

            @if(!session()->has('admin_id'))
            <li class="dropdown-lk__item">
                <a
                    class="dropdown-lk__link"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                >
                    <span>{{ __('base.exit') }}</span>
                </a>
            </li>
            @endif
        </ul>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" type="hidden">
            @csrf
        </form>
        @if(session()->has('admin_id'))
        <form id="login-as-form" action="{{ route('auth.login_as_admin') }}" method="POST" type="hidden">
            @csrf
            <input type="hidden" name="id" value="{{ session()->get('admin_id') }}">
        </form>
        @endif
    </div>
@endauth
