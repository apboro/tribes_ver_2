import { createServerErrorMessage } from "../../functions";
import { CreateNode } from "../Helper/CreateNode";

export class OutcomePage {
    constructor(parent) {
        this.container = parent.container;
        
        // спиннер
        this.cardSelectionSpinner = null;
        // тело модального окна
        this.modalBody = null;
        // контейнер вывода сообщений
        this.messageContainer - null;
        // контейнер банка
        this.bankContainer = null;
        // экземпляр модального окна
        this.modal = null;
        // кнопка по которой мы нажали для вывода денег
        this.clickedBtn = null;

        this.apiToken = document.querySelector('[name="api-token"]').content;

        this._cards = {};
        this.spAccumulationId = '';
        this.oldCardsLength = 0;
        this.maxNumberAttempts = 3;
    }

    async showCards(btn) {
        // сохраняем кнопку по которой нажали
        this.clickedBtn = btn;
        // берем данные для вывода денег
        this.spAccumulationId = btn.dataset.spAccumulationId;
        // открываем модальное окно
        this.openRightsModal();
        // загружаем данные
        await this.loadCards();
        // скрываем спиннер
        this.hideSpinner();
        // если карт нет показываем сообщение об отсутствии, либо выводим список
        if (this.isEmptyCards) {
            this.createEmptyMessage();
        } else {
            this.createCardList();
        }
    }

    openRightsModal() {
        // создаем экземпляр модального окна
        this.modal = new ModalWindow({
            modalEl: this.createModal(),
        });
    }

    // создаем наполнение для модального окна
    createModal() {
        // обертки
        const modalContainer = new CreateNode({
            class: `modal fade text-start modal-success`
        }).init();

        const modalWrapper = new CreateNode({
            parent: modalContainer,
            class: 'modal-dialog modal-dialog-centered',
        }).init();
        
        const modalContent = new CreateNode({
            parent: modalWrapper,
            class: 'modal-content'
        }).init();

        // хэдер
        this.createModalHeader(modalContent);
        // тело
        this.createModalBody(modalContent);
        // блок сообщений
        this.createMessageBlock();

        return modalContainer;
    }

    createModalHeader(container) {
        const modalHeader = new CreateNode({
            parent: container,
            class: 'modal-header'
        }).init();
        // заголовок
        new CreateNode({
            parent: modalHeader,
            tag: 'h5',
            class: 'modal-title',
            text: Dict.write('payments', 'withdraw_funds')
        }).init();
        // кнопка закрытия
        new CreateNode({
            parent: modalHeader,
            tag: 'button',
            class: 'btn-close',
            dataset: {
                bsDismiss: 'modal'
            }
        }).init();
    }

    createModalBody(container) {
        this.modalBody = new CreateNode({
            parent: container,
            class: 'modal-body'
        }).init();
        // спиннер
        this.cardSelectionSpinner = new CreateNode({
            parent: this.modalBody,
            class: 'spinner-border text-primary'
        }).init();

        new CreateNode({
            parent: this.cardSelectionSpinner,
            tag: 'span',
            class: 'visually-hidden',
            text: 'Loading...',
        }).init();
    }

