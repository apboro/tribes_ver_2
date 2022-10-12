import { Toast } from "bootstrap";

export class Toasts {
    constructor (options) {
        this.toastContainer = document.querySelector('#toasts_container');
        this.toast = null;

        this.toastSettings = {
            animation: true,
            autohide: true,
            delay: 5000
        };
        
        this.type = options.type;
        this.message = options.message;

        this.toastClass = 'toast-new';

        this.init();
    }

    init() {
        this.clearContainer();
        this.createToastHTMLEl();
        this.createToastInstance();
    }

    clearContainer() {
        this.toastContainer.innerHTML = '';
    }

    createToastHTMLEl() {
        this.toast = createElementFromHTML(`
            <div class="${ this.toastClass } ${ this.toastModifier }">
                ${ this.message }
            </div>
        `);
        this.toastContainer.append(this.toast);
    }

    // createToastHTMLEl() {
    //     this.toast = createElementFromHTML(`
    //         <div class="col-10">
    //             <div class="toast toast-basic">
    //                 <div class="toast-header p-1 ${ this.headColorClass }">
    //                     <div class="me-1">
    //                         ${ this.headIcon }
    //                     </div>
    //                     <strong class="me-auto">${ this.heatTitleText }</strong>
    //                     <div class="close pointer" data-bs-dismiss="toast" aria-label="Close">
    //                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    //                     </div>
    //                 </div>
                    
    //                 <div class="toast-body">
    //                     ${ this.message }
    //                 </div>
    //             </div>
    //         </div>
    //     `);
    //     this.toastContainer.append(this.toast);
    // }

    get toastModifier() {
        switch (this.type) {
            case 'success': return `${ this.toastClass }--success`;
            case 'error': return `${ this.toastClass }--danger`;
            case 'warning': return `${ this.toastClass }--warning`;
            case 'info': return `${ this.toastClass }--info`;
        }
    }

    get headColorClass() {
        switch (this.type) {
            case 'success': return 'bg-gradient-success';
            case 'error': return 'bg-gradient-danger';
            case 'warning': return 'bg-gradient-warning';
            case 'info': return 'bg-gradient-info';
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

    get headIcon() {
        switch (this.type) {
            case 'success': return `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
            case 'error': return `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;
            case 'warning': return `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
            case 'info': return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
        }
    }

    createToastInstance() {
        new Toast(`.${ this.toastClass }`, {
            animation: this.toastSettings.animation,
            autohide: this.toastSettings.autohide,
            delay: this.toastSettings.delay
        }).show();
    }
}
