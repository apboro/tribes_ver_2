@extends('common.community.profile')

@section('tab')
    <section data-tab="tariffPage">
        <div class="row">
            <div class="col-12">
                <div class="card faq-search">
                    <div class="card-header">
                        <div class="col-6 col-lg-2">
                            <h4 class="card-title">
                                {{ __('base.tariffs') }}
                            </h4>   
                        </div>
                       
                        <div class="mt-1 mt-sm-0">
                            <a
                                    href="{{ route('community.tariff.add', $community) }}"
                                    class="btn btn-success"
                            >
                                <i data-feather='plus' class="font-medium-1"></i>
                                <span class="d-none d-lg-inline-block">
                                    {{ __('tariff.add_tariff') }}
                                </span>
                            </a>

                            <a
                                    href="{{ route('community.tariff.publication', $community) }}"{{--сюда роут для публикации--}}
                                    class="btn btn-success ms-0 ms-sm-1 "
                            >
                                <i data-feather='plus' class="font-medium-1"></i>
                                <span class="d-none d-lg-inline-block">
                                    {{ __('tariff.tariffs_publication') }}
                                </span>
                            </a>

                            <a
                                href="{{ route('community.tariff.settings', $community) }}"
                                class="btn btn-outline-success ms-0 ms-sm-1"
                            >
                                <i data-feather='settings' class="font-medium-1"></i>
                                <span class="d-none d-lg-inline-block">
                                    {{ __('tariff.setting_messages') }}
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Nav -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a
                            class="nav-link @if(request('active') == null || request('active') == 'true') active @endif"
                            href="{{ route('community.tariff.list', $community) }}"
                        >
                            {{ __('base.active') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link @if(request('active') !== null && request('active') == 'false') active @endif"
                            href="{{ route('community.tariff.list', $community) . '?active=false' }}"
                        >
                            {{ __('base.inactive') }}
                        </a>
                    </li>
                </ul>
                
                        
                <!-- TARIFF LIST -->
                <div class="row">
                    @forelse ($tariffs ?? [] as $tariff)
                        <!-- tariff -->
                        <div class="col-12 col-sm-6 col-xl-4">
                            <div class="card tariff-list">
                                <div class="tariff-list__header">
                                    <h4 class="tariff-list__month" title="{{ $tariff->title }}">{{ $tariff->title }}</h4>
                                    <a
                                        href="{{ route('community.tariff.edit', [$community, $tariff->id]) }}"
                                        class="tariff-list__change"
                                    >
                                        <!-- <i data-feather='edit' class="font-medium-1"></i> -->
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.5858 4.41421C16.3668 3.63317 17.6332 3.63317 18.4142 4.41421L19.5858 5.58579C20.3668 6.36684 20.3668 7.63316 19.5858 8.41421L8.58579 19.4142C8.21071 19.7893 7.70201 20 7.17157 20L4 20L4 16.8284C4 16.298 4.21071 15.7893 4.58579 15.4142L15.5858 4.41421Z" stroke="#B5B4B8"/>
                                            <path d="M14 6L18 10" stroke="#B5B4B8"/>
                                        </svg>
                                    </a>
                                </div>
                                <p class="tariff-list__price-days"><span class="tariff-list__price">{{ $tariff->price }}&#8381; </span> / {{ $tariff->period }} {{App\Traits\Declination::defineDeclination($tariff->period)}}</p>
                                <div class="tariff-list__community">
                                    <h4 class="tariff-list__community-label">{{ __('base.community') }}</h4>
                                    <p class="tariff-list__community-title">Канал Димы Коваля</p>
                                </div>
                                <div class="toggle-switch">
                                    <label class="toggle-switch__switcher">
                                        <input type="checkbox" id="is_tariff-list" class="toggle-switch__input" value=""> 
                                        <span class="toggle-switch__slider"></span>
                                    </label> 
                                    <label for="is_tariff-list" class="toggle-switch__label">
                                        Активный
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-12 col-sm-6 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4
                                        class="card-title community-item__title mb-1"
                                        title="{{ $tariff->title }}"
                                    >
                                        {{ $tariff->title }}
                                    </h4>
                                    
                                    <p class="card-text">
                                        {{ $tariff->price }}₽ — {{ $tariff->period }} ({{ __('base.days_low') }})
                                    </p>
                                    
                                </div>

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12">
                                            <a
                                                href="{{ route('community.tariff.edit', [$community, $tariff->id]) }}"
                                                class="btn btn-flat-dark waves-effect text-nowrap w-100"
                                            >
                                                <i data-feather='edit' class="font-medium-1"></i>
                                                <span class="">{{ __('base.edit') }}</span>
                                            </a>
                                        </div>

                                        <div class="col-12">
                                            @if(request('active') == null)
                                                <a
                                                    type="submit"
                                                    href="{{ route('community.tariff.edit', [$community, $tariff->id, $activate = 0]) }}"
                                                    class="btn btn-flat-danger waves-effect text-nowrap w-100"
                                                >
                                                    <i data-feather='x' class="font-medium-1"></i>
                                                    <span class="">{{ __('base.disable') }}</span>
                                                </a>
                                            @else
                                                <a
                                                    type="submit"
                                                    href="{{ route('community.tariff.edit', [$community, $tariff->id, $activate = 1]) }}"
                                                    class="btn btn-flat-success waves-effect text-nowrap w-100"
                                                >
                                                    {{ __('base.enable') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    @empty
                        <!-- Empty tariffs -->
                        <div class="row justify-content-center mb-2">
                            <div class="col-md-8 col-12">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="text-center tariff-list__empty">
                                            @if(request()->has('active') && request()->get('active') == "false")
                                                {{ __('tariff.create_tariff_instruction_inactive') }}
                                            @else
                                                {{ __('tariff.create_tariff_instruction_active') }}
                                            @endif
                                        </h5>
                                    </div>
                                    <a
                                        href="{{ route('community.tariff.add', $community) }}"
                                        class="button-filled button-filled--secondary tariff-list__btn"
                                    >
                                        <!-- <i data-feather='plus' class="font-medium-1"></i> -->
                                        <span>
                                            {{ __('tariff.add_tariff') }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
