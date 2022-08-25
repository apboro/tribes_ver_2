import { createServerErrorMessage } from "../functions";
import Page from "./Abstract/Page";
import { CreateNode } from "./Helper/CreateNode";

export default class TariffSelectionPage extends Page {
    constructor(container) {
        super(container);

        // данные
        this.data = {};
        // почта
        this.email = '';
        // тело модального окна
        this.modalBody = null;
        // инпут "ознакомлен со всем"
        this.allRightsInput = null;
        // кнопка подтверждения
        this.acceptBtn = null;
        // поле ошибки
        this.errorSpan = null;
        // поле заполнения почты
        this.emailInput = null;
        // футер модального окна
        this.modalFooter = null;
        // спиннер
        this.spinner = null;

        this.selectElems = [];
        this.checkCount = 0;
        this.isRequestComplete = false;

        this.rightItems = [
            {
                link: '/terms',
                text: Dict.write('base', 'terms_of_use')
            },
            {
                link: '/privacy',
                text: Dict.write('base', 'personal_data_processing_policy')
            },
            {
                link: '/privacy_accept',
                text: Dict.write('base', 'consent_to_the_processing_of_personal_data')
            },
            {
                link: '/ad_accept',
                text: Dict.write('base', 'consent_to_receive_promotional_mailings')
            },
            {
                link: '/sub_terms',
                text: Dict.write('base', 'consent_to_subscription')
            },
            {
                link: '/agency_contract',
                text: Dict.write('base', 'agency_contract')
            }
        ];
    }
    
    init() {}

