import { TariffPagePublicationsMessage } from "./TariffPageSettings/TariffPagePublicationsMessage";
import { TariffPagePublicationsPayBlock } from "./TariffPageSettings/TariffPagePublicationsPayBlock";

export class TariffPagePublications {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="tariffPagePublications"]');

        this.init();
    }

    init() {
        if (this.isBlock('[data-tab="tariffPagePublicationsPay"]')) {
            this.payBlock = new TariffPagePublicationsPayBlock({
                parent: this.container,
            });
        }

        if (this.isBlock('[data-tab="tariffPagePublicationsMessage"]')) {
            this.messageBlock = new TariffPagePublicationsMessage({
                parent: this.container,
            });
        }
    }

    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }
}
