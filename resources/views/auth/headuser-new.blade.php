@auth
    <div class="dropdown-personal main-header__dropdown" data-plugin="Headuser">
        <button class="dropdown-personal__header" data-dropdown-btn onclick="Dropdown.toggle(this)">
            <span class="dropdown-personal__avatar">
                <img src="/images/no-user-avatar.svg" alt="">
            </span>

            <i class="dropdown-personal__arrow">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon__fill" d="M3.53317 5.53317C3.6665 5.39984 3.79984 5.33317 3.99984 5.33317C4.19984 5.33317 4.33317 5.39984 4.4665 5.53317L7.99984 9.0665L11.5332 5.53317C11.7998 5.2665 12.1998 5.2665 12.4665 5.53317C12.7332 5.79984 12.7332 6.19984 12.4665 6.4665L8.4665 10.4665C8.19984 10.7332 7.79984 10.7332 7.53317 10.4665L3.53317 6.4665C3.2665 6.19984 3.2665 5.79984 3.53317 5.53317Z" fill="#4C4957"/>
                </svg>
            </i>
        </button>

        <div class="dropdown-personal__body" data-dropdown-content>
            <div class="dropdown-personal__user-block">
                <div class="dropdown-personal__user-avatar">
                    <img src="/images/no-user-avatar.svg" alt="">
                </div>

                <span class="dropdown-personal__user-name">
                    {{ Auth::user()->name }}
                </span>

                <a class="dropdown-personal__user-link link" href="#">Сменить пользователя</a>
            </div>

            <div class="dropdown-personal__links-block">
                <ul class="dropdown-personal__link-list">
                    <li class="dropdown-personal__link-item">
                        <a href="{{route('project.list')}}" class="dropdown-personal__link">
                            Мои проекты
                        </a>
                    </li>

                    <li class="dropdown-personal__link-item">
                        <a href="{{ route('author.messenger.list') }}" class="dropdown-personal__link {{ request()->is('*profile/messengers*') ? 'active' : ''  }}">
                            Мессенджеры
                        </a>
                    </li>

                    <li class="dropdown-personal__link-item">
                        <a href="{{ route('payment.list') }}" class="dropdown-personal__link {{ request()->is('*payments*') ? 'active' : ''  }}">
                            Финансы
                        </a>
                    </li>
                </ul>

                <span class="dropdown-personal__additionally">
                    Дополнительно
                </span>

                <ul class="dropdown-personal__link-list">
                    <li class="dropdown-personal__link-item">
                        <a href="" class="dropdown-personal__link">
                            Помощь
                        </a>
                    </li>

                    <li class="dropdown-personal__link-item">
                        <a
                            href="{{ route('logout') }}"
                            class="dropdown-personal__link"
                            onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                        >
                            Выход
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="dropdown-personal__overlay" onclick="Dropdown.toggle(this)"></div>
        
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
