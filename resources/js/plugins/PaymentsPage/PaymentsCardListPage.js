import { createServerErrorMessage } from "../../functions";
import { EmailEditable } from "./EmailEditable";

export class PaymentsCardListPage {
    constructor(parent) {
        this.container = parent.container.querySelector('[data-tab="PaymentsCardListPage"]');
        // контейнер для отображения банковских карт
        this.cardsContainer = this.container.querySelector('#cards_container');
        // контейнер добавления карты
        this.addCardContainer = this.container.querySelector('#add_card_container');
        // конпка добавления карты
        this.addCardBtn = this.container.querySelector('#add_card_btn');
        // фрэйм на сервис добавления карты
        this.addCardIframe = this.addCardContainer.querySelector('#card_iframe');
        // спиннер загрузки перечня карт
        this.cardsSpinner = this.container.querySelector('#cards_spinner');
        // спиннер загрузки сервиса добавления карты
        this.bankSpinner = this.container.querySelector('#bank_spinner');
        // сообщение об отсутствии подключенных карты
        this.emptyLabel = this.container.querySelector('#empty_label');

        this.apiToken = document.querySelector('[name="api-token"]').content;

        // данные о картах
        this._cards = {};
        // хранилище нод элементов карт
        // ключ - идентификатор карты (берется из данных от банка), значение - нода карты
        this.cardList = {};
        this.oldCardsLength = 0;
        this.maxNumberAttempts = 3;

        this.emailEditableList = {};

        this.init();
    }

    async init() {
        // загружаем данные карт
        await this.getCardsData();
        // скрываем спиннер карт
        this.hideCardsSpinner();
        // сохраняем текующий размер массива данных о картах
        this.saveCardsLength();
        // проверяем получили ли мы какие то карты, если нет сообщаем
        this.checkForCards();
        // выводим карты на сайте
        this.createCardList();
        // слушаем события
        this.listeners();
    }

    listeners() {
        // в добавленных нодах карт слушаем событие клика
        Object.entries(this.cardList).forEach(([ id, card ]) => {
            card.addEventListener('click', async (event) => {
                // если клик по кнопке удаления карты
                if (event.target.id === 'remove_btn') {
                    // удаляем карту
                    await this.removeCard(id);
                    // обновляем список карт
                    await this.updateCardList();
                }
            });
        });
    }

    checkForCards() {
        // если список карт пуст
        if (this.isEmptyCards) {
            // выводим сообщение
            this.showEmptyLabel();
        } else {
            // иначе скрываем
            this.hideEmptyLabel();
        }
    }

