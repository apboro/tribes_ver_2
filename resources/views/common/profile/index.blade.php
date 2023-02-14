<section class="profile" data-plugin="Profile" data-community-id="{{ $ids }}">
    <div class="profile__selected-project-community d-none">
        <h5 class="selected-project">Show</h5>
        <h5 class="selected-community">Hide</h5>
    </div>
    
    <div
        id="hideShow"
        class="profile__hide-show @if(data::get('is_visible_sidebar') == 'false') active @endif"
        @if(data::get('is_visible_sidebar') == 'false')  style="max-height:  0" @endif
        data-tab="profileBlock"
    >
        @if( $projects->isEmpty() && $communities->isEmpty())
            <!--START нет проектов и сообществ -->
            <div class="profile__start-work">
                <div class="profile__community_not_selected">
                    <p>
                        Начните работу со Spodial, подключив свои сообщества.
                    </p>
                </div>

                <a
                    class="button-filled button-filled--primary"
                    href="{{route('profile.communities.list')}}"
                    data-repeater-create
                >
                    Начать работу!
                </a>
            </div>
            <!--END нет проектов и сообществ -->
        @else
            <div class="profile__wrapper">
                <div class="profile__channel-owner">
                    @include('common.template.assets.project_list')

                    @if(empty($activeCommunity))
                        <!-- START если сообщество не выбрано -->
                        <div class="profile__community_not_selected">


                            <p>Данные выводятся по всем сообществам проекта, если хотите посмотреть информацию конкретного
                                сообщества, выберите его из списка.</p>


                        </div>
                        <!-- END если сообщество не выбрано -->
                    @else

                        <!-- START профиль выбранного сообщества -->
                        <div class="profile__community_selected">
                            <div class="profile__selected-wrap">
                                <div class="profile__community-img">
                                    <img src="{{$activeCommunity->image ?? '/images/no-image.png'}}" alt="Avatar">
                                </div>
                                <div class="profile__community-description">
                                    <h4 class="profile__community-description--title">
                                        {{ $activeCommunity->title }}
                                    </h4>
                                    
                                    <a
                                        class="profile__community-description--link"
                                        href="https://t.me/TribesSupport_bot"
                                    >
                                        <img
                                            src="/images/icons/social/telegram.png"
                                            alt="Telegram"
                                        >
                                        https://t.me/
                                    </a>
                                    <p class="profile__community-description--subscribers">
                                    <h6 class="profile__community-description--subscribers-text">Подписчиков:</h6>
                                    <p class="profile__community-description--subscribers-quantity">{{$activeCommunity->getCountFollowersAttribute()}}</p>

                                </div>
                            </div>
                            <div class="profile-community__pay-link-block">
                                <p class="profile-community__pay-link-label">
                                    Ссылка на страницу оплаты для доступа к сообществу
                                </p>

                                <div class="profile-community__pay-link-wrapper">
                                    <a
                                    href="{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($activeCommunity->id, 8)]) }}"
                                            target="_blank"
                                            class="link profile-community__pay-link"
                                    >
                                        Перейти
                                    </a>

                                    <button
                                            class="link profile-community__pay-link profile-community__pay-link--divider"
                                            onclick="copyText('{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($activeCommunity->id, 8)]) }}')"
                                    >
                                        Скопировать
                                    </button>
                                    <a
                                        href="{{ route('community.tariff.publication', ['community' => $activeCommunity, 'tab' => 'pay']) }}"
                                        class="link profile-community__pay-link profile-community__pay-link--divider"
                                    >
                                        Редактировать
                                    </a>
                                </div>
                                <p>Просмотры страницы: {{$activeCommunity->statistic->repository()->getViews()}}</p>
                            </div>
                        </div>
                        <!-- END профиль выбранного сообщества -->
                    @endif
                </div>

                <div class="profile__list-channel">
                    @if($activeCommunity  && $activeCommunity->project_id === null)
                        <div class="profile__community_not_selected full-width">
                            <p>Вы можете объединять сообщества в одном проекте. Проекты позволят вам лучше организовать свое
                                рабочее пространство в Spodial, а также смотреть по проектам статистику, донаты и тарифы в
                                общем контексте.
                                <br></br>
                                Чтобы создать проект, откройте меню «Профиль» -> «Мои проекты».</p>
                        </div>
                    @else
                        <h4 class="profile__list-title">Сообщества Проекта</h4>
                        @if($activeProject || $projects->isNotEmpty())
                            @php($currentProj = $activeProject ?? $projects->first())
                            @if($currentProj->communities()->get()->isNotEmpty())

                                <!-- START список сообществ проекта -->
                                <div id="profile-list" class="profile__list">
                                    @foreach($currentProj->communities()->get() as $community)
                                    
                                        <a class="profile__item-wrap" id="community_{{ $community->id }}"
                                        href="{{route(request()->route()->getName(),['project'=>$community->project_id,'community' => $community->id])}}">
                                            <!-- <input type="radio" id="community-item_{{ $community->id }}"
                                                name="community-item"
                                                class="profile__input"> -->
                                               
                                            <div id="community-item_{{ $community->id }}" class="profile__item @if($activeCommunity && $activeCommunity->id == $community->id) active @endif">
                                                <div class="profile__item-image">
                                                    <img class="profile__image" src="{{ $community->image ?? '/images/no-image.png' }}">
                                                </div>
                                                <div class="profile__item-text">
                                                    <p class="profile__channel">{{ $community->description }}</p>
                                                    <div class="profile__messenger">
                                                        <img src="/images/icons/social/telegram.png">
                                                        <p class="profile__text">{{ $community->title }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else

                                <div class="profile__community_not_selected full-width project-without-communities">
                                    <p>В вашем проекте нет ни одного сообщества</p>
                                    <a href="{{ route('profile.project.edit', $currentProj ) }}" class="button-filled button-filled--primary" data-repeater-create>
                                        Добавить сообщество
                                    </a>
                                </div>




                            @endif
                            <!-- END список сообществ проекта -->
                        
                        @endif
                    
                    @endif
                    

                </div>
                <div id="load_container"></div>
            </div>
        @endif
    </div>

    <div
        id="hide_info"
        class="profile__hide-info @if(data::get('is_visible_sidebar') == 'false') active @endif"
    >
        <span class="hide-info-project">
            @if(!empty($activeProject->title))
                {{$activeProject->title}}
            @endif
            
        </span>
        @if(!empty($activeCommunity->title))
            <span  class="hide-info-community">
                {{ $activeCommunity->title }}
            </span>
        @endif
    </div>

    <div class="community__profile-btn-wrapper">
        <button
            id="btn_profile"
            class="community__profile-btn @if(data::get('is_visible_sidebar') == 'false') active @endif"
            onclick="Profile.toggleProfileCommunityVisibility(event)"
            data-switch-visibility-btn
        >
            @if(data::get('is_visible_sidebar') == 'false') Раскрыть @else Скрыть @endif
        </button>
    </div>
</section>
