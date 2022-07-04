import Page from "./Abstract/Page";
import { CommonPage } from "./LessonPage/CommonPage";

export default class LessonPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        if (this.isBlock('[data-tab="CommonPage"]')) {
            this.commonPage = new CommonPage(this);
        }

    }
    
    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }
}
