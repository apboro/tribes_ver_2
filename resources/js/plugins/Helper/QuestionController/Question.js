export class Question {
    constructor(id, question) {
        this.id = id;
        this.container = question;
        this.addedQuestion = this.container.querySelector('span');
        this.questionInput = this.container.querySelector('input');

        this.editOrSaveBtn = null;

        this.checkIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check font-medium-1 pe-none"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
        this.editIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit font-medium-1 pe-none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>`;
    
        this.init();
    }

    init() {
        this.updateQuestionItem();
    }

    updateQuestionItem() {

        //this.questionInput.setAttribute('name', `questions[title][${ this.id }]`);
        this.questionInput.setAttribute('name', `questions[][title]`);
        /*this.container.querySelector('.btn-info') ?
            this.container.querySelector('.btn-info').onclick = (event) => this.editQuestion(event) :
            this.container.querySelector('.btn-success').onclick = (event) => this.saveQuestion(event);*/
        this.container.querySelector('.btn-danger').onclick = () => this.removeQuestion();
    }

    // редактировать вопрос
    editQuestion(event) {
        // сохраняем текущее состояние
        this.editOrSaveBtn = event.target;
        // активирууем поле для редактирования 
        this.addedQuestion.classList.add('hide');
        this.questionInput.setAttribute('type', 'text');
        // заменяем кнопку редактирования на сохранение
        this.changeToSaveBehavior();
    }

    // сохранить изменения вопроса
    saveQuestion(event) {
        // сохраняем текущее состояние
        this.editOrSaveBtn = event.target;
        // аткивируем поле сохраненного вопроса
        this.questionInput.setAttribute('type', 'hidden');
        this.addedQuestion.textContent = this.questionInput.value;
        this.addedQuestion.classList.remove('hide');
        // заменяем кнопку сохранения на редактирование
        this.changeToEditBehavior();
    }

    // удалить вопрос
    removeQuestion() {
        this.container.remove();
        Emitter.emit('RemoveQuestion:id', { id: this.id });
    }

    changeToSaveBehavior() {
        this.editOrSaveBtn.classList.remove('btn-info');
        this.editOrSaveBtn.classList.add('btn-success');
        this.editOrSaveBtn.innerHTML = this.checkIcon;

        this.editOrSaveBtn.onclick = (event) => {
            this.saveQuestion(event);
        }
    }

    changeToEditBehavior() {
        this.editOrSaveBtn.classList.add('btn-info');
        this.editOrSaveBtn.classList.remove('btn-success');
        this.editOrSaveBtn.innerHTML = this.editIcon;

        this.editOrSaveBtn.onclick = (event) => {
            this.editQuestion(event)
        }
    }
}
