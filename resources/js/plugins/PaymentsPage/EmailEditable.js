export class EmailEditable {
    constructor(container, id) {
        this.container = container;
        this.cardId = id;

        // находим саму карточку и эллементы формы, почты, и подписи в этой карточке
        this.cardContainer = this.container.querySelector(`[data-card-id="${ this.cardId }"]`);
        this.emailForm = this.cardContainer.querySelector('#email_form');
        this.email = this.cardContainer.querySelector('#email');
        this.emailLabel = this.cardContainer.querySelector('#email_label');

        this.defaultEmail = {};
    }

    showEmailForm() {
        // сохраняем изначальное значение почты
        this.setDefaultEmail = this.email.value;

        this.emailForm.classList.remove('hide');
        this.emailLabel.classList.add('hide');
    }

    hideEmailForm() {
        // возвращаем исходное значение
        this.setEmailValue = this.defaultEmail;

        this.emailForm.classList.add('hide');
        this.emailLabel.classList.remove('hide');
    }

    set setDefaultEmail(value) {
        this.defaultEmail = value;
    }

    set setEmailValue(value) {
        this.email.value = value;
    }
}
