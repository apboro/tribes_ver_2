<nav class="main-nav-red main-header-red__main-nav">
    <ul class="main-nav-red__list">
        @if (request()->is('*follower*'))
        <li class="main-nav-red__item">
            <a
                href="{{ route('follower.product', ['hash' => 123]) }}"
                class="main-nav-red__link {{ request()->is('*product*') ? 'active' : ''  }}"
            > 
                <span>{{ __('base.product') }}</span>
            </a>
        </li>

        <li class="main-nav-red__item">
            <a
                href="{{ route('audience.list') }}"
                class="main-nav-red__link {{ request()->is('*audience*') ? 'active' : ''  }}"
            >
                <span>{{ __('base.audience') }}</span>
            </a>
        </li>

        <li class="main-nav-red__item">
            <a
                href="{{ route('faq.index') }}"
                class="main-nav-red__link {{ request()->is('*faq*') ? 'active' : ''  }}"
            >
                <span>{{ __('base.help') }}</span>
            </a>
        </li>
        @else
        <!-- Сообщества -->
        <li class="main-nav-red__item">
            <a
                href="{{ route('community.list') }}"
                class="main-nav-red__link {{ request()->is('*community*') ? 'active' : ''  }}"
            >
                <span>{{ __('base.communities') }}</span>
            </a>
        </li>
        <!-- Медиаконтент -->
        <li class="main-nav-red__item">
            <a
                href="{{ route('course.list') }}"
                class="main-nav-red__link {{ request()->is('*courses*') ? 'active' : ''  }}"
            >
                <span>
                    Медиаконтент
                </span>
            </a>
        </li>
        <!-- Финансы -->
        <li class="main-nav-red__item">
            <a
                href="{{ route('payment.list') }}"
                class="main-nav-red__link {{ request()->is('*payments*') ? 'active' : ''  }}"
            >
                <span>{{ __('base.finance') }}</span>
            </a>
        </li>
        <!-- FAQ -->
        <li class="main-nav-red__item">
            <a
                href="{{ route('faq.index') }}"
                class="main-nav-red__link {{ request()->is('*faq*') ? 'active' : ''  }}"
            >
                <span>{{ __('base.help') }}</span>
            </a>
        </li>
        <!-- Справка -->
        <li class="main-nav-red__item">
            <a
                href="{{ route('education.index') }}"
                class="main-nav-red__link {{ request()->is('*education*') ? 'active' : ''  }}"
            >
                <span>{{ __('base.education') }}</span>
            </a>
        </li>

        <li class="main-nav-red__item">
            @include('auth.headuser-red')
        </li>
        @endif
    </ul>
</nav>