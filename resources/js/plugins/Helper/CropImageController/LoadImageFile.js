export class LoadImageFile {
    constructor(options) {
        // эллемент инпут файла
        this.fileContainer = options.container;
        // событие загрузки файла изображения
        this.imageLoadedEvent = options.imageLoadedEvent;
        this.imageAlertEvent = options.imageAlertEvent;

        // массив загружаемых файлов (может быть передан от драгдроп)
        this.files = options.files ? options.files : null;
        // файл изображения
        this.imgFile = null;

        this.init();
    }

    init() {
        // подхватываем загружаемые файлы
        if (!this.files) {
            this.files = this.fileContainer.files;
        }

        // создаем файл
        if (FileReader && this.files && this.files.length) {
            this.createFile();            
        }
    }

    createFile() {
        this.file = new FileReader();
        this.file.readAsDataURL(this.files[0]);
        
        // если выбранный файл больше допустимого размера - не загружаем его
        if (this.isBigFile()) {
            return false;
        }

        // когда файл загружен
        this.file.onload = (event) => {
            // создаем файл изображения
            this.createImage(event.target);
        };
    }

    isBigFile() {
        if (this.files[0].size > 2097152) {
            /*new Toasts({
                type: 'error',
                message: Dict.write('service_message', 'max_size_img')
            });*/
            Emitter.emit(this.imageAlertEvent, {
                type: 'error',
                message: Dict.write('service_message', 'max_size_img'),
            });
            return true;
        }
        return false;
    }

    createImage(file) {
        this.img = new Image();
        this.img.src = file.result;

        // когда файл изображения загружен
        this.img.onload = () => {
            // проверяем параметры изображения
            //this.checkImageParams();

            // инициируем событие загрузки файла изображения и передаем этот файл
            Emitter.emit(this.imageLoadedEvent, {
                image: this.img,
            });
        };
    }

    checkImageParams() {
        let warnings = 0;

        if (this.img.width > this.img.height && Math.ceil(this.img.width / this.img.height) > 2) {          
            warnings += 1;

            new Toasts({
                type: 'warning',
                message: Dict.write('service_message', 'limit_width_ratio_img')
            });
        }

        if (this.img.height > this.img.width && Math.ceil(this.img.height / this.img.width) > 2) {
            warnings += 1;

            new Toasts({
                type: 'warning',
                message: Dict.write('service_message', 'limit_height_ratio_img')
            });
        }

        if (this.img.width < 300 || this.img.height < 300) {
            warnings += 1;
        
            /*new Toasts({
                type: 'warning',
                message: Dict.write('service_message', 'min_size_img')
            });   */
            new AlertMessage({
                selector: '[data-image-alert="main"]',
                type: 'warning',
                message: Dict.write('service_message', 'min_size_img'),
            }); 
        }

        if(!warnings) {
            new Toasts({
                type: 'info',
                message: Dict.write('service_message', 'select_image_area')
            })
        }
    }
}
