import { createServerErrorMessage, lockBody, unlockBody } from '../../functions';

export class DataUpload {
    constructor(parent, uploadBtn) {
        this.parent = parent;
        this.uploadBtn = uploadBtn;
        this.container = parent.container;
        this.data = null;
        
        this.onSubmit()
    }

    onSubmit() {
        console.log(this);
        this.data = this.parent.data;
        
        // BAD IF
        if (!this.data.chatBot) {
            new Toasts({
                type: 'error',
                message: 'Пожалуйста, выберите нужного чат-бота'
            });
            return false;
        }

        if (!this.data) {
            new Toasts({
                type: 'error',
                message: 'Пожалуйста, убедитесь, что все данные заполнены'
            });
        }
        
        let formData = new FormData();
        formData.append("community_name", this.data.communityName);
        formData.append("community_file", this.data.communityFile);
        formData.append("crop", this.data.isCrop);
        formData.append("crop_data", this.data.cropData);
        console.log(formData.get("community_name"));

        this.upload(formData);
    }

    toggleUploadBtnStatus() {
        this.uploadBtn.classList.toggle('_load');
    }

    async upload(dataObj) {
        lockBody();
        this.toggleUploadBtnStatus();
        try {
            const resp = await window.axios({
                method: 'post',
                url: '/manager/file/store',
                data: dataObj,
            });
            
            const data = await resp.json();
        } catch(error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        } finally {
            unlockBody();
            this.toggleUploadBtnStatus();
        }
    }
}
