import { CreateNode } from "./CreateNode";

export default class BurgerMenu {
    constructor() {
        this.menu = document.querySelector('.main-nav');
        this.overlay = null;
        this.isActive = false;
    }

    toggle() {
        if (this.isActive) {
            this.hide();
        } else {
            this.show();
        }
    }

    show() {
        // меню отображаем
        this.menu.classList.add('active');
        // создаем темный фон
        this.initOverlay();
        this.isActive = true;
    }

    hide() {
        // меню скрываем
        this.menu.classList.remove('active');
        // темный фон удаляем
        this.destroyOverlay();
        this.isActive = false;
    }

    initOverlay() {
        this.overlay = new CreateNode({
            parent: document.body,
            class: 'overlay'
        }).init();

        this.overlay.classList.add('active');
        this.overlay.onclick = () => this.hide();
    }

    destroyOverlay() {
        this.overlay.classList.remove('active');
        // даем время для отработки анимации закрытия
        setTimeout(() => {
            this.overlay.remove();
            this.overlay = null;
        }, 300)
    }
}
