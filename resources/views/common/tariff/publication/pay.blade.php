@extends('common.tariff.publication.index')

@section('subtab')
    <form
        action="{{ route('tariff.settings.update', $community) }}"
        method="post"
        class=""
        id="pay_form_{{ $community->id }}"
        enctype="multipart/form-data"
        data-tab="tariffPagePublicationsPay"
    >
        <div class="card">
            <div class="card-body">
                <div class="row" data-tab="tariffPageSettingsPay">
                    <div class="left col-12 col-xl-6">
                        @csrf
                        <!-- TITLE -->
                        <div class="col-12">
                            <label
                                class="form-label-red"
                                for="pay_title"
                            >
                                {{ __('base.title') }}
                            </label>
                            
                            <input
                                type="text"
                                class="form-control-red @error('title') form-control-red--danger @enderror"
                                id="pay_title"
                                name="title"
                                aria-describedby="pay_title"
                                placeholder="{{ __('form.title_text') }}"
                                value="{{$community->tariff ? $community->tariff->title : old('title')}}"
                                oninput="CommunityPage.tariffPagePublications.payBlock.onInputTitle(event)"
                            />

                            <!-- <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                <i data-feather='save' class="font-medium-1" ></i>
                            </span> -->
                        </div>

                        <!-- Изображение -->
                        @include('common.tariff.assets.tariff_main_image')
                        
                        <!-- EDITOR -->
                        @include('common.tariff.assets.tariff_main_editor')
                    </div>
                    
                    <!-- Preview page -->
                    @include('common.tariff.assets.tariff_main_preview')
                </div>


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

                        <span
                            class="link profile-community__pay-link profile-community__pay-link--divider"
                            onclick="copyText('{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}')"
                        >
                            Скопировать
                        </span>
                    </div>
                </div>
            </div>

            <div class="community-settings__buttons">
                <!-- Submit -->
                <button
                    class="button-filled button-filled--primary"
                    type="submit"
                    data-repeater-create
                >
                    <i data-feather="save" class="font-medium-1"></i>
                    <span class="ms-1">
                        {{ __('base.save') }}
                    </span>
                </button>

                <a
                    href="{{ route('community.tariff.list', $community) }}"
                    class="button-filled button-filled--primary"
                >
                    Отменить
                </a>
            </div>

        </div>
    </form>
@endsection
