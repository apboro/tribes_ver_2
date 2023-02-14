<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a
                    class="nav-link {{ request()->is('*profile/mobile*') ? 'active' : ''  }}"
                    href="{{ route('author.mobile.form') }}"
                    aria-controls="messengers"
                >
                    {{ __('author.mobile') }}
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ request()->is('*profile/messengers*') ? 'active' : ''  }}"
                    href="{{ route('author.messenger.list') }}"
                    aria-controls="messengers"
                >
                    {{ __('author.messengers') }}
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ request()->is('*profile/password*') ? 'active' : ''  }}"
                    href="{{ route('author.password.form') }}"
                    aria-controls="change-password"
                >
                    {{ __('author.password_change') }}
                </a>
            </li>
            <li class="nav-item">
                <a
                        class="nav-link {{ request()->is('*courses*') ? 'active' : ''  }}"
                        href="{{ route('course.list') }}"
                        aria-controls="change-password"
                >
                    {{ __('base.mediaProducts') }}
                </a>
            </li>
        </ul>
    </div>
</div>
