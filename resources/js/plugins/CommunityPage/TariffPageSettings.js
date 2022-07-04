import { TariffPageSettingsCommonBlock } from "./TariffPageSettings/TariffPageSettingsCommonBlock";
import { TariffPageSettingsPayBlock } from "./TariffPageSettings/TariffPageSettingsPayBlock";

export class TariffPageSettings {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="tariffPageSettings"]');

        this.init();
    }

    init() {
        if (this.isBlock('[data-tab="tariffPageSettingsCommon"]')) {
            this.commonBlock = new TariffPageSettingsCommonBlock({
                parent: this.container,
            });
        }

        if (this.isBlock('[data-tab="tariffPageSettingsPay"]')) {
            this.payBlock = new TariffPageSettingsPayBlock({
                parent: this.container,
            });
        }
    }

    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }
}
