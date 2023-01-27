import Page from "./Abstract/Page";

export default class TariffConfirmation extends Page {
    constructor(container) {
        super(container);        
    }
    
    init() {
        this.rightsContainer = this.container.querySelector('#confirm-check-rights');
        this.rightsInputs = this.rightsContainer.querySelectorAll('input');
        this.allRightsInput = this.container.querySelector('#all_rights_check');
        this.submitBtn = this.container.querySelector('#submit_btn');
        this.emailMessage = this.container.querySelector('#email_message');
        
        this.isAllRightsChecked = false;
        //super.init();
        
        console.log(this);
        this.resetRights();
        this.listeners();
    }
    
    listeners() {
        this.rightsInputs.forEach((input) => {
            input.addEventListener('change', (event) => {
                if (this.isAllChecked()) {
                    this.allRightsInput.checked = true;
                    this.isAllRightsChecked = true;
                    this.submitBtn.classList.remove('button-filled--disabled');
                } else {
                    this.allRightsInput.checked = false;
                    this.isAllRightsChecked = false;
                    this.submitBtn.classList.add('button-filled--disabled');
                }
            });
        });
    }

    resetRights() {
        this.allRightsInput.checked = false;
        this.rightsInputs.forEach((el) => {
            el.checked = false;
        });
    }

    isAllChecked() {
        let state = true;
        this.rightsInputs.forEach((el) => {
            if (el.checked == false) {
                state = false;
            }
        });
        return state;
    }

    checkAllRights() {
        this.rightsInputs.forEach((el) => {
            el.checked = this.isAllRightsChecked ? false : true;
        });
        this.isAllRightsChecked = !this.isAllRightsChecked;
        if (this.isAllRightsChecked) {
            this.submitBtn.classList.remove('button-filled--disabled');
        } else {
            this.submitBtn.classList.add('button-filled--disabled');
        }
    }

    async openRightsModal(data) {
        this.data = data;
        this.email = this.container.querySelector('#email').value
        await this.sendData();
    }

    async sendData() {
        let params = this.data.url.split('?');
        let uri = params[0];
        let query = window.parseQuery('?' + params[1]);
        query.email = this.email;
        query.communityTariffID = this.data.communityTariffID;

        try {
            const res = await window.axios({
                method: 'post',
                url: uri,
                data: query
            });
            if (res.data.success == 'false'){
                document.getElementById('error_msg').innerHTML = res.data.message
            }
            if (res.data.status == 'ok' && res.data.redirect != 'undefined'){
                window.location.href = res.data.redirect;
            }


            return res.data;
        } catch(error) {
            if (error.response.data.errors.email) {
                this.showErrorMessage(error.response.data.errors.email[0]);
            }
        } finally {
            // this.spinner.remove();
        }
    }

    showErrorMessage(text) {
        this.emailMessage.classList.remove('hide')
        this.emailMessage.textContent = text;
    }
}
