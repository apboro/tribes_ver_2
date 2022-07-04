import { VisibilityPassword } from "../Helper/VisibilityPassword/VisibilityPassword";

export class ChangePasswordPage {
    constructor(container) {
        this.container = container;

        this.init();
    }

    init() {
        this.passwordField = new VisibilityPassword(this.container.querySelector('#password_container'));
        this.confirmPasswordField = new VisibilityPassword(this.container.querySelector('#confirmation_password_container'));
    }
}
