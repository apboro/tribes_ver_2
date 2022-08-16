@extends('common.tariff.publication.index')

@section('subtab')
    <form
        action="{{ route('tariff.settings.update', $community) }}"
        method="post"
        class=""
        id="pay_form_{{ $community->id }}"
        enctype="multipart/form-data"
    >
        <div class="card">
            <div class="card-body">
                <div class="row" data-tab="tariffPageSettingsPay">
                    <div class="left col-12 col-xl-6">
                        @csrf
                        <!-- Изображение -->
                        @include('common.tariff.assets.tariff_main_image')
                        <hr>

                        <!-- TITLE -->
                        <div class="col-12">
                            <label class="form-label pointer" for="pay_title">
                                {{ __('base.title') }}
                            </label>
                            
                            <input
                                type="text"
                                class="form-control @error('title') error @enderror"
                                id="pay_title"
                                name="title"
                                aria-describedby="pay_title"
                                placeholder="{{ __('form.title_text') }}"
                                value="{{$community->tariff ? $community->tariff->title : old('title')}}"
                                oninput="CommunityPage.tariffPageSettings.payBlock.onInputTitle(event)"
                            />

                            <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                <i data-feather='save' class="font-medium-1" ></i>
                            </span>
                            
                        </div>
                        <hr>

                        <!-- EDITOR -->
                        @include('common.tariff.assets.tariff_main_editor')
                    </div>
                    
                    <!-- Preview page -->
                    @include('common.tariff.assets.tariff_main_preview')
                </div>

                <hr/>

                <div class="profile-community__pay-link-block">
                    <p class="profile-community__pay-link-label">
                        Ссылка на страницу оплаты для доступа к сообществу
                    </p>

                    <div class="profile-community__pay-link-wrapper">
                        <a
                                href="{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}"
                                target="_blank"
                                class="link profile-community__pay-link"
                        >
                            Перейти
                        </a>

                        <button
                                class="link profile-community__pay-link profile-community__pay-link--divider"
                                onclick="copyText('{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}')"
                        >
                            Скопировать
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <!-- Submit -->
                <div class="col-sm-5 col-lg-4 col-xl-3">
                    <button
                        class="btn w-100 btn-icon btn-success d-flex align-items-center justify-content-center"
                        type="submit"
                        data-repeater-create
                    >
                        <i data-feather="save" class="font-medium-1"></i>
                        <span class="ms-1">
                            {{ __('base.save') }}
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </form>
@endsection
