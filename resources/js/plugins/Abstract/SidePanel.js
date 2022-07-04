import { Offcanvas } from "bootstrap";

export class SidePanel {
    constructor(options) {
        // контейнер панели
        this.sidePanel = document.querySelector('#offcanvas_container');
        // экземпляр класса
        this.bsOffcanvas = new Offcanvas(this.sidePanel);

        // заголовок, контент
        //this.side = options.side;
        this.title = options.title ?? undefined;
        this.content = options.content;
        
        this.init();       
    }

    init() {
        //this.determineSide();
        // создаем данные в блоке
        this.createSidePanelBlock();
        // запускаем слушатели событий
        this.listeners();
    }
    
    /* determineSide() {
        switch (this.side) {
            case 'left':
                this.sidePanel.classList.add('offcanvas-start');
            case 'right':
                this.sidePanel.classList.add('offcanvas-end');
        }
    } */

    listeners() {
        // после закрытия панели - очищаем панель
        this.sidePanel.addEventListener('hidden.bs.offcanvas', () => {
            this.clear();
        })
    }

    createSidePanelBlock() {
        // создаем голову и тело панели
        this.sidePanel.append(this.createHeaderBlock());
        this.sidePanel.append(this.createBodyBlock());
    }

    createHeaderBlock() {
        const headerBlock = document.createElement('div');
        headerBlock.className = 'offcanvas-header';
        // заголовок(опционально)
        let titleBlock = `<div></div>`;
        if (this.title) {
            titleBlock = `<h5 class="offcanvas-title" id="offcanvasExampleLabel">${ this.title }</h5>`
        }
        // кнопка закрытия
        headerBlock.innerHTML = `
            ${ titleBlock }
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        `;
        return headerBlock;
    }

    createBodyBlock() {
        // в тело вставляем контент генерируемы индивидуально
        const bodyBlock = document.createElement('div');
        bodyBlock.className = 'offcanvas-body';
        bodyBlock.append(this.content);

        return bodyBlock;
    }

    // показать
    show() {
        this.bsOffcanvas.show()
    }

    // скрыть
    hide() {
        this.bsOffcanvas.hide()
    }

    // очистить
    clear() {
        this.sidePanel.innerHTML = '';
    }
}
