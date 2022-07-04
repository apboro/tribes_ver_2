import { FormWizardHorizontal } from "../Helper/FormWizardHorizontal";

export class PaymentsFormWizard extends FormWizardHorizontal {
    constructor(parent) {
        super(parent);

        this.currencyEvent = parent.currencyEvent; 
    }

    toNextFromCurrency(event) {
        event.target.checked = false;
        Emitter.emit(this.currencyEvent, {
            currency: event.target.value,
        });
        this.toNext();
    }
}
