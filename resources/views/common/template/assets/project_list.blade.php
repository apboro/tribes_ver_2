

<div class="dropdown-project-list">
    <button class="dropdown-project-list__header" data-dropdown-btn onclick="Dropdown.toggle(this)">
        <span class="dropdown-project-list__title">
            {{ $activeProject->title ?? $activeCommunity->title ?? 'Без имени' }}
        </span>

        <!-- <i class="dropdown-project-list__arrow">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__fill" d="M3.53317 5.53317C3.6665 5.39984 3.79984 5.33317 3.99984 5.33317C4.19984 5.33317 4.33317 5.39984 4.4665 5.53317L7.99984 9.0665L11.5332 5.53317C11.7998 5.2665 12.1998 5.2665 12.4665 5.53317C12.7332 5.79984 12.7332 6.19984 12.4665 6.4665L8.4665 10.4665C8.19984 10.7332 7.79984 10.7332 7.53317 10.4665L3.53317 6.4665C3.2665 6.19984 3.2665 5.79984 3.53317 5.53317Z" fill="#4C4957"/>
            </svg>
        </i> -->
    </button>

    <div class="dropdown-project-list__body" data-dropdown-content>
        <div class="dropdown-project-list__scroll">
            <h6 class="dropdown-project-list__category">
                Проекты
            </h6>

            <ul class="dropdown-project-list__project-list">
                @foreach($projects as $pr)

                <li class="dropdown-project-list__project-item">
                    <a href="{{route(request()->route()->getName(),['project' => $pr->id])}}" class="dropdown-project-list__project-link @if(!empty($activeProject->id) && $activeProject->id == $pr->id) active @endif" >
                        <p class="dropdown-project-list__project-link-title">{{ $pr->title }}</p>

                        <p class="dropdown-project-list__project-link-subtitle">Сообществ: {{$pr->communities()->get()->count()}}</p>
                    </a>
                </li>
                @endforeach
            </ul>

            <h6 class="dropdown-project-list__category">
                Сообщества вне проектов
            </h6>

            <ul class="dropdown-project-list__community-list">
                @foreach($communities as $community)
                    <li class="dropdown-project-list__community-item">
                            <a href="{{route(request()->route()->getName(),['project'=>'c','community' => $community->id])}}" class="dropdown-project-list__community-link">
                            <div class="dropdown-project-list__community-link-avatar">

                                <img src="{{ $community->image ? $community->image : '/images/no-image.svg' }}" alt="">
                            </div>

                            <div class="dropdown-project-list__project-link-active-wrapper">
                                <p class="dropdown-project-list__community-link-title">
                                    {{$community->title}}
                                </p>

                                <div class="dropdown-project-list__community-link-messenger dropdown-project-list__community-link-messenger--telegram">
                                    <svg height="16" viewBox="0 0 176 176" width="16" xmlns="http://www.w3.org/2000/svg"><g id="Layer_2" data-name="Layer 2"><g id="_16.telegram" data-name="16.telegram"><circle id="background" cx="88" cy="88" fill="#1c8adb" r="88"/><path id="icon" d="m135.94 45.5-1.82.66-98.78 35.59a3.31 3.31 0 0 0 .29 6.4l25.57 7 4.77 14 4.77 14a4.54 4.54 0 0 0 7.32 1.63l13.21-12.48 25.94 17.59c3.17 2.16 7.69.56 8.5-3l17.18-75.91c.84-3.76-3.12-6.85-6.95-5.48zm-12.61 16.85-44.63 36.48-2.1 1.72a2.27 2.27 0 0 0 -.84 1.48l-.47 3.88-1.29 10.9a.5.5 0 0 1 -1 .08l-3.63-10.89-3.75-11.15a2.26 2.26 0 0 1 1.08-2.67l46.44-26.62 8.74-5c1.27-.74 2.57.87 1.45 1.79z" fill="#fff"/></g></g></svg>
                                    <span>{{$community->type}}</span>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

    <div class="dropdown-project-list__overlay"></div>
</div>