import Page from './Abstract/Page';
import { Popup } from './Abstract/Popup';
import { CreateNode } from './Helper/CreateNode';

export default class KnowledgeList extends Page {
    constructor(container) {
        super(container);

        this.questions = this.container.querySelectorAll('[data-id]');
        this.popupQuestion = null;
    }

    init() {
        
    }

    showModal() {
        this.popupQuestion = new Popup({
            content: this.createModalContent(),
            footer: this.createModalFooter()
        });
        
    }

    createModalContent() {
        const content = new CreateNode({}).init();

        new CreateNode({
            parent: content,
            tag: 'label',
            text: 'Ваш вопрос'
        }).init();

        new CreateNode({
            parent: content,
            tag: 'textarea',
        }).init();

        return content;
    }

    createModalFooter() {
        const footer = new CreateNode({}).init();
        
        this.sendQuestionBtn = new CreateNode({
            parent: footer,
            tag: 'button',
            text: 'Send'
        }).init();

        this.sendQuestionBtn.onclick = () => this.popupQuestion.hide();
        
        return footer;
    }

    toggleAnswerVisibility(index) {
        this.questions.forEach((question) => {
            if (question.dataset.id == index) {
                question.classList.toggle('active');
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
}
