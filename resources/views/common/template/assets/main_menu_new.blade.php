<div class="main-nav-new main-header-red__main-nav">
    <button
            class="main-nav-new__burger-btn"
            onclick="Burger.toggle()"
    >
        <span class="main-nav-new__burger-lines"></span>
    </button>
    <nav class="main-nav-new__nav" data-burger>
        <ul class="main-nav-new__list">
            @if (request()->is('*follower*'))
                <li class="main-nav-new__item">
                    <a
                            href="{{ route('follower.product', ['hash' => 123]) }}"
                            class="main-nav-new__link {{ request()->is('*product*') ? 'active' : ''  }}"
                    >
                        <span>{{ __('base.product') }}</span>
                    </a>
                </li>

                <li class="main-nav-new__item">
                    <a
                            href="{{ route('audience.list') }}"
                            class="main-nav-new__link {{ request()->is('*audience*') ? 'active' : ''  }}"
                    >
                        <span>{{ __('base.audience') }}</span>
                    </a>
                </li>

                <li class="main-nav-new__item">
                    <a
                            href="{{ route('faq.index') }}"
                            class="main-nav-new__link {{ request()->is('*faq*') ? 'active' : ''  }}"
                    >
                        <span>{{ __('base.help') }}</span>
                    </a>
                </li>
            @else
                <!-- Аналитика -->
                <li class="main-nav-new__item">
                    <a
                            href="{{ route('project.analytics', array_filter([ 'project' => isset($activeProject)? $activeProject->id :(isset($activeCommunity)?'c':''), 'community'=> isset($activeCommunity)?$activeCommunity->id:''])) }}"
                            class="main-nav-new__link {{ request()->is('*analytics*') ? 'active' : ''  }}"
                    >
                        <span>Аналитика</span>
                    </a>
                </li>

                <!-- Монетизация -->
                <li class="main-nav-red__item">
                    <div class="dropdown-menu-item main-nav-new__dropdown">
                        <button
                                class="dropdown-menu-item__head main-nav-new__link"
                                data-dropdown-btn
                                onclick="Dropdown.toggle(this)"
                        >
                        <span class="dropdown-menu-item__name main-nav-new__link">
                            Монетизация
                        </span>

                            <i class="main-nav-new__arrow">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path class="icon__fill"
                                          d="M3.53317 5.53317C3.6665 5.39984 3.79984 5.33317 3.99984 5.33317C4.19984 5.33317 4.33317 5.39984 4.4665 5.53317L7.99984 9.0665L11.5332 5.53317C11.7998 5.2665 12.1998 5.2665 12.4665 5.53317C12.7332 5.79984 12.7332 6.19984 12.4665 6.4665L8.4665 10.4665C8.19984 10.7332 7.79984 10.7332 7.53317 10.4665L3.53317 6.4665C3.2665 6.19984 3.2665 5.79984 3.53317 5.53317Z"
                                          fill="#4C4957"/>
                                </svg>
                            </i>
                        </button>

                        <ul class="dropdown-menu-item__list" data-dropdown-content>
                            <li class="dropdown-menu-item__item">
                                <a
                                        class="dropdown-menu-item__link {{ request()->is('*donates*') ? 'active' : ''  }}"
                                        href="{{ route('project.donates', array_filter([ 'project' => isset($activeProject)? $activeProject->id :(isset($activeCommunity)?'c':''), 'community'=> isset($activeCommunity)?$activeCommunity->id:''])) }}"
                                >
                                    <span>Донаты</span>
                                </a>
                            </li>

                            <div class="dropdown-menu-item__divider"></div>

                            <li class="dropdown-menu-item__item">
                                <a
                                        class="dropdown-menu-item__link {{ request()->is('*tariffs*') ? 'active' : ''  }}"
                                        href="{{ route('project.tariffs', array_filter([ 'project' => isset($activeProject)? $activeProject->id :(isset($activeCommunity)?'c':''), 'community'=> isset($activeCommunity)?$activeCommunity->id:''])) }}"
                                >
                                    <span>Тарифы</span>
                                </a>
                            </li>

                            <div class="dropdown-menu-item__divider"></div>

                            <li class="dropdown-menu-item__item">
                                <a
                                        class="dropdown-menu-item__link {{ request()->is('*members*') ? 'active' : ''  }}"
                                        href="{{ route('project.members', array_filter([ 'project' => isset($activeProject)? $activeProject->id :(isset($activeCommunity)?'c':''), 'community'=> isset($activeCommunity)?$activeCommunity->id:''])) }}"
                                >
                                    <span>Участники</span>
                                </a>
                            </li>

                            <div class="dropdown-menu-item__divider"></div>

                            <li class="dropdown-menu-item__item">
                                <a
                                        class="dropdown-menu-item__link {{ request()->is('*courses*') ? 'active' : ''  }}"
                                        href="{{ route('course.list') }}"
                                >
                                    <span>Медиатовары</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                <!-- База знаний -->
                    <li class="main-nav-new__item">
                            <a
                                    href="{{ route('project.knowledge', array_filter([ 'project' => isset($activeProject)? $activeProject->id :(isset($activeCommunity)?'c':''), 'community'=> isset($activeCommunity)?$activeCommunity->id:''])) }}"
                                    class="main-nav-new__link {{ request()->is('*knowledge/list') ? 'active' : ''  }}"
                            >
                            <span>База знаний</span>
                            </a>
                    </li>

                <!-- Финансы -->
                <li class="main-nav-new__item">
                    <a
                            href="{{ route('payment.list') }}"
                            class="main-nav-new__link {{ request()->is('*payments/outcome', '*payments/income', '*payments/card') ? 'active' : ''  }}"
                    >
                        <span>Финансы</span>
                    </a>
                </li>
                <!-- FAQ будущая Помощь -->
                <li class="main-nav-new__item">
                    <a
                            href="{{ route('faq.index') }}"
                            class="main-nav-new__link {{ request()->is('*faq*') ? 'active' : ''  }}"
                    >
                        <span>Помощь</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <div class="main-nav-new__overlay" onclick="Burger.toggle()"></div>
</div>
