<div class="settings-text-image community-settings__item">
    <!-- Текст -->
    <div class="settings-text-image__text">
        <label
            class="form-label-red"
            for="thanks_description"
        >
            {{ __('tariff.success_description_title') }}
        </label>

        <textarea
            class="form-control-red @error('success_description') form-control-red--danger @enderror"
            id="thanks_description"
            name="success_description"
            rows="5"
            placeholder="{{ __('form.message_text') }}"
        >@if($community->tariff && $community->tariff->thanks_description){{$community->tariff->thanks_description}}@elseif(is_null($community->tariff->thanks_description)){{__('tariff.payment_thanks_message')}}@endif
        </textarea>
        
        <!-- <span
            class="badge bg-warning hide"
            title="{{ __('base.unsaved_data') }}"
        >
            <i
                data-feather='save'
                class="font-medium-1"
            ></i>
        </span> -->
        
        @error('success_description')
            <span class="form-message form-message--danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Изображение -->
    <div
        class="settings-text-image__image-block"
        data-crop-image-container="success"
    >
        <label class="form-label-red">
            {{ __('base.image') }}
        </label>

        <div
            class="settings-text-image__image"
        >
            <div
                class="settings-text-image__image-message hide"
                data-image-alert
            ></div>

            <!-- Если есть загруженное изображение -->
            <div
                class="settings-text-image__active-image @if($community->tariff && $community->tariff->getThanksImage()) d-block @else d-none @endif"
                data-crop-image-default-image
            >
                <img
                    src="@if($community->tariff && $community->tariff->getThanksImage()){{ $community->tariff->getThanksImage()->url }}@endif"
                    alt=""
                >
            </div>

            <!-- Загрузка изображения -->
            <div
                class="settings-text-image__load-container @if($community->tariff && $community->tariff->getThanksImage()) hide @else @endif"
                data-crop-image-load-container
            >
                <!-- Описание загрузки -->
                <div class="settings-text-image__instructions" style="padding: 0; border:none;" data-crop-image-instructions>
                    <div class="settings-text-image__active-image">
                        <img
                            src="/images/thanks.jpg"
                            alt=""
                        >
                    </div>
                    
                    <label
                        for="success_image_upload"
                        class="position-absolute top-0 end-0 bottom-0 start-0 pointer"
                    ></label>

                    <div
                        class="settings-text-image__buttons"
                    >
                        <label
                            class="button-empty button-empty--primary"
                            for="success_image_upload "                                
                        >
                            {{ __('base.select_image') }}
                        </label>
                    </div>
                </div>
                
                <!-- Данные -->
                <div
                    class="hide"
                    data-crop-image-data-container
                >
                    <input
                        type="file"
                        id='success_image_upload'
                        type="file"
                        accept="image/png,image/jpeg,image/gif"
                        name="files[success][image]"
                        onchange="CommunityPage.tariffPageSettings.settingsMessages.croppImageControllerSuccess.onChange()"
                        data-crop-image-file
                    >
                    
                    <input
                        type="hidden"
                        name="files[success][delete]"
                        value="false"
                        data-crop-image-remove-data
                    >

                    <input
                        type="hidden"
                        name="files[success][crop]"
                        value=""
                        data-crop-image-crop-data
                    >
                </div>  

                <!-- Croppr -->
                <div class="settings-text-image__cropper cropper">
                    <div
                        class="settings-text-image__cropper-container"
                        data-crop-image-croppr-container
                    ></div>
                </div>
            </div>

            <!-- Кнопки -->
            <div
                class="settings-text-image__buttons @if($community->tariff && $community->tariff->getThanksImage())) @else hide @endif"
                data-crop-image-buttons-container
            >                
                <label
                    class="button-empty button-empty--primary"
                    for="success_image_upload"                                
                >
                    {{ __('base.select_image') }}
                </label>
            
                <span
                    class="button-empty button-empty--primary"
                    onclick="CommunityPage.tariffPageSettings.settingsMessages.croppImageControllerSuccess.removeLoadedImage()"
                >
                    Удалить    
                </span>
            </div>
        </div>
    </div>
</div>    
