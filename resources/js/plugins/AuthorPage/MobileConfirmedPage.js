import { dateFormatting, initPhoneMask, toFormatPhone, toLimitInput } from "../../functions";
import { CreateNode } from "../Helper/CreateNode";

export class MobileConfirmedPage {
    constructor(container) {
        this.container = container;
        this.form = this.container.querySelector('#mobile_confirmed');
        this.code = this.container.querySelector('#country_code');
        this.phone = this.container.querySelector('#phone');
        this.errorField = this.container.querySelector('#phone_error');
        

        this.phoneField = this.container.querySelector('#phone_field');
        //this.dateField = this.container.querySelector('#date_field');

        this.init();
    }

    init() {
        initPhoneMask(this.container);
        /*if (this.phoneField && this.phoneField.dataset.phoneCode && this.phoneField.dataset.phoneNumber) {
            this.phoneField.textContent = toFormatPhone(this.phoneField.dataset.phoneCode, this.phoneField.dataset.phoneNumber);
        }*/
        
        
    }

    async onSubmit() {
        if (this.phone.value && this.phone.value.match(/\d/g).length === 10) {
            this.errorField.classList.add('hidden');
            this.phone.classList.remove('error');
            this.code.classList.remove('error');

            try {
                const { data } = await axios.post(
                    this.form.action,
                    {
                        phone: Number(this.phone.value.match(/\d/g).join('')),
                        code: this.code.value
                    }
                );
                
                if (data.status == true) {
                    this.openRightsModal()
                } else {
                    new Toasts({
                        type: 'error',
                        message: data.message
                    });
                }
            } catch (error) {
                console.error(error)
            }
        } else {
            this.errorField.classList.remove('hidden');
            this.phone.classList.add('error');
            this.code.classList.add('error');
        }
    }
    
    openRightsModal() {
        console.log(phone.value);
        
        // создаем экземпляр модального окна
        new ModalWindow({
            modalEl: this.createModal(),
        });
        setTimeout(() =>{
            this.input.focus();
        }, 500);
    }

    createModal() {
        // Главный контейнер
        const modalContainer = new CreateNode({
            class: 'modal fade text-start modal-success'
        }).init();
        
        const modalWrapper = new CreateNode({
            parent: modalContainer,
            class: 'modal-dialog modal-dialog-centered'
        }).init();

        // контент
        const modalContent = new CreateNode({
            parent: modalWrapper,
            class: 'modal-content'
        }).init();
        
        // Хэдер
        this.createHeader(modalContent);
        
        const form = new CreateNode({
            parent: modalContent,
            tag: 'form',
            id: 'confirmation_form',
            class: 'a',
            action: this.form.dataset.codeAction,
            method: 'post',
        }).init();

        new CreateNode({
            parent: form,
            tag: 'input',
            type: 'hidden',
            value: window.token,
            name: '_token'
        }).init();

        this.createBodyContent(form);

        this.createModalFooter(form);

        return modalContainer;
    }

    createHeader(container) {
        const modalHeader = new CreateNode({
            parent: container,
            class: 'modal-header',
        }).init();
        
        // Заголовок
        new CreateNode({
            parent: modalHeader,
            tag: 'h5',
            class: 'modal-title',
            text: 'Подтвердите номер телефона'
        }).init();
        
        // Кнопка закрытия
        new CreateNode({
            parent: modalHeader,
            tag: 'button',
            class: 'btn-close',
            dataset: {
                bsDismiss: 'modal'
            } 
        }).init();
    }

    createBodyContent(container) {
        // Боди
        const modalBody = new CreateNode({
            parent: container,
            class: 'modal-body'
        }).init();

        this.createSuccessMsg(modalBody);    
        this.createConfirmCodeBlock(modalBody);
    }

    createSuccessMsg(container) {
        const successAlert = new CreateNode({
            parent: container,
            class: 'alert alert-success'
        }).init();

        new CreateNode({
            parent: successAlert,
            class: 'alert-body',
            text: 'Сообщение придет на указанный номер в течение 5 минут.'
        }).init();
    }

    createConfirmCodeBlock(container) {
        const confirmCodeBlock = new CreateNode({
            parent: container,
            class: 'mb-1'
        }).init();

        new CreateNode({
            parent: confirmCodeBlock,
            tag: 'label',
            class: 'form-label',
            for: 'sms_code',
            text: 'Код подтверждения'
        }).init();

        this.input = new CreateNode({
            parent: confirmCodeBlock,
            tag: 'input',
            type: 'number',
            class: 'form-control',
            id: 'sms_code',
            name: 'sms_code',
            placeholder: '1234',
            autofocus: true,
        }).init();
        
        // this.input.focus();

        this.input.oninput = (event) => {
            toLimitInput(event, 4);
            if (event.target.value.length === 4) {
                this.acceptBtn.classList.remove('disabled');
            } else {
                this.acceptBtn.classList.add('disabled')
            }
        };
    }

    createModalFooter(container) {
        this.modalFooter = new CreateNode({
            parent: container,
            class: 'modal-footer'
        }).init();
        
        this.acceptBtn = new CreateNode({
            parent: this.modalFooter,
            tag: 'button',
            class: 'btn btn-success disabled',
            type: 'submit',
            text: 'Подтвердить'
        }).init();

        /* this.acceptBtn.onclick = async (event) => {
            event.preventDefault();
            axios.post(this.form.dataset.codeAction, {
                sms_code: this.input.value
            })
                .then(respons => {
                    console.log(respons);
                    document.location.href = '/profile/mobile'
                })
                .catch(error => {
                    console.error(error)
                });
        }; */
    }

}