    openRightsModal(data) {
        // сохраняем данные от сервера
        this.data = data;
        // создаем экземпляр модального окна
        new ModalWindow({
            modalEl: this.createModal(),
        });
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

        // Боди
        this.modalBody = new CreateNode({
            parent: modalContent,
            class: 'modal-body'
        }).init();

        // Описание
        this.createDesriptionBlock();
        
        // список страниц
        this.createRightList();

        this.createEmailBlock();

        this.createModalFooter(modalContent);

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
            text: Dict.write('base', 'subscription_confirmation')
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

    createDesriptionBlock() {
        const desriptionBlock = new CreateNode({
            parent: this.modalBody,
            class: 'mb-2'
        }).init();

        // Название сообщества
        new CreateNode({
            parent: desriptionBlock,
            tag: 'p',
            text: `${ Dict.write('base', 'community') }: ${ this.data.communityName }`
        }).init();

        // Тариф сообщества
        new CreateNode({
            parent: desriptionBlock,
            tag: 'p',
            text: `${ Dict.write('base', 'tariff_plan') }: ${ this.data.communityTariff }`
        }).init();

        // Сумма тарифа
        new CreateNode({
            parent: desriptionBlock,
            tag: 'p',
            text: `${ Dict.write('base', 'price') }: ${ this.data.communityAmount }₽`
        }).init();
    }

    createAccordionHeader(container) {
        // Header аккордиона
        const accordionHeader = new CreateNode({
            parent: container,
            class: 'accordion-header d-flex align-items-center',
            id: 'headingOne'
        }).init();

        // bootstrap
        const accordionHeaderWrapper1 = new CreateNode({
            parent: accordionHeader,
            class: 'col-11'
        }).init();
        
        // righst wrapper
        const rightsWrapper = new CreateNode({
            parent: accordionHeaderWrapper1,
            class: 'form-check form-check-inline'
        }).init();

        // input
        this.allRightsInput = new CreateNode({
            parent: rightsWrapper,
            tag: 'input',
            class: 'form-check-input',
            id: 'all_rights',
            dataset: {
                'checked': 'false'
            },
            type: 'checkbox'
        }).init();
        
        // при изменении "со всем ознакомлен" меняются и остальные элементы
        this.allRightsInput.onchange = (event) => {
            if (this.allRightsInput.dataset.checked === 'true') {
                this.allRightsInput.checked = false;
                this.allRightsInput.dataset.checked = 'false';
                this.checkSelectedElms('false');
            } else if (this.allRightsInput.dataset.checked === 'false') {
                this.allRightsInput.checked = true;
                this.allRightsInput.dataset.checked = 'true';
                this.checkSelectedElms('true');
            }
        }
       
        // label
        const allRightsLabel = new CreateNode({
            parent: rightsWrapper,
            tag: 'label',
            class: 'form-check-label',
            for: 'all_rights'
        }).init();
        
        allRightsLabel.innerHTML = `${ Dict.write('base', 'agree_text_1') } <span class="text-primary">${ Dict.write('base', 'agree_text_2') }</span>`;

        // bootstrap
        const accordionHeaderWrapper2 = new CreateNode({
            parent: accordionHeader,
            class: 'col-1'
        }).init();

        // кнопка открытия/закрытия аккордиона
        new CreateNode({
            parent: accordionHeaderWrapper2,
            tag: 'button',
            class: 'accordion-button collapsed',
            dataset: {
                'bsToggle': 'collapse',
                'bsTarget': '#accordionOne'
            }
        }).init();
    }

    createRightList() {
        const rightList = new CreateNode({
            parent: this.modalBody,
            class: 'mb-2',
            role: 'tablist'
        }).init();

        this.createAccordionHeader(rightList);

        // accordion body
        const accordionBody = new CreateNode({
            parent: rightList,
            class: 'accordion-collapse collapse mt-2',
            id: 'accordionOne'
        }).init();

        
        this.rightItems.forEach((item) => {
            const termWrapper = new CreateNode({
                parent: accordionBody,
                class: 'form-check mb-1'
            }).init();
            
            const termInput = new CreateNode({
                parent: termWrapper,
                tag: 'input',
                class: 'form-check-input',
                dataset: {
                    'checked': false
                },
                type: 'checkbox'
            }).init();
            
            this.selectElems.push(termInput);

            // при изменении каждого пункта
            termInput.onchange = (event) => {
                if (event.target.dataset.checked == 'false') {
                    termInput.checked = true;
                    event.target.dataset.checked = 'true';
                    this.checkSelectedMain('true');
                } else if (event.target.dataset.checked == 'true') {
                    termInput.checked = false;
                    event.target.dataset.checked = 'false';
                    this.checkSelectedMain('false');
                }
            }
            
            // term link
            new CreateNode({
                parent: termWrapper,
                tag: 'a',
                class: 'btn-link',
                text: item.text,
                href: item.link,
                target: '_blank'
            }).init();
        });
    }

    createEmailBlock() {
        // Email Label
        new CreateNode({
            parent: this.modalBody,
            tag: 'label',
            class: 'form-label',
            text: 'Email*',
            for: 'email',
        }).init();
        
        // Email Input
        this.emailInput = new CreateNode({
            parent: this.modalBody,
            tag: 'input',
            class: 'form-control',
            id: 'email',
            placeholder: 'ivan@moyapochta.ru',
            name: 'email',
            required: true
        }).init();
        
        // Email Error
        this.errorSpan = new CreateNode({
            parent: this.modalBody,
            tag: 'span',
            class: 'error hide'
        }).init();
        
        this.emailInput.oninput = (event) => {
            this.email = event.target.value.trim();
        };
        
        // при нажатии энтер
        this.emailInput.onkeydown = async (event) => {
            // если до этого не отправляли запрос
            if (event.keyCode === 13 && !this.isRequestComplete) {
                // если все права заполнены
                if (this.selectElems.length === this.checkCount) {
                    // если поле имейл не пустое
                    if (this.email.length > 0) {
                        // меняем статус отправления запроса
                        this.isRequestComplete = true;
                        this.errorSpan.classList.add('hide');
                        this.emailInput.classList.remove('error');
                        this.showSpinner();
                        await this.sendData();
                    } else {
                        this.emailInput.classList.add('error');
                        this.showErrorMessage(Dict.write('base', 'empty_email'));
                    }
                } else if (this.selectElems.length !== this.checkCount && this.email.length === 0) {
                    this.emailInput.classList.add('error');
                    this.showErrorMessage(Dict.write('base', 'empty_email'));
                } else if (this.selectElems.length !== this.checkCount && this.email.length > 0) {
                    this.emailInput.classList.remove('error');
                    this.showErrorMessage(Dict.write('base', 'you_need_agree_rules'));
                }
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
            tag: 'a',
            class: 'btn btn-success disabled',
            href: this.data.url,
            text: Dict.write('base', 'pay')
        }).init();

        this.acceptBtn.onclick = async (event) => {
            event.preventDefault();
            if (!this.isRequestComplete) {
                if (this.email.length > 0) {
                    this.isRequestComplete = true;
                    this.errorSpan.classList.add('hide');
                    this.emailInput.classList.remove('error');
                    this.showSpinner();
                    await this.sendData();
                } else {
                    this.emailInput.classList.add('error');
                    this.showErrorMessage(Dict.write('base', 'empty_email'));
                }
            }
        };
    }

    checkSelectedMain(boolean) {
        if (boolean == 'true') {
            this.checkCount += 1;
        }

        if (boolean == 'false') {
            this.checkCount -= 1;
        }

        if (this.selectElems.length === this.checkCount) {
            this.allRightsInput.checked = true;
            this.allRightsInput.dataset.checked = 'true';
            this.acceptBtn.classList.remove('disabled');
        } else {
            this.allRightsInput.checked = false;
            this.allRightsInput.dataset.checked = 'false';
            this.acceptBtn.classList.add('disabled');
        }
    }

    checkSelectedElms(boolean) {
        this.selectElems.forEach((selectEl) => {
            if (boolean == 'true') {
                selectEl.checked = true;
                this.checkCount = this.selectElems.length;
                this.acceptBtn.classList.remove('disabled');
            } else if (boolean == 'false') {
                selectEl.checked = false;
                this.checkCount = 0;
                this.acceptBtn.classList.add('disabled');
            }
            selectEl.dataset.checked = boolean;
        })
    }

    showErrorMessage(text) {
        this.errorSpan.textContent = text;
        this.errorSpan.classList.remove('hide');
    }

    showSpinner() {
        this.spinner = new CreateNode({
            parent: this.modalBody,
            class: 'spinner-border text-primary'
        }).init();

        new CreateNode({
            parent: this.spinner,
            tag: 'span',
            class: 'visually-hidden',
            text: 'Loading...',
        }).init();

        this.modalFooter.prepend(this.spinner);
    }

    async sendData() {
        let params = this.data.url.split('?');
        let uri = params[0];
        let query = window.parseQuery('?' + params[1]);
        query.email = this.email;
        query.communityTariffID = this.data.communityTariffID;

        try {
            const res = await window.axios({
                method: 'post',
                url: uri,
                data: query
            });
            if (res.data.status == 'ok' && res.data.redirect != 'undefined'){
                window.location.href = res.data.redirect;
            }

            return res.data;
        } catch(error) {
            if (error.response.data.errors.email) {
                this.showErrorMessage(error.response.data.errors.email[0]);
            }
            this.isRequestComplete = false;
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        } finally {
            this.spinner.remove();
        }
    }
}
