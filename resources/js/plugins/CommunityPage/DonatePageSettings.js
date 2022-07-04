import { toLimitInput } from "../../functions";
import { CropImageController } from "../Helper/CropImageController/CropImageController";
import { DonatePageSwitcher } from "./DonatePage/DonatePageSwitcher";

export class DonatePageSettings {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="donatePageSettings"]');

        this.init();
    }

    init() {
        this.initCroppImageController();
        this.initPageSwitcher();
    }

    initCroppImageController() {
        this.croppImageControllerSuccess = new CropImageController({
            container: this.container,
            id: 'success',
        });
    }

    initPageSwitcher() {
        // Переключатель активности донат-опций
        this.donateSwitcher = new DonatePageSwitcher({
            parent: this,
            containerSelector: '[data-switcher-cotainer]'
        });
    }

    onInputTimeInput(event) {
        // ограничиваем ввод
        toLimitInput(event, 2);
    }
}