    createCardList() {
        const cardListContainer = new CreateNode({
            parent: this.modalBody
        }).init();
        // заголовок
        new CreateNode({
            parent: cardListContainer,
            tag: 'h5',
            text: Dict.write('payments', 'choose_a_card')
        }).init();

        const cardListRow = new CreateNode({
            parent: cardListContainer,
            class: 'row outcome-modal-card-list'
        }).init();


        // список карт
        this.cards.forEach((card) => {
            const cardItemCol = new CreateNode({
                parent: cardListRow,
                class: 'col-12 col-sm-6'
            }).init();

            const cardItemCard = new CreateNode({
                parent: cardItemCol,
                class: 'card pointer'
            }).init();

            const cardItemBody = new CreateNode({
                parent: cardItemCard,
                class: 'card-body'
            }).init();

            const cardHead = new CreateNode({
                parent: cardItemBody,
                class: 'd-flex justify-content-sm-center justify-content-md-start align-items-center'
            }).init();

            const cardIcon = new CreateNode({
                parent: cardHead,
            }).init();

            cardIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>`;

            new CreateNode({
                parent: cardHead,
                tag: 'h4',
                class: 'mb-0 ms-1',
                text: card.Pan
            }).init();

           new CreateNode({
                parent: cardItemBody,
                class: 'mt-1',
                text: `${ Dict.write('payments', 'valid_until') }: ${ this.toFormatValidDate(card.ExpDate) }`
            }).init();

            cardItemCol.onclick = async() => {
                await this.withdraw(card.CardId);
            }
        });
    }

    createMessageBlock() {
        // блок сообщений
        this.messageContainer = new CreateNode({
            parent: this.modalBody
        }).init();
    }

    // сообщение об ошибке
    createErrorMessage(text) {
        this.clearMessageBlock();

        this.errorMessage = new CreateNode({
            parent: this.messageContainer,
            class: 'alert alert-danger mt-1 alert-validation-msg d-flex'
        }).init();

        const errorMessageBody = new CreateNode({
            parent: this.errorMessage,
            class: 'alert-body w-100'
        }).init();

        new CreateNode({
            parent: errorMessageBody,
            tag: 'span',
            text
        }).init();

        const closeBtn = new CreateNode({
            parent: this.errorMessage,
            tag: 'button',
            class: 'btn btn-danger',
            text: Dict.write('base', 'out')
        }).init();

        closeBtn.onclick = () => {
            this.modal.instance.hide();
        };
    }

    // сообщение об успехе
    createSuccessMessage(text) {
        this.clearMessageBlock();

        this.successMessage = new CreateNode({
            parent: this.messageContainer,
            class: 'alert alert-success mt-1 alert-validation-msg d-flex'
        }).init();

        const successMessageBody = new CreateNode({
            parent: this.successMessage,
            class: 'alert-body w-100'
        }).init();

        new CreateNode({
            parent: successMessageBody,
            tag: 'span',
            text
        }).init();
    }

    // соообщение об отсутствии карт
    createEmptyMessage() {
        this.clearMessageBlock();

        this.emptyMessage = new CreateNode({
            parent: this.messageContainer,
            class: 'alert alert-info mt-1 alert-validation-msg d-flex'
        }).init();

        const emptyMessageBody = new CreateNode({
            parent: this.emptyMessage,
            class: 'alert-body w-100'
        }).init();

        new CreateNode({
            parent: emptyMessageBody,
            tag: 'span',
            text: Dict.write('payments', 'no_connected_cards')
        }).init();

        const closeBtn = new CreateNode({
            parent: this.emptyMessage,
            tag: 'button',
            class: 'btn btn-info',
            text: Dict.write('base', 'add')
        }).init();

        closeBtn.onclick = () => {
            this.addCard();
        };
    }

    async addCard() {
        this.clearMessageBlock();
        this.showSpinner();
        const url = await this.addBankCard();
        if (url) {
            this.createBankBlock(url);
        } else {
            this.createEmptyMessage();
        }
    }

    createBankBlock(url) {
        this.bankContainer = new CreateNode({
            parent: this.modalBody,
            class: 'card'
        }).init();

        const bankBody = new CreateNode({
            parent: this.bankContainer,
            class: 'card-body'
        }).init();

        new CreateNode({
            parent: bankBody,
            tag: 'iframe',
            id: 'card_iframe_modal',
            src: url
        }).init();

        const bankFooter = new CreateNode({
            parent: this.bankContainer,
            class: 'card-footer'
        }).init();

        const successBtn = new CreateNode({
            parent: bankFooter,
            tag: 'button',
            class: 'btn btn-success d-flex align-items-center',
            text: Dict.write('base', 'complete')
        }).init();

        successBtn.onclick = () => {
            this.finishAddingCard();
        };
    }

    // завершение добавления карты
    async finishAddingCard() {
        // скрываем контейнер для добавления карты
        this.bankContainer.remove();
        // отображаем спиннер загрузки карт
        this.showSpinner();

        await this.updateCardList();
    }

    // обновляем список карт
    async updateCardList() {
        // счетчик попыток соединения с данными о картах
        let count = 0;
        // проверка данных
        const toCheck = async (count) => {
            console.log('update' + count);
            // получаем данные о картах
            await this.loadCards();
            // фиксируем попытку
            count += 1;
            // если размер массива полученных карт не отличается от предыдущего массива карт
            // или количество попыток загрузки данных меньше 3
            if (this.cardsLength === this.oldCardsLength && count < this.maxNumberAttempts) {
                setTimeout(async () => {
                    // через 2 сек еще раз проверяем данные, на случай, что карта не успела добавиться в базу
                    await toCheck(count);
                }, 2000);
            } else {
                // иначе скрываем спиннер карт
                this.hideSpinner();
                // если карт нет показываем сообщение об отсутствии, либо выводим список
                if (this.isEmptyCards) {
                    this.createEmptyMessage();
                } else {
                    this.createCardList();
                }
                // сохраняем обновленный размер массива карт
                this.saveCardsLength();
            }
        }
        await toCheck(count);
    }

    clearMessageBlock() {
        this.messageContainer.innerHTML = '';
    }

    hideSpinner() {
        this.cardSelectionSpinner.classList.add('hide');
    }

    showSpinner() {
        this.cardSelectionSpinner.classList.remove('hide');
    }

    // сохранить текущий размер массива карт
    saveCardsLength() {
        this.oldCardsLength = this.cardsLength;
    }

    // загрузка карт
    async loadCards() {
        try {
            const res = await window.axios({
                method: 'post',
                url: '/api/payment/cardList',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                }, 
                data: {}
            });
            // записываем данные карт, не забирая карты со статусом удаленные
            if (res.data.cards != null){
                this.cards = Object.values(res.data.cards).filter((card) => card.Status !== 'D');
            } else {
                this.cards = {};
            }
        } catch (error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
    }
    // запрос на снятие средств
    async withdraw(cardId) {
        try {
            const res = await window.axios({
                method: 'post',
                url: '/api/payment/payout',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                },
                data: {
                    CardId: cardId,
                    accumulationId: this.spAccumulationId,
                }
            });

            if (res.data.status === 'error') {
                if (res.data.details) {
                    this.createErrorMessage(`${ res.data.message }. ${ res.data.details }`);
                } else {
                    this.createErrorMessage(res.data.message);
                }
            } else {
                this.createSuccessMessage(res.data.message);
                this.refreshPage();
                //this.clickedBtn.classList.add('disabled');
            }
        } catch (error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
    }

    // делаем запрос на добавление
    async addBankCard() {
        try {
            const res = await window.axios({
                method: 'post',
                url: '/api/payment/addCard',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                }, 
                data: {}
            });

            // если банк вернул с ошибкой
            if (res.data.status == 'error') {
                new Toasts({
                    type: 'error',
                    message: res.data.message
                });
            }

            // возвращаем ссылку на сервис добавления карты
            return res.data.redirect;
        } catch (error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        } finally {
            this.hideSpinner();
        }
    }

    refreshPage() {
        setTimeout(() => {
            location.reload();
        }, 1000);
    }

    // преобразовать вид валидности карты
    toFormatValidDate(date) {
        let arr = [...date];
        arr.splice(2, 0, '/');
        return arr.join('');
    }

    get cards() {
        return this._cards;
    }

    set cards(cards) {
        this._cards = cards;
    }

    get cardsLength() { 
        return this._cards.length;
    }

    get isEmptyCards() {
        if (Object.keys(this._cards).length === 0) {
            return true;
        }
        return false;
    }
}
