export class ImagePreview {
    constructor(options) {
        // Контейнер с имеющейся картинкой и канвасом для отрисовки новых изображений
        this.container = options.parent.querySelector(options.selector);
        this.defaultImg = this.container.querySelector('img');
        this.canvas = this.container.querySelector('canvas');

        // событие работы кроппра
        this.cropEvent = `GetCropData:${ options.id }`

        // контекст канваса
        this.ctx = this.canvas.getContext('2d');

        // переданное для отрисовки изображение и координаты по которым его нужно отрисовать
        this.img = null;
        this.cropX = null;
        this.cropY = null;
        this.cropWidth = null;
        this.cropHeight = null;
        
        this.init();
    }

    init() {
        this.listener();
    }

    listener() {
        Emitter.subscribe(this.cropEvent, (data) => {
            this.onCropEvent(data);
        });
    }

    onCropEvent(data) {
        this.cropData = data.crop;
        this.image = data.crop;
        
        this.canvas.classList.remove('hide');
        this.defaultImg.classList.add('hide');

        this.draw();
    }

    // отрисовка канвас в нужном изображении по переданным координатам кроппра
    draw() {
        this.canvas.width  = this.width;
        this.canvas.height = this.height;
        this.ctx.drawImage(this.image, this.xCoord, this.yCoord, this.width, this.height, 0, 0, this.width, this.height);
    }

    get image() {
        return this.img;
    }

    get xCoord() {
        return this.cropX;
    }

    get yCoord() {
        return this.cropY;
    }

    get width() {
        return this.cropWidth;
    }

    get height() {
        return this.cropHeight;
    }

    set cropData(data) {
        [this.cropX, this.cropY, this.cropWidth, this.cropHeight] = data.cropData.split('|');
    }

    set image(data) {
        this.img = data.img;
    }
}
