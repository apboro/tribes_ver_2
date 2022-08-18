export class BlockElements {
    constructor() {
        this.isAdminModeActive = false;

        this.buttons = document.querySelectorAll('[type="submit"]');

        this.init();
    }

    init() {
        if (document.body.dataset.admin) {
            this.isAdminModeActive = true;
            this.buttons.forEach((button) => {
                button.classList.add('disabled')
            });
            console.log(this.buttons);
        }
    }
}
