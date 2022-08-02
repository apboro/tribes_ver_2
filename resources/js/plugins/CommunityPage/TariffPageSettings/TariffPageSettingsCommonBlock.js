import { CropImageController } from "../../Helper/CropImageController/CropImageController";

export class TariffPageSettingsCommonBlock {
    constructor(options) {
        this.container = options.parent.querySelector('[data-tab="tariffPageSettingsCommon"]');
        this.trialPeriodSelect = this.container.querySelector('#trial_period');
        

        this.init();
    }

    init() {
        
        this.initCroppImageController();
    }

    
    initCroppImageController() {
        this.croppImageControllerWelcome = new CropImageController({
            container: this.container,
            id: 'welcome',
        });

        this.croppImageControllerReminder = new CropImageController({
            container: this.container,
            id: 'reminder',
        });

        this.croppImageControllerSuccess = new CropImageController({
            container: this.container,
            id: 'success',
        });

        this.croppImageControllerPublication = new CropImageController({
            container: this.container,
            id: 'publication',
        });
    }

    trialPeriodAttention(event, savedPeriod) {
        event.preventDefault();
        if (savedPeriod != '0' && this.trialPeriodSelect.value == '0') {
            new ModalWindow({
                modalEl: this.createModal(),
            });
        } else {
            this.container.submit();
        }
    }

    createModal() {
        let modalContainer = document.createElement('div');
        modalContainer.className = `modal fade text-start modal-warning`;

        let modalWrapper = document.createElement('div');
        modalWrapper.className = 'modal-dialog modal-dialog-centered';
        modalContainer.append(modalWrapper);

        let modalContent = document.createElement('div');
        modalContent.className = 'modal-content';
        modalWrapper.append(modalContent);

        let modalHeader = document.createElement('div');
        modalHeader.className = 'modal-header';
        modalContent.append(modalHeader);

        let modalTitle = document.createElement('h5');
        modalTitle.className = 'modal-title';
        modalTitle.textContent = Dict.write('service_message', 'warning');
        modalHeader.append(modalTitle);

        let closeBtn = document.createElement('button');
        closeBtn.className = 'btn-close';
        closeBtn.dataset.bsDismiss = 'modal';
        modalHeader.append(closeBtn);

        let modalBody = document.createElement('div');
        modalBody.className = 'modal-body';
        modalBody.textContent = Dict.write('service_message', 'none_tariff_period');
        modalContent.append(modalBody);

        let modalFooter = document.createElement('div');
        modalFooter.className = 'modal-footer';
        modalContent.append(modalFooter);

        let acceptBtn = document.createElement('button');
        acceptBtn.className = 'btn btn-warning';
        acceptBtn.textContent = Dict.write('base', 'i_know');
        acceptBtn.onclick = () => this.container.submit();
        modalFooter.append(acceptBtn);

        return modalContainer;
    }
}
