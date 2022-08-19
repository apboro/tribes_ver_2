@auth
    <div class="dropdown-lk main-header__dropdown" data-plugin="Headuser">
        <button class="dropdown-lk__head" data-dropdown-btn onclick="Dropdown.toggle(this)">
            <span
                class="dropdown-lk__name"
                title="{{ Auth::user()->name }}"
            >
                Личный кабинет
            </span>
        </button>

        <ul class="dropdown-lk__list" data-dropdown-content>
            @if (request()->is('*follower*'))
                <li class="dropdown-lk__item">
                    <a class="dropdown__link" href="{{ route('community.list') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-repeat"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                        <span>{{ __('base.panel_author') }}</span>
                    </a>
                </li>

                <div class="dropdown-lk__divider"></div>

                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('follower.mobile.form') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user font-medium-2 me-50"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>{{ __('base.profile') }}</span>
                    </a>
                </li>
            @else
                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('follower.products') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-repeat"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                        <span>{{ __('base.panel_follower') }}</span>
                    </a> 
                </li>

                <div class="dropdown-lk__divider"></div>
            
                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="{{ route('author.profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user font-medium-2 me-50"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>{{ __('base.profile') }}</span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="dropdown-lk__divider"></div>

                <li class="dropdown-lk__item">
                    <a class="dropdown-lk__link" href="/manager/users">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-power font-medium-2 me-50"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-power font-medium-2 me-50"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                        <span>{{ __('auth.back_as_admin') }}</span>
                    </a>
                </li>
            @endif

            <div class="dropdown-lk__divider"></div>

            <li class="dropdown-lk__item">
                <a
                    class="dropdown-lk__link"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-power font-medium-2 me-50"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                    <span>{{ __('base.exit') }}</span>
                </a>
            </li>
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
