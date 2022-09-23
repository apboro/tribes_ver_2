export class Dropdown {
    constructor() {
        this.contents = document.querySelectorAll('[data-dropdown-content]');
        this.btn = null;
        this.listeners();
    }

    listeners() {
        window.addEventListener('click', (event) => {
            if (!event.target.matches('[data-dropdown-btn]')) {
                this.toInactiveAllContents();
                this.removeBtn();
            }
        });
    }

    toggle(btn) {
        // если нет ни одного открытого меню
        if (!this.btn) {
            this.saveBtn(btn);
            this.toActiveContent();
        }
        // если есть открытое меню, и клик осуществяется не на том же меню
        else if (this.btn && this.btn != btn ) {
            this.toInactiveAllContents();
            this.saveBtn(btn);
            this.toActiveContent();
        }
        // если есть открытое меню, и клик осуществяется на том же меню
        else if (this.btn && this.btn == btn) {
            this.toInactiveContent();
            this.removeBtn();
        }
    }

    toActiveContent() {
        this.btn.classList.add('active');
        this.btn.nextElementSibling.classList.add('active');
    }

    toInactiveContent() {
        this.btn.classList.remove('active');
        this.btn.nextElementSibling.classList.remove('active');
    }

    saveBtn(btn) {
        this.btn = btn;
    }

    removeBtn() {
        this.btn = null;
    }

    toInactiveAllContents() {
        this.contents.forEach((content) => {
            if (content.classList.contains('active') && this.btn) {
                content.classList.remove('active');
                this.btn.classList.remove('active');
            }
        });
    }
}
