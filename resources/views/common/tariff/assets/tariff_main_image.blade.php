<!-- Изображение -->
<div
    class="settings-text-image__image-block community-settings__form-item"
    data-crop-image-container="pay"
>
    <label class="form-label-red">
        {{ __('base.image') }}
    </label>

    <div
        class="settings-text-image__image settings-text-image__image--no-line"
    >
        <div
            class="settings-text-image__image-message hide"
            data-image-alert
        ></div>

        <!-- Если есть загруженное изображение -->
        <div
            class="settings-text-image__active-image @if($community->tariff && $community->tariff->getMainImage()) d-block @else d-none @endif"
            data-crop-image-default-image
        >
            <img
                src="@if($community->tariff && $community->tariff->getMainImage()){{ $community->tariff->getMainImage()->url }}@endif"
                alt=""
            >
        </div>

        <!-- Загрузка изображения -->
        <div
            class="settings-text-image__load-container @if($community->tariff && $community->tariff->getMainImage()) hide @else @endif"
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
                    for="pay_image_upload"
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
                    id='pay_image_upload'
                    type="file"
                    accept="image/png,image/jpeg,image/gif"
                    name="files[pay][image]"
                    onchange="CommunityPage.tariffPagePublications.payBlock.croppImageControllerPay.onChange()"
                    data-crop-image-file
                >
                
                <input
                    type="hidden"
                    name="files[pay][delete]"
                    value="false"
                    data-crop-image-remove-data
                >

                <input
                    type="hidden"
                    name="files[pay][crop]"
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
            class="settings-text-image__buttons @if($community->tariff && $community->tariff->getMainImage()) @else hide @endif"
            data-crop-image-buttons-container
        >                
            <label
                class="button-empty button-empty--primary"
                for="pay_image_upload"                                
            >
                {{ __('base.select_image') }}
            </label>
        
            <span
                class="button-empty button-empty--primary"
                onclick="CommunityPage.tariffPagePublications.payBlock.croppImageControllerPay.removeLoadedImage()"
            >
                Удалить    
            </span>
        </div>
    </div>
</div>
