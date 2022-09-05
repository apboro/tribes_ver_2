<div class="settings-text-image community-settings__item">
    <!-- Текст -->
    <div class="settings-text-image__text">
        <label
            class="form-label-red"
            for="welcome_description"
        >
            {{ __('tariff.welcome_description_title') }}
        </label>

        <textarea
            class="form-control-red @error('publication_description') form-control-red--danger @enderror"
            id="welcome_description"
                name="welcome_description"
                rows="5"
                placeholder="@ссылка_на_профиль_участника, приветствуем вас в нашем сообществе!"
            >@if($community->tariff && $community->tariff->welcome_description){{$community->tariff->welcome_description}}@endif</textarea>
        
        <!-- <span
            class="badge bg-warning hide"
            title="{{ __('base.unsaved_data') }}"
        >
            <i
                data-feather='save'
                class="font-medium-1"
            ></i>
        </span> -->
        
        @error('welcome_description')
            <div class="form-message form-message--danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Изображение -->
    <div
        class="settings-text-image__image-block"
        data-crop-image-container="welcome"
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
                class="settings-text-image__active-image @if($community->tariff && $community->tariff->getWelcomeImage()) d-block @else d-none @endif"
                data-crop-image-default-image
            >
                <img
                    src="@if($community->tariff && $community->tariff->getWelcomeImage()) {{ $community->tariff->getWelcomeImage()->url }} @endif"
                    alt=""
                >
            </div>

            <!-- Загрузка изображения -->
            <div
                class="settings-text-image__load-container @if($community->tariff && $community->tariff->getWelcomeImage()) hide @else @endif"
                data-crop-image-load-container
            >
                <!-- Описание загрузки -->
                <div
                    class="settings-text-image__instructions"
                    data-crop-image-instructions
                >
                    <p class="settings-text-image__instructions-text">
                        {{ __('form.drag_image_instruction') }}
                    </p>

                    <p class="settings-text-image__instructions-text settings-text-image__instructions-formats">
                        {{ __('form.support_format') }}
                    </p>

                    <p class="settings-text-image__instructions-text settings-text-image__instructions-text--bold settings-text-image__instructions-size">
                        {{ __('form.max_image_size') }}
                    </p>
                    
                    <label
                        for="welcome_image_upload"
                        class="settings-text-image__instructions-label"
                    ></label>
                </div>
                    
                <!-- Данные -->
                <div
                    class="hide"
                    data-crop-image-data-container
                >
                    <input
                        type="file"
                        id='welcome_image_upload'
                        type="file"
                        accept="image/png,image/jpeg,image/gif"
                        name="files[welcome][image]"
                        onchange="CommunityPage.tariffPageSettings.settingsMessages.croppImageControllerWelcome.onChange()"
                        data-crop-image-file
                    >
                    
                    <input
                        type="hidden"
                        name="files[welcome][delete]"
                        value="false"
                        data-crop-image-remove-data
                    >

                    <input
                        type="hidden"
                        name="files[welcome][crop]"
                        value=""
                        data-crop-image-crop-data
                    >
                </div>  

                <!-- Croppr -->
                <div class="settings-text-image__cropper  cropper">
                    <div
                        class="settings-text-image__cropper-container"
                        data-crop-image-croppr-container
                    ></div>
                </div>
            </div>

            <!-- Кнопки -->
            <div
                class="settings-text-image__buttons @if($community->tariff && $community->tariff->getWelcomeImage()) @else hide @endif"
                data-crop-image-buttons-container
            >                
                <label
                    class="button-empty button-empty--primary"
                    for="welcome_image_upload"                                
                >
                    {{ __('base.select_image') }}
                </label>
            
                <span
                    class="button-empty button-empty--primary"
                    onclick="CommunityPage.tariffPageSettings.settingsMessages.croppImageControllerWelcome.removeLoadedImage()"
                >
                    Удалить    
                </span>
            </div>
        </div>
    </div>
</div>    
