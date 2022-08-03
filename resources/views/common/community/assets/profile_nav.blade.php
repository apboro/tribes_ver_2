<ul class="nav nav-pills mb-1 gap-1">
    <li class="nav-item ">
        <a class="nav-link {{ request()->is('*community/*/statistic*') ? 'active' : '' }}"
            href="{{ route('statistic.index', $community) }}"
        >
            <i data-feather="pie-chart" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                {{ __('base.statistics') }}
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('*community/*/donate*') ? 'active' : '' }}"
            href="{{ route('community.donate.list', $community) }}"
        >
            <i data-feather="gift" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                {{ __('base.donations') }}
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('*community/*/tariff*') ? 'active' : '' }}"
            href="{{ route('community.tariff.list', $community) }}"
        >
            <i data-feather="dollar-sign" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                {{ __('base.tariffs') }}
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('*community/*/knowledge*') ? 'active' : '' }}"
            href="{{ route('knowledge.index', $community) }}"
        >
            <i data-feather="database" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                {{ __('base.knowledge_base') }}
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('*community/*/subscribers') ? 'active' : '' }}"
            href="{{ route('community.tariff.subscriptions', $community) }}"
        >
            <i data-feather="book" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                {{ __('base.subscribers') }}
            </span>
        </a>
    </li>
</ul>
