import Page from './Abstract/Page';
import {Popup} from './Abstract/Popup';
import {CreateNode} from './Helper/CreateNode';
import axios from "axios";

export default class KnowledgeList extends Page {
    constructor(container) {
        super(container);

        this.questions = this.container.querySelectorAll('[data-id]');
        this.popupCat = null;
        this.category_id = null;
    }

    init() {

    }

    showModal(event, community_id) {
        switch (event) {
            case 'add':
                this.popupCat = new Popup({
                    header: this.createModalHeader(),
                    content: this.createModalContent('Название категории'),
                    footer: this.createModalFooter('Сохранить', 'add', community_id),
                    title: 'Добавить категорию',
                });
                break;
            case 'edit':
                if (this.category_id == null) {
                    alert('Сначала нужно выбрать категорию.')
                    return;
                }
                this.popupCat = new Popup({
                    header: this.createModalHeader(),
                    content: this.createModalContent('Новое название категории'),
                    footer: this.createModalFooter('Сохранить', 'edit', community_id),
                    title: 'Переименовать категорию',
                });
                break;
            case 'del':
                if (this.category_id == null) {
                    alert('Сначала нужно выбрать категорию.')
                    return;
                }
                this.popupCat = new Popup({
                    header: this.createModalHeader(),
                    footer: this.createModalFooter('Удалить', 'del', community_id),
                    title: 'Удалить категорию',
                });
                break;
        }
    }

    createModalContent(name) {
        const content = new CreateNode({}).init();

        new CreateNode({
            parent: content,
            tag: 'label',
            text: name
        }).init();

        new CreateNode({
            parent: content,
            id: 'cat_title',
            name: 'cat_title',
            tag: 'input',
        }).init();

        return content;
    }

    createModalFooter(btn_name, command, community_id) {
        const footer = new CreateNode({}).init();

        this.sendCatBtn = new CreateNode({
            parent: footer,
            tag: 'button',
            text: btn_name
        }).init();
        this.sendCatBtn.onclick = () => {
            let cat_name = (command === 'del') ? null : document.getElementById("cat_title").value
            this.processCategory(cat_name, command, community_id);
        }

        return footer;
    }

    createModalHeader() {
        const header = new CreateNode({}).init();

        new CreateNode({
            parent: header,
            tag: 'p',
            text: 'Сохранить'
        }).init();

        new CreateNode({
            parent: header,
            tag: 'h2',
            class: 'popup__title',
            text: 'Добавление категории'
        }).init();

        this.closeBtn = new CreateNode({
            parent: header,
            tag: 'button',
            class: 'popup__close-btn',
            text: 'Закрыть'
        }).init();

        this.closeBtn.onclick = () => this.hide();

        return header;
    }

    toggleAnswerVisibility(index) {
        this.questions.forEach((question) => {
            if (question.dataset.id == index) {
                question.classList.toggle('active');
            }
        });
    }

    filterByCategory(id) {
        this.category_id = id;
        this.questions.forEach((question) => {
            question.classList.add('hide')
            if (question.dataset.category === id) {
                question.classList.remove('hide');
            }
        });
    }

    hideAnswerVisibility(index) {
        this.questions.forEach((question) => {
            if (question.dataset.id == index) {
                question.classList.remove('active');
            }
        });
    }

    processCategory(title, command, community_id) {
        axios.post('/community/' + community_id + '/knowledge/process_category', {
            title: title,
            command: command,
            category_id: this.category_id
        }).then(() => {
            location.reload()
            return false;
        })
    }

    openKnowledgeForm() {
        console.log(this.category_id)
        if (this.category_id != null) {
            document.getElementsByClassName('knowledge-list__new_knowledge')[0].classList.toggle('active')
        } else {
            alert('Сначала нужно выбрать категорию.')
        }
    }

    processKnowledge(command, community_id, question_id) {
        let vopros = document.getElementById("vopros").value;
        let otvet = document.getElementById("otvet").value
        if (command === 'del') {
            if (confirm("Удалить вопрос-ответ?")) {
                axios.post('/community/' + community_id + '/knowledge/process_knowledge', {
                    command: command,
                    vopros: vopros,
                    otvet: otvet,
                    category_id: this.category_id,
                    question_id: question_id
                })
                    .then(() => {
                        location.reload();
                        return false;
                    })
            }
                } else {
            axios.post('/community/' + community_id + '/knowledge/process_knowledge', {
                command: command,
                vopros: vopros,
                otvet: otvet,
                category_id: this.category_id,
                question_id: question_id
            })
                .then(() => {
                    location.reload();
                    return false;
                })
        }
    }

    inputLengthCheck() {
        let voprosVal = document.getElementById("vopros").value;
        let otvetVal = document.getElementById("otvet").value;
        let saveButton = document.querySelector('.knowledge-list__new_knowledge__buttons-save');

        if ((voprosVal !== '') && (otvetVal !== '')) {
            saveButton.removeAttribute("disabled");
        } else {
            saveButton.setAttribute("disabled", "disabled");
        }
    }

    inputClearer() {
        if (window.matchMedia('(max-width: 767px)').matches) {
            let searchField = document.querySelector('.search-field__field').value;
            let searchClear = document.querySelector('.search-field__clear');

            if (searchField !== '') {
                searchClear.style.display = 'inline'
            } else {
                searchClear.style.display = 'none'
            }
        }
    }

    inputClear() {
        if (window.matchMedia('(max-width: 767px)').matches) {
            let searchFields = document.querySelector('.search-field__field');
            let searchClears = document.querySelector('.search-field__clear');
            searchFields.value = '';
            searchClears.style.display = 'none';
        }
    }

    showCategory() {
        let categoryMenu = document.querySelector('.p-2_left');
        if ( categoryMenu.classList.contains("mobile-hidden") ) {
            categoryMenu.classList.remove("mobile-hidden")
        } else {
            categoryMenu.classList.add("mobile-hidden")
        }
    }

    search() {
        this.questions.forEach((question) => {
            question.classList.add('hide')
            if (question.textContent.includes(document.getElementById('search_field').value)) {
            question.classList.remove('hide')
            }
        });
    }

    editQuestion(id){
        //console.log(document.getElementById("save_question_button"))//.classList.toggle('active')
        this.questions.forEach((question) => {
            // console.log(question.dataset.id)
            question.classList.add('hide')
            if (question.dataset.id == id) {
                // console.log(question.dataset.id)
                document.getElementsByClassName('knowledge-list__new_knowledge')[1].classList.toggle('active')
                question.classList.remove('hide');
                document.getElementById("save_question_button").removeAttribute("disabled");
            }
        });
    }
}
