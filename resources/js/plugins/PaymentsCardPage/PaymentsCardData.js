import { createServerErrorMessage, debounce } from "../../functions";

export class PaymentsCardData {
    constructor(parent) {
        // данные о валюте, адресе почты, номере карты
        this.currency = null;
        this.email = null;
        this.card_number = null;

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
        });
    }

    onEmailInput(event) {
        debounce(() => {
            this.setEmail = event.target.value;
        }, 500)()
    }

    onCardNumberInput(event) {
        debounce(() => {
            this.setCardNumber = event.target.value;
        }, 500)()
    }

    async onSubmit() {
        try {
            const resp = await window.axios({
                method: 'post',
                url: '/1',
                data: this.getData,
            });
            
            const data = await resp.json();
        } catch(error) {
            
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
        console.log(this.getData);
    }

    get getData() {
        if (this.currency === 'russia') {
            return {
                currency: this.currency,
                email: this.email,
            };
        } else if (this.currency === 'other') {
            return {
                currency: this.currency,
                email: this.email,
                card_number: this.card_number,
            };
        }
    }

    set setCurrency(value) {
        this.currency = value;
    }

    set setEmail(value) {
        this.email = value;
    }

    set setCardNumber(value) {
        this.card_number = value;
    }
}
