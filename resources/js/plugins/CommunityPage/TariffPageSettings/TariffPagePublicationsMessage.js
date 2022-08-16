import { CropImageController } from "../../Helper/CropImageController/CropImageController";

export class TariffPagePublicationsMessage {
    constructor(options) {
        this.container = options.parent.querySelector('[data-tab="tariffPagePublicationsMessage"]');

        this.init();
    }

    init() {
        this.initCroppImageController();
    }

    
    initCroppImageController() {
        this.croppImageControllerPublication = new CropImageController({
            container: this.container,
            id: 'publication',
        });
    }
}
