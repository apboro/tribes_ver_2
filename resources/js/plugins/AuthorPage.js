import Page from "./Abstract/Page";
import { ChangePasswordPage } from "./AuthorPage/ChangePasswordPage";
import { MobileConfirmedPage } from "./AuthorPage/MobileConfirmedPage";

export default class AuthorPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        if (this.isBlock('[data-tab="ChangePasswordPage"]')) {
            this.changePasswordPage = new ChangePasswordPage(this.container);
        }
        
        if (this.isBlock('[data-tab="MobileConfirmedPage"]')) {
            this.mobileConfirmedPage = new MobileConfirmedPage(this.container);
        }
    }

    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }
}
