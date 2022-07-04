export class PaymentsCardTemplate {
    constructor(parent) {
        // верстка элементов видимостью которых будем управлять
        this.cardNumberStep = parent.container.querySelector('#card_number_block_trigger')
        this.cardNumberStepChevron = parent.container.querySelector('#card_number_block_trigger_chevron')
        this.emailBlock = parent.container.querySelector('#email_block');
        this.emailBlockNextBtn = this.emailBlock.querySelector('.btn-next');
        this.emailBlockSubmitBtn = this.emailBlock.querySelector('.btn-submit');

        this.currency = null;

        // событие выбора валюты
        this.currencyEvent = parent.currencyEvent;
    
        this.init();
    }

    init() {
        this.listeners();
    }

    listeners() {
        Emitter.subscribe(this.currencyEvent, (data) => {
            this.setCurrency = data.currency;
            this.changeTemplate();
        });
    }

    changeTemplate() {
        if (this.getCurrency === 'russia') {
            this.hideCardNumberStep();
            this.emailBlockNextBtn.classList.add('hide');
            this.emailBlockSubmitBtn.classList.remove('hide');
        } else if (this.getCurrency === 'other') {
            this.showCardNumberStep();
            this.emailBlockNextBtn.classList.remove('hide');
            this.emailBlockSubmitBtn.classList.add('hide');
        }
    }

    showCardNumberStep() {
        this.cardNumberStepChevron.classList.remove('hide');
        this.cardNumberStep.classList.remove('hide');
    }

    hideCardNumberStep() {
        this.cardNumberStepChevron.classList.add('hide');
        this.cardNumberStep.classList.add('hide');
    }
    
    get getCurrency() {
        return this.currency;
    }

    set setCurrency(value) {
        this.currency = value;
    }
}
