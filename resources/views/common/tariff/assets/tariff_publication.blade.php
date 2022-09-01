<div class="settings-text-image community-settings__item">
    <!-- Текст -->
    <div class="settings-text-image__text">
        <label
            class="form-label-red"
            for="publication_description"
        >
            {{ __('tariff.description_publication_tariffs') }}
        </label>

        <textarea
            class="form-control-red @error('publication_description') form-control-red--danger @enderror"
            id="publication_description"
            name="publication_description"
            rows="5"
            placeholder="Доступные тарифы"
        >@if($community->tariff && $community->tariff->publication_description){{$community->tariff->publication_description}}@endif</textarea>
        
        <span
            class="badge bg-warning hide"
            title="{{ __('base.unsaved_data') }}"
        >
            <i
                data-feather='save'
                class="font-medium-1"
            ></i>
        </span>
        
        @error('publication_description')
            <div class="form-message form-message--danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Изображение -->
    <div
        class="settings-text-image__image-block"
        data-crop-image-container="publication"
    >
        <label class="form-label-red">
            {{ __('base.image') }}
        </label>

        <div
            class="settings-text-image__image"
            data-crop-image-container="publication"
        >
            <div
                class="settings-text-image__image-message hide"
                data-image-alert
            ></div>

            <!-- Если есть загруженное изображение -->
            <div
                class="settings-text-image__active-image @if($community->tariff && $community->tariff->getPublicationImage()) d-block @else d-none @endif"
                data-crop-image-default-image
            >
                <img
                    src="@if($community->tariff && $community->tariff->getPublicationImage()){{ $community->tariff->getPublicationImage()->url }}@endif"
                    alt=""
                >
            </div>

            <!-- Загрузка изображения -->
            <div
                class="settings-text-image__load-container @if($community->tariff &&  $community->tariff->getPublicationImage()) hide @else @endif"
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
                        for="publication_image_upload"
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
                <div class="settings-text-image__cropper  cropper">
                    <div
                        class="settings-text-image__cropper-container"
                        data-crop-image-croppr-container
                    ></div>
                </div>
            </div>

            <!-- Кнопки -->
            <div
                class="settings-text-image__buttons @if($community->tariff && $community->tariff->getPublicationImage()) @else hide @endif"
                data-crop-image-buttons-container
            >                
                <label
                    class="button-empty button-empty--primary"
                    for="publication_image_upload"                                
                >
                    {{ __('base.select_image') }}
                </label>
            
                <span
                    class="button-empty button-empty--primary"
                    onclick="CommunityPage.tariffPagePublications.messageBlock.croppImageControllerPublication.removeLoadedImage()"
                >
                    Удалить    
                </span>
            </div>
        </div>
    </div>
</div>    
