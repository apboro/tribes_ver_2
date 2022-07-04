import Page from "./Abstract/Page";
import { initPhoneMask } from "../functions";
import { VisibilityPassword } from "./Helper/VisibilityPassword/VisibilityPassword";

export default class ResetPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        //initPhoneMask(this.container);
        this.passwordField = new VisibilityPassword(this.container.querySelector('#password_container'));
        this.confirmPasswordField = new VisibilityPassword(this.container.querySelector('#confirm_password_container'));
    }
}
