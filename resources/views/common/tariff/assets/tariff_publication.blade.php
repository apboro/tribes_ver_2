<div>                                   
    <div class="row">
        <!-- Текст -->
        <div class="col-md-12 col-lg-6">
            <div class="mb-1 mb-lg-0">
                <label class="form-label pointer" for="publication_description">
                    {{ __('tariff.description_publication_tariffs') }}
                </label>

                <textarea
                    class="form-control @error('publication_description') error @enderror"
                    id="publication_description"
                    name="publication_description"
                    rows="5"
                    placeholder="{{ __('form.message_text') }}"
                >@if($community->tariff && $community->tariff->publication_description){{$community->tariff->publication_description}} @endif</textarea>
{{--                @else{{ __('tariff.available_rates') }}--}}
                <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                    <i data-feather='save' class="font-medium-1" ></i>
                </span>
            </div>
            @error('publication_description')
                <div class="alert alert-danger">{{ $message }}</div>
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
                                onchange="CommunityPage.tariffPageSettings.commonBlock.croppImageControllerPublication.onChange()"
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
                            onclick="CommunityPage.tariffPageSettings.commonBlock.croppImageControllerPublication.removeLoadedImage()"
                        >
                            <i data-feather='trash' class="font-medium-1"></i>    
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr />
</div>