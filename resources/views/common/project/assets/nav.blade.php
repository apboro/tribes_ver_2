<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs">
            @if(Auth::user()->hasCommunities())
            <li class="nav-item vertical_line">
                <a
                    class="nav-link {{ request()->is('*profile/projects*') ? 'active_projects_communities' : ''  }}"
                    href="{{route('profile.project.list')}}"
                    aria-controls="projects"
                >
                    {{ __('base.projects') }}
                </a>
            </li>
            @endif
            <li class="nav-item vertical_line">
                <a
                    class="nav-link {{ request()->is('*profile/communities*') ? 'active_projects_communities' : ''  }}"
                    href="{{route('profile.communities.list')}}"
                    aria-controls="communities"
                >
                    {{ __('base.communities') }}
                </a>
            </li>
        </ul>
    </div>
</div>
