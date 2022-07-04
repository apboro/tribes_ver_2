import Stepper from "bs-stepper";

export class FormWizardHorizontal {
    constructor(parent) {
        this.parent = parent;
        this.horizontalWizard = document.querySelector('.horizontal-wizard-example');

        this.init();
    }

    init() {
        if (typeof this.horizontalWizard !== undefined && this.horizontalWizard !== null) {
            this.numberedStepper = new Stepper(this.horizontalWizard);
        }
    }

    toNext() {
        this.numberedStepper.next();
    }

    toPrevious() {
        this.numberedStepper.previous();
    }

    toSubmit() {}
}
