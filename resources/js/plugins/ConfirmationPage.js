import { toLimitInput } from "../functions";
import Page from "./Abstract/Page";

export default class ConfirmationPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        // Вертска: 1)кнопка отправки смс повторно, 2)поле ввода смс кода
        this.resendCodeBtn = this.container.querySelector('#resend_code_btn');
        this.timer(30);
    }

    onInputConfirmCodeInput(event) {
        // ограничиваем ввод
        toLimitInput(event, 4);
    }

    onClickResendCodeBtn(event) {
        this.timer(30);
    }

    // блокирует кнопку повторной отправки смс на время
    timer(endOfTimer) {
        this.lockResendCodeBtn();
        let seconds = 0;

        const timer = setInterval(() => {
            seconds += 1;
            this.resendCodeBtn.textContent = `Повторить { ${ endOfTimer - seconds } сек }`
            
            if (seconds === endOfTimer) {
                clearInterval(timer);
                this.resendCodeBtn.textContent = 'Отправить sms повторно';
                this.unLockResendCodeBtn();
            }
            
        }, 1000);
    }

    // блокировка кнопки
    lockResendCodeBtn() {
        this.resendCodeBtn.setAttribute('disabled', true);
    }

    // разблокировка кнопки
    unLockResendCodeBtn() {
        this.resendCodeBtn.removeAttribute('disabled');
    }
}
