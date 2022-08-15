import { timeFormatting } from "../../core/functions";
import { FormattingData } from "./ProfileBlock/FormattingData";
import { SidebarVisibility } from "./ProfileBlock/SidebarVisibility";

export class ProfileBlock {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="profileBlock"]');
        this.isVisible = true;

        this.dateList = this.container.querySelectorAll('[data-date-format]');

            /* this.formattingData = new FormattingData(this);
            this.sidebarVisibility = new SidebarVisibility(this); */
        this.init();
    }

    init() {
        this.dateFormat();
    }

    toggleVisibility() {
        if (this.isVisible) {
            this.container.style.display = 'none';
        } else {
            this.container.style.display = 'grid';
        }
        this.isVisible = !this.isVisible;
    }

    dateFormat() {
        Object.values(this.dateList).forEach((dateItem) => {
            console.log(Date(dateItem.textContent.trim()));
            dateItem.textContent = timeFormatting({
                date: Date(dateItem.textContent),
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        });
    }
}
