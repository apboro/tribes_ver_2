import { timeFormatting } from "../../core/functions";
import { FormattingData } from "./ProfileBlock/FormattingData";
import { SidebarVisibility } from "./ProfileBlock/SidebarVisibility";

export class ProfileBlock {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="profileBlock"]');
        this.apiToken = document.querySelector('[name="api-token"]').content;
        this.isVisible = true;

        this.dataIsFormatted = false;
        this.dateList = this.container.querySelectorAll('[data-date-format]');

        /* this.formattingData = new FormattingData(this);
        this.sidebarVisibility = new SidebarVisibility(this); */
        this.init();
    }

    async init() {
        // получаем значение видимости при загрузке
        this.isVisible = await this.getSidebarVisibility();
        if (this.isVisible) {
            this.dateFormat();
            this.container.style.maxHeight = this.container.scrollHeight + 'px';
        }
    }

    async toggleVisibility() {
        this.isVisible = !this.isVisible;
        await this.setSidebarVisibility();
        if (!this.isVisible) {
            this.container.style.maxHeight = 0;
        } else {
            this.container.style.maxHeight = this.container.scrollHeight + 'px';
            if(!this.dataIsFormatted) {
                this.dateFormat();
            }
        }
    }

    async getSidebarVisibility() {
        try {
            const res = await window.axios({
                method: 'get',
                url: '/api/session/get?key=is_visible_sidebar',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                }, 
            });
            
            return res.data.value == 'true' ? true : false;
        } catch (error) {
            // если не было записей на сервере о видимости сайдбара присваиваем значение, что сайбар виден
            if (error.response.data.status == 'fail') {
                return true;
            }
            console.log(error);
        }
    }

    async setSidebarVisibility() {
        try {
            const res = await window.axios({
                method: 'post',
                url: '/api/session/put',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                }, 
                data: {
                    key: 'is_visible_sidebar',
                    value: this.isVisible.toString()
                }
            });
            
            return res.data;
        } catch (error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
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
        this.dataIsFormatted = true;
    }
}
