import { FormattingData } from "./ProfileBlock/FormattingData";
import { SidebarVisibility } from "./ProfileBlock/SidebarVisibility";

export class ProfileBlock {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="profileBlock"]');

        this.formattingData = new FormattingData(this);
        this.sidebarVisibility = new SidebarVisibility(this);
    }
}
