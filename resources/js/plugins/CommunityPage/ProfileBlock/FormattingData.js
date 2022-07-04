import { dateFormatting, numberFormatting } from "../../../functions";

export class FormattingData {
    constructor(options) {
        this.container = options.container;

        this.balanceTitle = this.container.querySelector('#balance_title');
        this.createDateTitle = this.container.querySelector('#create_date_title');
        this.uniqueVisitorsTitle = this.container.querySelector('#unique_visitors_title');
        this.renewedSubscriptionsTitle = this.container.querySelector('#renewed_subscription_title')
        this.paymentPageViews = this.container.querySelector('#payment_page_views')
        this.donationAmountsTitle = this.container.querySelector('#donation_amounts_title');
        this.donationCountsTitle = this.container.querySelector('#donation_count_title');
        this.tariffAmountsTitle = this.container.querySelector('#tariff_amounts_title');
        this.tariffCountsTitle = this.container.querySelector('#tariff_count_title');

        this.init();
    }

    init() {
        window.addEventListener('load', () => {
            this.toFormateData();
        });
    }

    toFormateData() {
        this.toCurrencyFormat(this.balanceTitle);
        this.toDateFormat(this.createDateTitle);
        this.toNumberFormat(this.uniqueVisitorsTitle);
        this.toNumberFormat(this.renewedSubscriptionsTitle);
        this.toNumberFormat(this.paymentPageViews);
        this.toCurrencyFormat(this.donationAmountsTitle);
        this.toNumberFormat(this.donationCountsTitle);
        this.toCurrencyFormat(this.tariffAmountsTitle);
        this.toNumberFormat(this.tariffCountsTitle);
    }

    toDateFormat(node) {
        node.textContent = dateFormatting({
            date: Date.parse(node.textContent),
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    }

    toNumberFormat(node) {
        node.textContent = numberFormatting({
            value: parseInt(node.textContent)
        });
    }

    toCurrencyFormat(node) {
        node.textContent = numberFormatting({
            value: parseInt(node.textContent),
            currency: true,
        });
    }
}
