import Page from "./Abstract/Page";

export default class TariffConfirmation extends Page {
    constructor(container) {
        super(container);

        this.rightsContainer = null;
        this.rightsInputs = null;
        this.submitBtn = null;
    }

    init() {
        this.rightsContainer = this.container.querySelector('.confirmation_subscription__body');
        this.rightsInputs = this.rightsContainer.querySelectorAll('input');
        this.rightsInputs.forEach((el) => {
            console.log(el.checked);
            el.checked = true;
            console.log(el.checked);
        });
        console.log(this);
    }

    
}
