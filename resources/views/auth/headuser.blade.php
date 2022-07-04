@auth
    <ul class="nav navbar-nav align-items-center ms-auto">
        <li class="nav-item dropdown dropdown-user">
            <a
                class="nav-link dropdown-toggle dropdown-user-link"
                id="dropdown-user"
                href="#"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
            >
                <div class="user-nav d-sm-flex d-none">
                    <span
                        class="user-name fw-bolder m-0 truncate truncate__header-name"
                        title="{{ Auth::user()->name }}"
                    >
                        {{ Auth::user()->name }}
                    </span>
                    <!--<span class="user-status">Автор</span>-->
                </div>
                
                <span class="avatar">
                    <div class="avatar-content">{{ Auth::user()->getFirstLettersOfName() }}</div>
                    <span class="avatar-status-online"></span>
                </span>
            </a>

            <!-- Drop menu -->
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                @if (request()->is('*follower*'))
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('community.list') }}">
                        <i data-feather="repeat" class="font-medium-2 me-50"></i>
                        {{ __('base.panel_author') }}
                    </a>

                    <div class="dropdown-divider"></div>
                
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('follower.mobile.form') }}">
                        <i data-feather="user" class="font-medium-2 me-50"></i>
                        {{ __('base.profile') }}
                    </a>
                @else
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('follower.products') }}">
                        <i data-feather="repeat" class="font-medium-2 me-50"></i>
                        {{ __('base.panel_follower') }}
                    </a> 

                    <div class="dropdown-divider"></div>
                
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('author.profile') }}">
                        <i data-feather="user" class="font-medium-2 me-50"></i>
                        {{ __('base.profile') }}
                    </a>
                @endif
                
                <div class="dropdown-divider"></div>

                <a
                    class="dropdown-item d-flex align-items-center"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                >
                    <i data-feather="power" class="font-medium-2 me-50"></i>
                    {{ __('base.exit') }}
                </a>
            </div>
        </li>
    </ul>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endauth
