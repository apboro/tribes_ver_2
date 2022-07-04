import Page from "./Abstract/Page";
import { OutcomePage } from "./PaymentsPage/OutcomePage";
import { PaymentsCardListPage } from "./PaymentsPage/PaymentsCardListPage";

export default class PaymentsPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        if (this.isBlock('[data-tab="PaymentsCardListPage"]')) {
            this.paymentsCardListPage = new PaymentsCardListPage(this);
        }

        if (this.isBlock('[data-tab="OutcomePage"]')) {
            this.outcomePage = new OutcomePage(this);
        }
    }

    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }
}
