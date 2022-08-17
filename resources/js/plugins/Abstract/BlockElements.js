export class BlockElements {
    constructor() {
        this.buttons = document.querySelectorAll('[type="submit"]')

        this.init();
    }

    init() {
        if (document.body.dataset.admin) {
            this.buttons.forEach((button) => {
                button.classList.add('disabled')
            });
            console.log(this.buttons);
        }
    }
}
