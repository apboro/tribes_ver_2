@extends('common.community.profile')

@section('tab')
    <section class="community-tab" data-tab="tariffPage">
        <div class="community-tab__main-header">
            <h2 class="community-tab__main-title">
                {{ __('base.tariffs') }}
            </h2>

            <div class="dropdown-red dropdown-red--left main-header__dropdown">
                <button class="button-text button-text--primary button-text--only-icon dropdown-red__head" data-dropdown-btn onclick="Dropdown.toggle(this)">
                    <span
                        class="dropdown-red__name"
                        title="{{ Auth::user()->name }}"
                    >
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_1145_9321)">
                                <path class="icon__fill" d="M18.6938 6.81543L17.6308 6.63828C16.9026 6.51692 16.4675 5.76121 16.7261 5.07123L17.1041 4.06207C17.3732 3.34389 17.0864 2.54429 16.4222 2.16074L13.5766 0.517872C12.9124 0.134399 12.0764 0.285976 11.589 0.877947L10.9041 1.70997C10.4349 2.27981 9.56301 2.27898 9.09463 1.70999L8.40969 0.877936C7.92225 0.285849 7.08637 0.134395 6.42217 0.517871L3.57663 2.16074C2.9124 2.54424 2.62559 3.34388 2.89469 4.06207L3.27274 5.07126C3.53175 5.76262 3.09478 6.51716 2.36803 6.63832L1.305 6.81545C0.548624 6.94154 -0.000547258 7.58973 -0.000625581 8.3567L-0.000622841 11.6424C-0.000633386 12.4093 0.548437 13.0575 1.305 13.1837L2.36799 13.3608C3.09623 13.4822 3.53123 14.2379 3.27272 14.9279L2.89462 15.937C2.62559 16.6553 2.91238 17.4549 3.57657 17.8384L6.42213 19.4812C7.08635 19.8647 7.92215 19.7132 8.4097 19.1212L9.0946 18.2892C9.56383 17.7192 10.4358 17.7203 10.9041 18.2892L11.589 19.1211C12.0765 19.7133 12.9124 19.8647 13.5766 19.4813L16.4221 17.8384C17.0863 17.4549 17.3731 16.6553 17.1041 15.9371L16.726 14.928C16.467 14.2368 16.9038 13.482 17.6307 13.3609L18.6938 13.1837C19.4503 13.0576 19.9994 12.4094 19.9994 11.6424L19.9994 8.35663C19.9994 7.58973 19.4503 6.94154 18.6938 6.81543ZM18.4369 11.6424L17.3739 11.8196C15.6746 12.1028 14.6597 13.8661 15.2628 15.476L15.6409 16.4852L12.7954 18.1281L12.1104 17.2961C11.0157 15.9662 8.98115 15.9687 7.88833 17.2961L7.2034 18.1281L4.35787 16.4852L4.73594 15.4761C5.34032 13.8628 4.32066 12.1022 2.62488 11.8196L1.5619 11.6424L1.56191 8.35668L2.62489 8.17954C4.32418 7.89624 5.3391 6.13302 4.73594 4.52309L4.35785 3.51393L7.20339 1.87106L7.88831 2.70307C8.98322 4.03311 11.0176 4.03033 12.1104 2.70308L12.7953 1.87106L15.641 3.51341C15.641 3.51341 15.641 3.51357 15.6408 3.51393L15.2628 4.52307C14.6584 6.13623 15.678 7.89689 17.3738 8.17954L18.4368 8.35668L18.4369 11.6424ZM8.0723 6.66175C6.23182 7.72435 5.59897 10.0862 6.66157 11.9267C7.72417 13.7671 10.086 14.4 11.9265 13.3374C13.7669 12.2748 14.3998 9.91295 13.3372 8.07248C12.2746 6.23201 9.91277 5.59916 8.0723 6.66175ZM11.1452 11.9842C10.0509 12.616 8.64655 12.2397 8.01473 11.1454C7.38292 10.0511 7.75921 8.64673 8.85355 8.01492C9.94788 7.3831 11.3522 7.75939 11.984 8.85373C12.6159 9.94807 12.2396 11.3524 11.1452 11.9842Z" fill="#C4C0D0"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_1145_9321">
                                    <rect width="20" height="20" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                </button>

                <ul class="dropdown-red__list" data-dropdown-content>
                    <li class="dropdown-red__item">
                        <a class="dropdown-red__link" href="{{ route('community.tariff.add', $community) }}">
                            <span>{{ __('tariff.add_tariff') }}</span>
                        </a>
                    </li>

                    <li class="dropdown-red__item">
                        <a class="dropdown-red__link" href="{{ route('community.tariff.publication', $community) }}">
                            <span>{{ __('tariff.tariffs_publication') }}</span>
                        </a>
                    </li>

                    <li class="dropdown-red__item">
                        <a class="dropdown-red__link" href="{{ route('community.tariff.settings', $community) }}">
                            <span>{{ __('tariff.setting_messages') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Nav -->
            <select
                id="tariff-list-state"
                class="select-rounded"
            >
                <option
                    value="{{ route('community.tariff.list', $community) }}"
                    @if(request('active') == null || request('active') == 'true') selected @endif
                >
                    Активный
                </option>
                <option
                    value="{{ route('community.tariff.list', $community) . '?active=false' }}"
                    @if(request('active') !== null && request('active') == 'false') selected @endif
                >
                    Неактивный
                </option>
                <option
                    value="{{ route('community.tariff.list', $community) . '?isPersonal=true' }}"
                    @if(request('active') == null && request('isPersonal') == true) selected @endif
                >
                    Персональный
                </option>
            </select>
        </div>
        
        <!-- TARIFF LIST -->
        @if(isset($tariffs[0]))
        <ul class="community-tariff__list">
            @forelse ($tariffs ?? [] as $tariff)
            <li>
                <div class="community-tariff-card community-tariff__item">
                    <div class="community-tariff-card__header">
                        <h4
                            class="community-tariff-card__title"
                            title="{{ $tariff->title }}"
                        >
                            {{ $tariff->title }}
                        </h4>
                        
                        <a
                            href="{{ route('community.tariff.edit', [$community, $tariff->id]) }}"
                            class="button-text button-text--primary button-text--only-icon community-tariff-card__edit"
                        >
                            <!-- <i data-feather='edit' class="font-medium-1"></i> -->
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd" d="M15.5858 4.41421C16.3668 3.63317 17.6332 3.63317 18.4142 4.41421L19.5858 5.58579C20.3668 6.36684 20.3668 7.63316 19.5858 8.41421L8.58579 19.4142C8.21071 19.7893 7.70201 20 7.17157 20L4 20L4 16.8284C4 16.298 4.21071 15.7893 4.58579 15.4142L15.5858 4.41421Z" stroke="#B5B4B8"/>
                                <path class="icon__stroke" d="M14 6L18 10" stroke="#B5B4B8"/>
                            </svg>
                        </a>
                    </div>

                    <p class="community-tariff-card__text">
                        <span class="community-tariff-card__price">{{ $tariff->price }}&#8381;</span>/
                        <span class="community-tariff-card__period">
                            {{ $tariff->period }} {{App\Traits\Declination::defineDeclination($tariff->period)}}
                        </span>
                    </p>

                    <p class="community-tariff-card__text">
                        {{ __('base.community') }}
                    </p>

                    <p class="community-tariff-card__community">
                        {{ $community->title }}
                    </p>

                    <a
                        type="submit"
                        @if(request('active') == null)
                        href="{{ route('community.tariff.edit', [$community, $tariff->id, $activate = 0]) }}"
                        @else
                        href="{{ route('community.tariff.edit', [$community, $tariff->id, $activate = 1]) }}"
                        @endif class="toggle-switch community-tariff-card__switcher"
                    >
                        <label class="toggle-switch__switcher">
                            <input
                                type="checkbox"
                                id="is_tariff-list"
                                class="toggle-switch__input"
                                value=""
                                @if($tariff->isActive) checked @endif
                            > 
                            <span class="toggle-switch__slider"></span>
                        </label> 

                        <label for="is_tariff-list" class="toggle-switch__label">
                            @if(request('active') == null) Активный @else Неактивный @endif
                        </label>
                    </a>
                </div>
                <div class="community-settings__inline-command list">
                    <span class="form-label-red">{{__('tariff.inline_command')}}</span>
                    <a 
                        class="community-settings__inline-link" 
                        onclick="copyText('{{$tariff ? $tariff->getInlineLink() : 'Создастся при сохранении'}}')"
                    >
                        {{$tariff->getInlineLink()}}
                    </a>
                </div>
            </li>
            @empty
            
            @endforelse
        </ul>
        @else
        <!-- Empty tariffs -->
        <div class="community-tariff__empty-wrapper">
            @if(request()->has('active') && request()->get('active') == "false")
                <p class="community-tariff__empty-text">
                    Отсутствуют неактивные тарифы. 
                </p>
            @else
                <p class="community-tariff__empty-text">
                    Отсутствуют активные тарифы. 
                </p>
                <p class="community-tariff__empty-text">
                    Добавьте активный тариф или измените статус неактивного тарифа
                </p>
            @endif

            <a
                href="{{ route('community.tariff.add', $community) }}"
                class="button-filled button-filled--primary community-tariff__empty-btn"
            >
                {{ __('tariff.add_tariff') }}
            </a>
        </div>
        @endif
    </section>
@endsection
