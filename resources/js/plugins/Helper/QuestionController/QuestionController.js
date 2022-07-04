import { Question } from "./Question";

export class QuestionController {
    constructor(container) {
        this.container = container.querySelector('#question_controller');
        this.addedQuestions = this.container.querySelector('#questions_container');
        this.newQuestionInput = this.container.querySelector('#new_question');

        this.questions = {};

        this.init();
    }

    init() {
        this.listener();
        this.updateQuestionList();
    }

   listener() {
        Emitter.subscribe('RemoveQuestion:id', (data) => {
            // при удалении вопроса очищаем обхект вопросов
            this.questions = {};
            // и заново его наполняем для верности всех индексов
            this.updateQuestionList();
        });
    }

    // обновление состояния вопросов, офункционаливание
    updateQuestionList() {
        Object.entries(this.addedQuestions.children).forEach(([key, question]) => {
            // добавляем в объект вопросов новый экземпляр класса по индексу
            this.questions[key] = new Question(key, question);
        });
    }

    // добавляем новый вопрос в список
    addQuestion() {
        // добавляем в дом новый вопрос
        //let value = this.newQuestionInput.value;

        let question = document.createElement('div');
        question.className = 'row mb-1';
        question.innerHTML = this.questionEl();
        this.addedQuestions.append(question);

        // обновляем объект вопросов
        this.updateQuestionList();
        // очищаем поле добавления нового вопроса и фокусируемся на нем
        //this.newQuestionInput.value = '';
        //this.newQuestionInput.focus();
    }

    questionEl() {
        return `
            <div class="col-9 col-sm-10">
                <input type="text" class="form-control" placeholder="${ Dict.write('base', 'question_text') }" name="" value="">
            </div>

            <div class="col-3 col-sm-2 text-end">
                <span class="btn btn-danger px-1">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash font-medium-1 pe-none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </span>
            </div>
        `;
    }
}



/*
<div class="col-12 col-sm-9 col-md-8 col-lg-9 col-xl-10">
                <span>${ value }</span>
                <input type="hidden" class="form-control" name="" value="${ value }">
            </div>

            <div class="col-sm-3 col-md-4 col-lg-3 col-xl-2 text-sm-end mt-1 mt-sm-0">
                <span
                    class="btn btn-info px-1"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit font-medium-1 pe-none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </span>

                <span
                    class="btn btn-danger px-1"
                >
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash font-medium-1 pe-none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </span>
            </div>
*/