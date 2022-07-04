<ul class="nav nav-pills mb-1 gap-1">
    <li class="nav-item">    
        <a
            class="nav-link {{ request()->is('*lesson/*/common*') ? 'active' : '' }}"
            href="{{ route('lesson.edit.common') }}"
        >
            <i data-feather="layout" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                Основное
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a
            class="nav-link "
            href="/"
        >
            <i data-feather="layers" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                ?
            </span>
        </a>
    </li>
</ul>
