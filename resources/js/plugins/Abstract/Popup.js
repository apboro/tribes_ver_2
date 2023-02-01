import { CreateNode } from "../Helper/CreateNode";

export class Popup {
    constructor(options) {
        this.modalContainer = document.querySelector('#modal_container');
        this.lockElements = document.querySelectorAll('[data-lock-element]');
        
        this.modal = null;
        this.header = null;
        this.content = null;
        this.footer = null;

        this.closeBtn = null;

        this.title = options.title ?? 'Title';
        this.contentNode = options.content ?? false;
        this.footerNode = options.footer ?? false;
        
        this.init();
    }

    init() {
        this.clearContainer();
        this.createModalBody();
        this.createHeader();
        this.createContent();
        this.createFooter();
        this.modalContainer.append(this.modal);
        this.bodyLock();
    }

    clearContainer() {
        this.modalContainer.innerHTML = '';
    }

    createModalBody() {
        this.modal = new CreateNode({}).init();

        this.overlay = new CreateNode({
            parent: this.modal,
            class: 'overlay'
        }).init();

        this.overlay.onclick = () => this.hide();

        this.modalBody = new CreateNode({
            parent: this.modal,
            tag: 'section',
            class: 'popup'
        }).init();
    }

    createHeader() {
        this.header = new CreateNode({
            parent: this.modalBody,
            tag: 'header',
            class: 'popup__header'
        }).init();

        new CreateNode({
            parent: this.header,
            tag: 'h2',
            class: 'popup__title',
            text: this.title
        }).init();

        this.closeBtn = new CreateNode({
            parent: this.header,
            tag: 'button',
            class: 'popup__close-btn',
            text: 'X'
        }).init();

        this.closeBtn.onclick = () => this.hide();
    }

    createContent() {
        if (!this.contentNode) {
            this.content = new CreateNode({
                parent: this.modalBody,
                class: 'popup__content',
            }).init();

            return false;
        }

        this.content = new CreateNode({
            parent: this.modalBody,
            class: 'popup__content'
        }).init();
        
        Object.values(this.contentNode.children).forEach((element) => {
            this.content.append(element);
        });
    }

    createFooter() {
        if (!this.footerNode) {
            return false;
        }

        this.footer = new CreateNode({
            parent: this.modalBody,
            tag: 'footer',
            class: 'popup__footer'
        }).init();

        Object.values(this.footerNode.children).forEach((element) => {
            this.footer.append(element);
        });
    }

    hide() {
        this.clearContainer();
        this.bodyUnlock();
    }

    bodyLock() {
        const lockPaddingValue = window.innerWidth - document.body.offsetWidth + 'px';
        document.body.style.paddingRight = lockPaddingValue;
        document.body.classList.add('locked');

        /* if (this.lockElements) {
            this.lockElements.forEach((element) => {
                element.style.paddingRight = lockPaddingValue;
            });
        } */
    }
    
    bodyUnlock() {
        document.body.style.paddingRight = 0;
        document.body.classList.remove('locked');
    }
}
