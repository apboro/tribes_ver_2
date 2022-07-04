import { Modal } from "bootstrap";

export class ModalWindow {
    constructor(options) {
        this.modalContainer = document.querySelector('#modal_container');
        this.modal = null;
        
        this.onShowCallback = options.onShowCallback ?? null;
        this.onHideCallback = options.onHideCallback ?? null;
        
        this.modalEl = options.modalEl;
        console.log(this.modalEl);
        this.init();
    }

    init() {
        this.clearContainer();
        this.createModalEl();
        this.createModalInstance();
        this.initListeners();
    }

    /*init() {
        this.clearContainer();
        this.createModalHTMLEl();
        this.createModalInstance();
        this.initListeners();
    }*/

    clearContainer() {
        this.modalContainer.innerHTML = '';
    }

    createModalEl() {
        this.modalContainer.append(this.modalEl);
    }

    createModalHTMLEl() {
        this.modal = createElementFromHTML(`
            <div class="modal fade text-start modal-${ this.type }" id="primary" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel160">${ this.heatTitleText }</h5>
                            <button class="btn-close pointer" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${ this.message }
                        </div>
                        <div class="modal-footer">
                            <button class="btn pointer btn-${ this.type }" data-bs-dismiss="modal">${ Dict.write('base', 'i_know') }</button>
                        </div>
                    </div>
                </div>
            </div>
        `);
        this.modalContainer.append(this.modal);
    }

    createModalInstance() {
        this.instance = new Modal('.modal', {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        this.instance.show();
    }

    hide() {
        this.instance.hide();
    }

    initListeners() {
        if (this.onShowCallback) {
            this.modalEl.addEventListener('shown.bs.modal', () => { this.onShowCallback(); });
        }
        
        if (this.onHideCallback) {
            this.modalEl.addEventListener('hidden.bs.modal', () => { this.onHideCallback(); });
        }
    }

    get heatTitleText() {
        switch (this.type) {
            case 'success': return Dict.write('service_message', 'success');
            case 'error': return Dict.write('service_message', 'error');
            case 'warning': return Dict.write('service_message', 'warning');
            case 'info': return Dict.write('service_message', 'info');
        }
    }
}