    async getCardsData() {
        console.log(this.apiToken);
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
            console.log(error);
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        } finally {
            
        }
    }

    // добавление карты
    async addCard() {
        // блокировка кнопки добавления карты
        this.addCardBtn.disabled = true;
        this.showBankSpinner();
        // получаем ссылку на сервис добавления карты
        const url = await this.addBankCard();
        if (url) {
            this.showAddCardContainer();
            // загружаем по ссылке сервис добваления карты
            this.setCardIframeSrc(url);
        } else {
            // разблокировка кнопки добавления карты
            this.addCardBtn.disabled = false;
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
            this.hideBankSpinner();
        }
    }

    // удаление карты, по идентификатору карты, под которой хранится соответствующая нода карты
    async removeCard(id) {
        try {
            const res = await window.axios({
                method: 'post',
                url: '/api/payment/removeCard',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                }, 
                data: {
                    CardId: id
                }
            });
            
        } catch (error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
    }

    // завершение добавления карты
    async finishAddingCard() {
        // скрываем контейнер для добавления карты
        this.hideAddCardContainer();
        // обновляем список карт
        await this.updateCardList();
        // разблокировка кнопки добавления карты
        this.addCardBtn.disabled = false;
        // очищаем айфрэйм
        this.setCardIframeSrc('');
    }

    // обновляем список карт
    async updateCardList() {
        // счетчик попыток соединения с данными о картах
        let count = 0;
        // очищаем сайт и хранилище от карт
        this.clearCardList();
        // отображаем спиннер загрузки карт
        this.showCardsSpinner();
        
        // проверка данных
        const toCheck = async (count) => {
            // получаем данные о картах
            await this.getCardsData();
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
                this.hideCardsSpinner();
                // проверяем есть ли данные о картах, если нет - предупреждаем
                this.checkForCards();
                // выводим на сайт список карт
                this.createCardList();
                // запускаем слушатели
                this.listeners();
                // сохраняем обновленный размер массива карт
                this.saveCardsLength();
            }
        }

        await toCheck(count);
    }

    // вывести список карт на сайте
    createCardList() {
        Object.values(this.cards).forEach((card) => {
            this.cardsContainer.prepend(this.createCardItem(card));
        });
    }

    // создать ноду карты
    createCardItem(card) {
        const cardEl = document.createElement('div');
        cardEl.className = 'col-12 col-sm-6 col-md-4';
        
        cardEl.innerHTML = `
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-sm-center justify-content-md-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        <h5 class="card-title mb-0 ms-1"> ${ card.Pan }</h5>
                    </div>
                    
                    <div class="mt-1">
                        ${ Dict.write('payments', 'valid_until') }: ${ this.toFormatValidDate(card.ExpDate) }
                    </div>
                </div>

                <div class="card-footer text-center">
                    <button id="remove_btn" class="btn btn-flat-danger waves-effect ${ AdminState.isAdminModeActive ? 'disabled' : '' }">
                        ${ Dict.write('payments', 'exclude') }
                    </button>
                </div>

                <span class="badge badge-glow bg-${ this.getTypeOfCardStatus(card.Status) } badge-up community-badge">
                    ${ this.getCardStatus(card.Status) }
                </span>
            </div>
        `;
        
        this.addToCardList(card.CardId, cardEl);
        return cardEl; 
    }
    
    // добавить идентификатор и ноду в хранилище нод перечня карт
    addToCardList(id, card) {
        this.cardList[id] = card;
    }

    // сохранить текущий размер массива карт
    saveCardsLength() {
        this.oldCardsLength = this.cardsLength;
    }

    // задать путь для загрузки айфрэйм
    setCardIframeSrc(value) {
        this.addCardIframe.src = value;
    }

    // очистить список карт на сайте и в хранилище нод
    clearCardList() {
        Object.values(this.cardList).forEach((card) => {
            card.remove();
        });
        this.cardList = {};
    }
    
    // скрыть спиннер загрузки карт
    hideCardsSpinner() {
        this.cardsSpinner.classList.add('hidden');
    }

    // показать спиннер загрузки карт
    showCardsSpinner() {
        this.cardsSpinner.classList.remove('hidden');
    }

    // скрыть спиннер загрузки банковского сервиса
    hideBankSpinner() {
        this.bankSpinner.classList.add('hidden');
    }

    // показать спиннер загрузки банковского сервиса
    showBankSpinner() {
        this.bankSpinner.classList.remove('hidden');
    }

    // скрыть контейнер банковского сервиса
    hideAddCardContainer() {
        this.addCardContainer.classList.add('hidden');
    }

    // показать контейнер банковского сервиса
    showAddCardContainer() {
        this.addCardContainer.classList.remove('hidden');
    }

    // скрыть собщение об отсутствии
    hideEmptyLabel() {
        this.emptyLabel.classList.add('hidden');
    }

    // показать собщение об отсутствии
    showEmptyLabel() {
        this.emptyLabel.classList.remove('hidden');
    }

    // вернуть человекочитаемы статус карты
    getCardStatus(status) {
        switch (status) {
            case 'A': return Dict.write('payments', 'active');
            case 'I': return Dict.write('payments', 'inactive');
            case 'E': return Dict.write('payments', 'overdue');
        }
    }

    // вернуть подходящий цсс класс для требуемого статуса карты
    getTypeOfCardStatus(status) {
        switch (status) {
            case 'A': return 'success';
            case 'I': return 'secondary';
            case 'E': return 'warning';
        }
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

    set cards(value) {
        this._cards = value;
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

    // по клику на изменить
    onClickEditEmailForm(event) {
        // берем номер карточки
        let cardId = event.target.dataset.cardId;
        // создаем экземпляр объекта редактора почты под этим номером
        this.emailEditableList[cardId] = new EmailEditable(this.container, cardId);
        // вызываем метод показа формы
        this.emailEditableList[cardId].showEmailForm();
    }

    // по клику на закрыть форму
    onClickCloseEmailForm(event) {
        // берем номер карточки
        let cardId = event.target.dataset.cardId;
        // вызываем метод скрытия формы
        this.emailEditableList[cardId].hideEmailForm();
        // удаляем экземпляр объекта редактора почты под этим номером
        delete this.emailEditableList[cardId];
    }
}
