<div class="content-header row">
    <div class="content-header-left col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-9">
                <h2 class="content-header-title float-start mb-0">
                    {{ __('base.communities') }}
                </h2>

                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('community.list') }}">
                                {{ __('base.communities') }}
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            @if(request()->is('*community/*/statistic*'))
                                {{ __('base.statistics') }}
                            @elseif(request()->is('*community/*/donate'))
                                {{ __('base.donations') }}
                            @elseif(request()->is('*community/*/donate/list*'))
                                <a href="{{ route('community.donate.list', $community) }}">
                                    {{ __('base.donations') }}
                                </a>
                            @elseif(request()->is('*community/*/tariff'))
                                {{ __('base.tariffs') }}
                            @elseif(request()->is('*community/*/tariff*'))
                                <a href="{{ route('community.tariff.list', $community) }}">
                                    {{ __('base.tariffs') }}
                                </a>
                            @elseif(request()->is('*community/*/knowledge'))
                                {{ __('base.knowledge_base') }}
                            @elseif(request()->is('*community/*/knowledge*'))
                                <a href="{{ route('knowledge.list', $community) }}">
                                    {{ __('base.knowledge_base') }}
                                </a>
                            @elseif(request()->is('*community/*/subscriptions'))
                                {{ __('base.subscriptions') }}
                            @endif
                        </li>

                        @if(request()->is('*community/*/donate/settings'))
                            <li class="breadcrumb-item">
                                {{ __('donate.settings_title') }}
                            </li>
                        @elseif(request()->is('*community/*/tariff/add'))
                            <li class="breadcrumb-item">
                                {{ __('tariff.add_tariff') }}    
                            </li>
                        @elseif(request()->is('*community/*/tariff/edit*'))
                            <li class="breadcrumb-item">
                                {{ __('tariff.change_tariff') }}
                            </li>
                        @elseif(request()->is('*community/*/tariff/settings*'))
                            <li class="breadcrumb-item">
                                {{ __('tariff.settings_title') }}
                            </li>
                        @elseif(request()->is('*community/*/knowledge/add'))
                            <li class="breadcrumb-item">
                                {{ __('knowledge.add_question_answer') }}
                            </li>
                        @elseif(request()->is('*community/*/knowledge/settings'))
                            <li class="breadcrumb-item">
                                {{ __('knowledge.knowledge_base_settings') }}
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
            <div class="col-sm-3 text-sm-end mt-1 mt-sm-0">
                <button
                    id="toggle_sidebar_btn"
                    class="btn btn-outline-primary waves-effect"
                    onclick="CommunityPage.profileBlock.sidebarVisibility.toggleSidebarVisibility()"
                >
                    @if(data::get('is_visible_sidebar') == 'false') {{ __('base.show') }} @else {{ __('base.hide') }} @endif {{ __('base.profile_low') }}
                </button>
            </div>
        </div>
    </div>
</div>
