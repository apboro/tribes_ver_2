<nav class="nav-community">
    <ul class="nav-community__list">
        <li class="nav-community__item">
            <a
                href="{{ route('community.statistic', $community) }}"
                class="nav-community__link {{ request()->is('*community/*/statistic*') ? 'active' : '' }}"    
            >
                {{ __('base.statistics') }}
            </a>
        </li>

        <li class="nav-community__item">
            <a
                href="{{ route('community.donate.list', $community) }}"
                class="nav-community__link {{ request()->is('*community/*/donate*') ? 'active' : '' }}"    
            >
                {{ __('base.donations') }}
            </a>
        </li>

        <li class="nav-community__item">
            <a
                href="{{ route('community.tariff.list', $community) }}"
                class="nav-community__link {{ request()->is('*community/*/tariff*') ? 'active' : '' }}"    
            >
                {{ __('base.tariffs') }}
            </a>
        </li>

        <!-- <li class="nav-community__item">
            <a
                href="{{ route('knowledge.index', $community) }}"
                class="nav-community__link {{ request()->is('*community/*/knowledge*') ? 'active' : '' }}"    
            >
                {{ __('base.knowledge_base') }}
            </a>
        </li> -->

        <li class="nav-community__item">
            <a
                href="{{ route('community.tariff.subscriptions', $community) }}"
                class="nav-community__link {{ request()->is('*community/*/subscribers*') ? 'active' : '' }}"    
            >
                {{ __('base.subscribers') }}
            </a>
        </li>
    </ul>
</nav>
