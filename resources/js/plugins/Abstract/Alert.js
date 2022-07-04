export class AlertMessage {
    constructor(options) {
        this.container = options.container;
        this.type = options.type;
        this.message = options.message;

        this.timeout = null;

        this.init();
    }

    init() {
        this.cleanContrainer();
        this.createAlert();
        this.show();
        this.clearTimer();
    }

    cleanContrainer() {
        this.container.innerHTML = '';
    }

    show() {
        this.container.classList.remove('hide');
    }

    hide() {
        this.container.classList.add('hide');
    }

    createAlert() {
        const alertEl = document.createElement('div');
        alertEl.onclick = () => {
            this.close();
        }

        alertEl.onmouseenter = () => {
            clearTimeout(this.timeout);
        }

        alertEl.onmouseleave = () => {
            this.clearTimer();
        }

        alertEl.innerHTML = `
            <div role="alert" aria-live="polite" aria-atomic="true" class="alert ${ this.headColorClass } mb-0 mt-1 mt-lg-0 pointer">
                <div class="alert-body">
                    <span class="me-1">${ this.headIcon }</span>
                    ${ this.message }
                </div>
            </div>
        `;

        this.container.append(alertEl);
    }

    close() {
        this.cleanContrainer();
        this.hide();
    }

    clearTimer() {
        this.timeout = setTimeout(() => {
            this.close();
        }, 5000);
    }

    get headColorClass() {
        switch (this.type) {
            case 'success': return 'alert-success';
            case 'error': return 'alert-danger';
            case 'warning': return 'alert-warning';
            case 'info': return 'alert-info';
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
}
