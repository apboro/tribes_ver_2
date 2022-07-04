import { QuestionController } from "../Helper/QuestionController/QuestionController";

export class KnowledgeBaseAddPage {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="knowledgeBaseAddPage"]');

        this.questionController = new QuestionController(this.container);
    }
}
