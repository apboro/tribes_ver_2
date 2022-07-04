import { CropImageController } from "../../Helper/CropImageController/CropImageController";
import { InitCropprOnMove } from "./InitCropprOnMove";

export class CropImageOnMoveCroppController extends CropImageController {
    constructor(options) {
        super(options);
    }

    initCroppr() {
        // создаем экземпляр кроппра передаем название общего контейнера и файл изображения
        let croppr = new InitCropprOnMove({
            nameContainer: this.nameContainer,
            image: this.image,
            getCropDataEvent: this.getCropDataEvent,
        });

        // сохраняем в объект экземпляр кроппра
        this.croppr = croppr.croppr;
    }
}