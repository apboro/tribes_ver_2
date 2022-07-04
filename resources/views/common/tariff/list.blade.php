@extends('common.community.profile')

@section('tab')
    <section data-tab="tariffPage">
        <div class="row">
            <div class="col-12">
                <div class="card faq-search">
                    <div class="card-header">
                        <div class="col-6 col-lg-4">
                            <h4 class="card-title">
                                {{ __('base.tariffs') }}
                            </h4>   
                        </div>
                       
                        <div class="mt-1 mt-sm-0">
                            <a
                                href="{{ route('community.tariff.settings', $community) }}"
                                class="btn btn-outline-success"
                            >
                                <i data-feather='settings' class="font-medium-1"></i>
                                <span class="d-none d-lg-inline-block">
                                    {{ __('base.settings') }}
                                </span>
                            </a>

                            <a
                                href="{{ route('community.tariff.add', $community) }}"
                                class="btn btn-success ms-0 ms-sm-1 "
                            >
                                <i data-feather='plus' class="font-medium-1"></i>
                                <span class="d-none d-lg-inline-block">
                                    {{ __('tariff.add_tariff') }}
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
                                    <p class="card-text">
                                        {{ __('base.trial_period') }} — {{ $community->tariff->test_period }} ({{ __('base.days_low') }})
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
                                                    href="{{ route('community.tariff.edit', [$community, $tariff->id, $activate = 0]) }}"
                                                    class="btn btn-flat-danger waves-effect text-nowrap w-100"
                                                >
                                                    <i data-feather='x' class="font-medium-1"></i>
                                                    <span class="">{{ __('base.disable') }}</span>
                                                </a>
                                            @else
                                                <a
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
                        </div>
                    @empty
                        <!-- Empty tariffs -->
                        <div class="row justify-content-center mb-2">
                            <div class="col-md-8 col-12">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="text-center">
                                            @if(request()->has('active') && request()->get('active') == "false")
                                                {{ __('tariff.create_tariff_instruction_inactive') }}
                                            @else
                                                {{ __('tariff.create_tariff_instruction_active') }}
                                            @endif
                                        </h5>
                                    </div>
                                    <a
                                        href="{{ route('community.tariff.add', $community) }}"
                                        class="btn btn-success mt-1"
                                    >
                                        <i data-feather='plus' class="font-medium-1"></i>
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
