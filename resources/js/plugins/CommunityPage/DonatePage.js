import { CropImageController } from "../Helper/CropImageController/CropImageController";
import { DonatePageSwitcher } from "./DonatePage/DonatePageSwitcher";

export class DonatePage {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="donatePage"]');

        this.init();
    }

    init() {
        this.initCroppImageController();        
        this.initPageSwitcher();
    }

    initCroppImageController() {
        this.croppImageControllerMain = new CropImageController({
            container: this.container,
            id: 'main',
        });
    }

    initPageSwitcher() {
        // Переключатель активности донат-опций
        this.donateSwitcher = new DonatePageSwitcher({
            parent: this,
            containerSelector: '[data-switcher-cotainer]'
        });
    }
}
