import Croppr from 'croppr';

export class InitCroppr {
    constructor(options) {
        // файл изображения
        this.img = options.image;
        // формируем селектор для инициализации кроппра
        this.selector = `croppr_${ options.nameContainer }`;
        // экземпляр кроппра
        this.croppr = null;

        // событие получения кроп данных
        this.getCropDataEvent = options.getCropDataEvent;

        this.initCroppr();
    }

    initCroppr() {
        // создаем экземпляр кроппра
        this.croppr = new Croppr(`#${ this.selector }`, {
            startSize: [100, 100, '%'],
            minSize: [30, 30, '%'],
            //maxSize: [100, 100, '%'],
        
            // при инициализации
            onInitialize: (instance) => {
                // задаем экземпляру путь от файла изображения
                instance.setImage(this.img.src);
                
                // инициализируем событие обновления кропп данных 
                setTimeout(() => {
                    this.emitCropData();
                }, 10);
            },
        
            onCropMove: (data) => {
                // инициализируем событие обновления кропп данных 
                setTimeout(() => {
                    this.cropMoveAction();
                }, 10);                
            },
        
            onCropEnd: (data) => {
                // инициализируем событие обновления кропп данных 
                setTimeout(() => {
                    this.emitCropData();
                }, 10);
            },
        });
    }

    emitCropData() {
        Emitter.emit(this.getCropDataEvent, {
            crop: {
                isCrop: true,
                cropData: this.getCropData(),
            },
            isImageRemove: false,
        });   
    }

    getCropData() {
        let data = this.croppr.getValue();
        return data.x + '|' + data.y + '|' + data.width + '|' + data.height;
    }

    cropMoveAction() {}
}
