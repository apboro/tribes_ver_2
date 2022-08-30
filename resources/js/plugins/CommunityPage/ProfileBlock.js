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
        this.container.style.maxHeight = this.container.scrollHeight + 'px';
    }

    toggleVisibility() {
        if (this.isVisible) {
            this.container.style.maxHeight = 0;
            // this.container.style.opacity = 0;
        } else {
            this.container.style.maxHeight = this.container.scrollHeight + 'px';
            // this.container.style.opacity = 1;
        }
        this.isVisible = !this.isVisible;
        // if (this.isVisible) {
        //     this.container.style.display = 'none';
        // } else {
        //     this.container.style.display = 'grid';
        // }
        // this.isVisible = !this.isVisible;
    }

    dateFormat() {
        Object.values(this.dateList).forEach((dateItem) => {
            dateItem.innerText = timeFormatting({
                date: dateItem.innerText,
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        });
    }
}
