import { PaymentsCardData } from "./PaymentsCardPage/PaymentsCardData";
import { PaymentsCardTemplate } from "./PaymentsCardPage/PaymentsCardTemplate";
import { PaymentsFormWizard } from "./PaymentsCardPage/PaymentsFormWizard";
import Page from "./Abstract/Page";


export default class PaymentsCardPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        this.initEvents();

        this.formWizard = new PaymentsFormWizard(this);
        this.data = new PaymentsCardData(this);
        this.formTemplate = new PaymentsCardTemplate(this);

        
    }

    initEvents() {
        this.currencyEvent = 'PaymentsCard:currency';
    }

   
}
