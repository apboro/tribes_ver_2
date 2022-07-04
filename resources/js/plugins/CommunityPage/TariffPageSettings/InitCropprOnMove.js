import { InitCroppr } from "../../Helper/CropImageController/InitCroppr";

export class InitCropprOnMove extends InitCroppr {
    constructor(options) {
        super(options);
    }
    
    emitCropData() {
        // передаем в событии кроп данные
        Emitter.emit(this.getCropDataEvent, {
            crop: {
                isCrop: true,
                cropData: this.getCropData(),
                img: this.img,
            },
            isImageRemove: false,
        });   
    }

    cropMoveAction() {
        // передаем в событии кроп данные
        Emitter.emit(this.getCropDataEvent, {
            crop: {
                isCrop: true,
                cropData: this.getCropData(),
                img: this.img,
            },
            isImageRemove: false,
        });  
    }
}