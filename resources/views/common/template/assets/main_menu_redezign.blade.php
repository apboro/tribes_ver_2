<nav class="main-nav-red main-header-red__main-nav">
    <ul class="main-nav-red__list">
        @if (request()->is('*follower*'))
        <li class="main-nav-red__item">
            <a href="{{ route('follower.product', ['hash' => 123]) }}" class="main-nav-red__link {{ request()->is('*product*') ? 'active' : ''  }}"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text font-medium-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <span>{{ __('base.product') }}</span>
            </a>
        </li>

        <li class="main-nav-red__item">
            <a href="{{ route('audience.list') }}" class="main-nav-red__link {{ request()->is('*audience*') ? 'active' : ''  }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span>{{ __('base.audience') }}</span>
            </a>
        </li>

        <li class="main-nav-red__item">
            <a href="{{ route('faq.index') }}" class="main-nav-red__link {{ request()->is('*faq*') ? 'active' : ''  }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle font-medium-2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <span>{{ __('base.help') }}</span>
            </a>
        </li>
        @else
        <!-- Сообщества -->
        <li class="main-nav-red__item">
            <a href="{{ route('community.list') }}" class="main-nav-red__link {{ request()->is('*community*') ? 'active' : ''  }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text font-medium-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <span>{{ __('base.communities') }}</span>
            </a>
        </li>
        <!-- Медиаконтент -->
        <li class="main-nav-red__item">
            <a class="main-nav-red__link {{ request()->is('*courses*') ? 'active' : ''  }}" href="{{ route('course.list') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign font-medium-2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <span>
                    Медиаконтент
                </span>
            </a>
        </li>
        <!-- Финансы -->
        <li class="main-nav-red__item">
            <a href="{{ route('payment.list') }}" class="main-nav-red__link {{ request()->is('*payments*') ? 'active' : ''  }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign font-medium-2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <span>{{ __('base.finance') }}</span>
            </a>
        </li>
        <!-- FAQ -->
        <li class="main-nav-red__item">
            <a href="{{ route('faq.index') }}" class="main-nav-red__link {{ request()->is('*faq*') ? 'active' : ''  }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle font-medium-2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <span>{{ __('base.help') }}</span>
            </a>
        </li>
        <!-- Справка -->
        <li class="main-nav-red__item">
            <a href="{{ route('education.index') }}" class="main-nav-red__link {{ request()->is('*education*') ? 'active' : ''  }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6e6b7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle font-medium-2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <span>{{ __('base.education') }}</span>
            </a>
        </li>
        @endif
    </ul>

    <button class="main-nav-red__burger-icon" onclick="Burger.toggle()">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu ficon"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>
</nav>