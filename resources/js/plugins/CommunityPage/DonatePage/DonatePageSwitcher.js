export class DonatePageSwitcher {
    constructor(options) {
        this.container = options.parent.container;

        // Форма
        this.donateForm = this.container.querySelector(options.containerSelector);

        // Блок доната
        this.donateSwitchingBlockElement = null;
    }

    onChangeDonateItemCheck(element) {
        this.findChangesDonateElements(element);
        this.switchVisibilityDonateItem(element);
    }

    // Переключение видимости доната
    switchVisibilityDonateItem(element) {
        if (element.checked) {
            element.value = true;
            this.activeItem();
        }
        else {
            element.value = false;
            this.inactiveItem();
        }
    }

    // Находим блок доната
    findChangesDonateElements(element) {
        this.donateSwitchingBlockElement = this.donateForm.querySelector(`[data-donate-item-id="${ element.dataset.donateCheckId }"]`);
    }

    activeItem() {
        this.donateSwitchingBlockElement.classList.remove('inactive-form-items');
    }

    inactiveItem() {
        this.donateSwitchingBlockElement.classList.add('inactive-form-items');
    }
}
