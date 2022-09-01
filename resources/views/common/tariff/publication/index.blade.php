@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <section class="community-tab">
        <div class="community-tab__header">
            <button class="community-tab__prev-page-btn"><-</button>
            <p class="community-tab__prev-page-title">Тарифы</p>
            <h2 class="community-tab__title">Публикация тарифов</h2>
        </div>

        <nav class="tab-nav community-tab__nav">
            <ul class="tab-nav__list">               
                <li class="tab-nav__item @if( !request('tab') || request('tab') == 'common') active @endif">
                    <a
                        class="tab-nav__link"
                        href="{{ route('community.tariff.publication', ['community' => $community]) }}"
                    >
                        Сообщение с тарифами в сообщество
                    </a>
                </li>
    
                <li  class="tab-nav__item @if( request('tab') == 'pay') active @endif">
                    <a
                        class="tab-nav__link"
                        href="{{ route('community.tariff.publication', ['community' => $community, 'tab' => 'pay']) }}"
                    >
                        Посадочная веб страница
                    </a>
                </li>
            </ul>
        </nav>

        <div class="settings-text-image community-settings__text-image">
            <!-- Текст -->
            <div class="settings-text-image__text">
                <label class="form-label-red" for="publication_description">
                    {{ __('tariff.description_publication_tariffs') }}
                </label>

                <textarea
                    class="form-control-red @error('publication_description') form-control-red--danger @enderror"
                    id="publication_description"
                    name="publication_description"
                    rows="5"
                    placeholder="{{ __('form.message_text') }}"
                >@if($community->tariff && $community->tariff->publication_description){{$community->tariff->publication_description}}@endif</textarea>
                
                <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                    <i data-feather='save' class="font-medium-1" ></i>
                </span>
                
                @error('publication_description')
                    <div class="form-message form-message--danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Изображение -->
            <div class="col-md-12 col-lg-6">
                <label class="form-label">
                    {{ __('base.image') }}
                </label>

                <div class="d-flex flex-column align-items-center" data-crop-image-container="publication">
                    <div class="col-12 message-alert hide" data-image-alert></div>

                    <!-- Если есть загруженное изображение -->
                    <div
                        class="col-12 d-flex flex-column position-relative active-image @if($community->tariff && $community->tariff->getPublicationImage()) d-block @else d-none @endif"
                        data-crop-image-default-image
                    >
                        <img
                            src="@if($community->tariff && $community->tariff->getPublicationImage()){{ $community->tariff->getPublicationImage()->url }}@endif"
                            alt=""
                            class="active-image__img rounded w-100"
                        >
                    </div>

                    <!-- Загрузка изображения -->
                    <div class="d-flex flex-column align-items-center w-100 text-center @if($community->tariff &&  $community->tariff->getPublicationImage()) hide @else  @endif" data-crop-image-load-container>
                        <div class="position-relative mx-auto w-100">
                            <!-- Описание загрузки -->
                            <div data-crop-image-instructions>
                                <p class="mb-1">
                                    {{ __('form.drag_image_instruction') }}
                                </p>

                                <small>
                                    {{ __('form.support_format') }}
                                </small>

                                <strong class="d-block">
                                    {{ __('form.max_image_size') }}
                                </strong>
                                
                                <label
                                    for="publication_image_upload"
                                    class="position-absolute top-0 end-0 bottom-0 start-0 pointer"
                                ></label>
                            </div>
                            <!-- Данные -->
                            <div class="hide" data-crop-image-data-container>
                                <input
                                    type="file"
                                    id='publication_image_upload'
                                    type="file"
                                    accept="image/png,image/jpeg,image/gif"
                                    name="files[publication][image]"
                                    onchange="CommunityPage.tariffPagePublications.messageBlock.croppImageControllerPublication.onChange()"
                                    data-crop-image-file
                                >
                                
                                <input
                                    type="hidden"
                                    name="files[publication][delete]"
                                    value="false"
                                    data-crop-image-remove-data
                                >

                                <input
                                    type="hidden"
                                    name="files[publication][crop]"
                                    value=""
                                    data-crop-image-crop-data
                                >
                            </div>  

                            <!-- Croppr -->
                            <div class="cropper">
                                <div class="cropper-container" data-crop-image-croppr-container></div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="col-12 d-flex mt-1 @if($community->tariff && $community->tariff->getPublicationImage()) @else hide @endif" data-crop-image-buttons-container>
                        <div class="input-group">                
                            <label
                                class="col-7 col-lg-8 btn btn-info px-1"
                                for="publication_image_upload"                                
                            >
                                <i data-feather='download' class="font-medium-1"></i>
                                <span class="d-none d-xl-inline-block">
                                    {{ __('base.select_image') }}
                                </span>
                            </label>
                        
                            <span
                                class="col-5 col-lg-4 btn btn-danger px-1"
                                onclick="CommunityPage.tariffPagePublications.messageBlock.croppImageControllerPublication.removeLoadedImage()"
                            >
                                <i data-feather='trash' class="font-medium-1"></i>    
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </section>

    <section class="form-control-repeater" data-tab="tariffPagePublications">
        <div class="row">
            <!-- Invoice repeater -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-8 col-xl-10">
                            <h4 class="card-title">
                                Публикация тарифов в сообществе
                            </h4>
                        </div>
                        
                        <a
                            href="{{ route('community.tariff.list', $community) }}"
                            class="btn btn-outline-primary custom waves-effect"
                        >
                            <i data-feather="arrow-left" class="font-medium-1"></i>
                            
                            <span class="align-middle d-sm-inline-block d-none">
                                {{ __('base.back') }}

                            </span>
                        </a>
                    </div>
                </div>
            
                <!-- Nav -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a
                            class="nav-link @if( !request('tab') || request('tab') == 'common') active @endif"
                            href="{{ route('community.tariff.publication', ['community' => $community]) }}"
                        >
                            Сообщение с тарифами в сообщество
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link @if( request('tab') == 'pay') active @endif"
                            href="{{ route('community.tariff.publication', ['community' => $community, 'tab' => 'pay']) }}"
                        >
                            Посадочная веб-страница с тарифами
                        </a>
                    </li>
                </ul>
                    
                <!-- TABS -->
                @yield('subtab')    
            </div>
        </div>
    </section>
@endsection
