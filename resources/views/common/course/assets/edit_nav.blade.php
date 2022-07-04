<ul class="nav nav-pills mb-1 gap-1">
    <li class="nav-item">    
    <a
            class="nav-link {{ request()->is('*courses/*/common*') ? 'active' : '' }}"
            href="{{ route('course.edit.common') }}"
        >
            <i data-feather="layout" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                Основное
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a
            class="nav-link {{ request()->is('*courses/*/public*') ? 'active' : '' }}"
            href="{{ route('course.edit.public') }}"
        >
            <i data-feather="layers" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                Публичная часть
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a
            class="nav-link {{ request()->is('*courses/*/education_materials*') ? 'active' : '' }}"
            href="{{ route('course.edit.education_materials') }}"
        >
            <i data-feather="book-open" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                Учебные материалы
            </span>
        </a>
    </li>

    <li class="nav-item"
    >
        <a
            class="nav-link {{ request()->is('*courses/*/tariffs*') ? 'active' : '' }}"
            href="{{ route('course.edit.tariffs') }}"
        >
            <i data-feather="dollar-sign" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                Тарифы
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a
            class="nav-link {{ request()->is('*courses/*/settings*') ? 'active' : '' }}"
            href="{{ route('course.edit.settings') }}"
        >
            <i data-feather="settings" class="font-medium-3 me-50"></i>
            <span class="fw-bold">
                Настройки
            </span>
        </a>
    </li>
</ul>
