import { CropImageData } from "./CropImageData";
import { InitCroppr } from "./InitCroppr";
import { LoadImageFile } from "./LoadImageFile";

export class CropImageController {
    constructor(options) {
        this.id = options.id;
        // Верстка
        // Общий контейнер для загружаемой картинки, которая будет кропаться, и данных
        this.container = options.container.querySelector(`[data-crop-image-container="${ this.id }"]`);
        // Контейнер для данных загрузки, кроппра, изображения, инструкции
        this.cropLoadImageContainer = this.container.querySelector('[data-crop-image-load-container]')
        // Контейнер инструкций по загрузке и полю загрузки
        this.cropImageInstructionsContainer = this.cropLoadImageContainer.querySelector('[data-crop-image-instructions]');
        // Контейнер для данных
        this.dataContainer = this.cropLoadImageContainer.querySelector('[data-crop-image-data-container]');
        // Инпут данные загружаемого изображения
        this.imageFileElement = this.dataContainer.querySelector('[data-crop-image-file');
        // Контейнер для создания элемента изображения и кроп оболочки
        this.cropprContainer = this.cropLoadImageContainer.querySelector('[data-crop-image-croppr-container]');
        // Контейнер кнопока заменить удалить
        this.buttonsContainer = this.container.querySelector('[data-crop-image-buttons-container]');
        // Контейнер дефолтного изображения
        this.defaultImageContainer = this.container.querySelector('[data-crop-image-default-image');

        // Идентификатор общего контейнера
        this.nameContainer = this.container.dataset.cropImageContainer;

        // События
        // Событие загрузки файла картинки
        this.imageLoadedEvent = `LoadedImage:${ this.id }`
        // Событие обновления кроп данных
        this.getCropDataEvent = `GetCropData:${ this.id }`;
        // Событие удаления изображения
        this.removeImageEvent = `RemoveImage:${ this.id }`;
        // Событие ошибки в размере файла
        this.imageAlertEvent = `ImageAlert:${ this.id }`;

        
        // Экземпляр загрузки файла изображения
        this.img = null;
        // Экземпляр кроппра
        this.croppr = null;
        // Данные кроп-данных, удалении изображения
        this.data = null;

        // Создаваемый элемент изображения
        this.imgElement = null;

        

        this.init();
    }

    init() {
        // Слушатель событий
        this.listeners();

        this.data = new CropImageData({
            dataContainer: this.dataContainer,
            getCropDataEvent: this.getCropDataEvent,
            removeImageEvent: this.removeImageEvent,
        });
    }

    listeners() {
        // drug&drop listeners
        this.cropLoadImageContainer.addEventListener('dragover', (event) => { this.drugDropPrventDefaults(event) }, false);
        this.cropLoadImageContainer.addEventListener('dragenter', (event) => { this.onDrugEnter(event) }, false);
        this.cropLoadImageContainer.addEventListener('dragleave', (event) => { this.onDrugLeave(event) }, false);
        this.cropLoadImageContainer.addEventListener('drop', (event) =>  { this.onDrop(event) }, false);

        // Происходит событие загрузки файла изображения
        Emitter.subscribe(this.imageLoadedEvent, (data) => {
            this.onImageLoaded(data.image);
        });

        // Событие ошибки при загрузке картинки
        Emitter.subscribe(this.imageAlertEvent, (data) => {
            new AlertMessage({
                container: this.container.querySelector('[data-image-alert]'),
                type: data.type,
                message: data.message,
            });
        });
    }

    onImageLoaded(image) {
        // Сохраняем в объекте файл изображения
        this.image = image;

        this.cropLoadImageContainer.classList.remove('hide');
        this.cropImageInstructionsContainer.classList.add('hide');
        this.buttonsContainer.classList.remove('hide');
        this.defaultImageContainer.classList.add('hide');

        // Удаляем  старый кроппр
        this.removeOldCroppr();
        // Создаем элемент нового изображения
        this.createImage();
        // Создаем новый кроппр
        this.initCroppr();
    }

    removeOldCroppr() {
        this.croppr ? this.croppr.destroy() : null;
    }

    createImage() {
        // Если элемент изображения был создан - очищаем весь контейнер для изображения и кроппра    
        if (this.imgElement) {
            this.cropprContainer.innerHTML = '';
        }
        
        // создаем элемент(ноду) изображения
        this.imgElement = document.createElement("img");
        this.imgElement.style.width = "100%";
        // присваиваем ему идентификатор с использованием общего названия контейнера
        this.imgElement.setAttribute('id', `croppr_${ this.nameContainer }`);
        // задаем ему путь
        this.imgElement.src = this.image.src; 
        // вставляем  изображения в контейнер
        this.cropprContainer.append(this.imgElement);
    }

    initCroppr() {
        // создаем экземпляр кроппра передаем название общего контейнера и файл изображения
        let croppr = new InitCroppr({
            nameContainer: this.nameContainer,
            image: this.image,
            getCropDataEvent: this.getCropDataEvent,
        });

        // сохраняем в объект экземпляр кроппра
        this.croppr = croppr.croppr;
    }

    onChange() {
        // инициируем загрузку файла изображения
        new LoadImageFile({
            container: this.imageFileElement,
            imageLoadedEvent: this.imageLoadedEvent,
            imageAlertEvent: this.imageAlertEvent,
        });
    }

    removeLoadedImage() {
        this.croppr = null;
        this.cropprContainer.innerHTML = '';
       
        this.cropLoadImageContainer.classList.remove('hide');
        this.cropImageInstructionsContainer.classList.remove('hide');
        this.buttonsContainer.classList.add('hide');
        this.defaultImageContainer.classList.add('hide');

        // создаем событие удаления изображения
        Emitter.emit(this.removeImageEvent, {
            isImageRemove: true,
        });   
    }

    onDrop(event) {
        this.drugDropPrventDefaults(event);
        this.cropLoadImageContainer.classList.remove('highlight');
        this.files = event.dataTransfer.files;
            
        if (FileReader && this.files && this.files.length) {
            new LoadImageFile({
                container: this.imageFileElement,
                imageLoadedEvent: this.imageLoadedEvent,
                files: event.dataTransfer.files
            });
        }
    }

    onDrugLeave(event) {
        this.drugDropPrventDefaults(event);
        this.cropLoadImageContainer.classList.remove('highlight');
    }

    onDrugEnter(event) {
        this.drugDropPrventDefaults(event);
        this.cropLoadImageContainer.classList.add('highlight');
    }

    drugDropPrventDefaults(event) {
        event.preventDefault();
        event.stopPropagation();
    }
}
