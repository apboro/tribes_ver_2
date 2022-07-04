export class CropImageData {
    constructor(options) {
        // Инпут данные кроп данных
        this.cropDataElement = options.dataContainer.querySelector('[data-crop-image-crop-data]');
        // Инпут данные об удалении изображения
        this.cropImageRemoveDataElement = options.dataContainer.querySelector('[data-crop-image-remove-data]');

        // событие обновления кроп данных
        this.getCropDataEvent = options.getCropDataEvent;
        // событие удаления изображения
        this.removeImageEvent = options.removeImageEvent;
        
        this.init();
    }

    init() {
        this.listeners();
    }

    listeners() {
        // Подписываемся на обновление кроп даты описания доната, и перемещаем ее в инпут
        Emitter.subscribe(this.getCropDataEvent, (data) => {
            this.setCropDataElement = data;
        });

        // Подписываемся на событие удаления изображения
        Emitter.subscribe(this.removeImageEvent, (data) => {
            this.setCropImageRemoveDataElement = data;
        });
    }

    set setCropDataElement(data) {
        this.cropDataElement.value = JSON.stringify(data.crop);
        this.cropImageRemoveDataElement.value = data.isImageRemove;
    }

    set setCropImageRemoveDataElement(data) {
        this.cropImageRemoveDataElement.value = data.isImageRemove; 
    }
}
