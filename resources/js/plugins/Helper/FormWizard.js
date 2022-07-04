import Stepper from 'bs-stepper';

export class FormWizard {
    constructor(parent) {
        this.parent = parent;
        this.bsStepper = document.querySelectorAll('.bs-stepper');
        this.modernWizard = document.querySelector('.modern-wizard-example');

        this.init();
    }

    init() {
        if (typeof this.bsStepper !== undefined && this.bsStepper !== null) {
            this.switchingTitleSteps();
        }

        if (typeof this.modernWizard !== undefined && this.modernWizard !== null) {
            this.switchingByBtns();
        }
    }

    switchingTitleSteps() {
        this.bsStepper.forEach((el) => {
            el.addEventListener('show.bs-stepper', (event) => {
                const index = event.detail.indexStep;
                const numberOfSteps = event.target.querySelectorAll('.step').length - 1;
                const line = event.target.querySelectorAll('.step');
    
                for (let i = 0; i < index; i++) {
                    line[i].classList.add('crossed');
    
                    for (let j = index; j < numberOfSteps; j++) {
                        line[j].classList.remove('crossed');
                    }
                }
    
                if (event.detail.to == 0) {
                    for (let k = index; k < numberOfSteps; k++) {
                        line[k].classList.remove('crossed');
                    }

                    line[0].classList.remove('crossed');
                }
            });
        });
    }

    switchingByBtns() {
        const modernStepper = new Stepper(this.modernWizard, {
            linear: false
        });
    
        this.modernWizard
            .querySelectorAll('.btn-next')
            .forEach((btn) => {
                btn.addEventListener('click', () => {
                    this.eventAtNext(modernStepper);
                });
            })
    
        this.modernWizard
            .querySelectorAll('.btn-prev')
            .forEach((btn) => {
                btn.addEventListener('click', () => {
                    this.eventAtPrevious(modernStepper);
                });
            });
    
        const uploadBtn = this.modernWizard.querySelector('.btn-submit');
        uploadBtn.addEventListener('click', () => {
            this.eventAtSubmit(uploadBtn);
        });
    }

    eventAtNext(stepperInstance) {
        stepperInstance.next();
    }

    eventAtPrevious(stepperInstance) {
        stepperInstance.previous();
    }

    eventAtSubmit(btn) {}
}
