import Croppr from "croppr";
import { CreateNode } from "../Helper/CreateNode";

export class LoadImage {
    constructor(options) {
        this.parent = options.parent;
        this.file = options.file;
        this.node = options.node;
        this.course_id = options.parent.course_id;
        this.croppr = null;
        this.img = null;
        
        this.fileInput = options.fileInput;

        this.isBlock = options.isBlock ?? true;
        this.init();
    }

    init() {
        // создаем экземпляр модального окна
        this.modal = new ModalWindow({
            modalEl: this.createModal(),
            onShowCallback: () => {
                this.initImage();

            },
            onHideCallback: () => { this.fileInput.value = '' }
        });
    }

    initImage() {
        // создаем файл изображения
        this.img = new Image();
        this.img.src = this.node.result;

        // когда файл изображения загружен
        this.img.onload = () => {
            this.croppr ? this.croppr.destroy() : null;
            
            const wrapper = new CreateNode({
                parent: this.modalBody,
            }).init();

            this.image = new CreateNode({
                parent: wrapper,
                tag: 'img',
                id: `croppr_${ this.parent.course_id }_${ this.parent.id }`,
                class: 'w-100',
                src: this.img.src,
            }).init();
            this.image.style.width = "100%";
            this.initCroppr();
        };
    }

    initCroppr() {
        // создаем экземпляр кроппра
        this.croppr = new Croppr(`#croppr_${ this.parent.course_id }_${ this.parent.id }`, {
            startSize: [100, 100, '%'],
            minSize: [30, 30, '%'],
        
            // при инициализации
            onInitialize: (instance) => {
                // задаем экземпляру путь от файла изображения
                instance.setImage(this.img.src);
                // инициализируем событие обновления кропп данных 
                setTimeout(() => {
                    this.emitCropData();
                }, 10);
            },
        
            onCropEnd: (data) => {
                // инициализируем событие обновления кропп данных 
                setTimeout(() => {
                    this.emitCropData();
                }, 10);
            },
        });
    }

    emitCropData() {
        this.cropDataInput.value = JSON.stringify({
            isCrop: true,
            cropData: this.getCropData(),
        })
    }

    getCropData() {
        let data = this.croppr.getValue();
        return data.x + '|' + data.y + '|' + data.width + '|' + data.height;
    }

    // создаем наполнение для модального окна
    createModal() {
        // обертки
        const modalContainer = new CreateNode({
            class: `modal fade text-start modal-dark`
        }).init();

        const modalWrapper = new CreateNode({
            parent: modalContainer,
            class: 'modal-dialog modal-dialog-centered',
        }).init();
        
        const modalContent = new CreateNode({
            parent: modalWrapper,
            class: 'modal-content'
        }).init();

        // хэдер
        this.createModalHeader(modalContent);
        // тело
        this.createModalBody(modalContent);
        // футер
        this.createModalFooter(modalContent);

        return modalContainer;
    }

    createModalHeader(container) {
        const modalHeader = new CreateNode({
            parent: container,
            class: 'modal-header'
        }).init();
        // заголовок
        new CreateNode({
            parent: modalHeader,
            tag: 'h5',
            class: 'modal-title',
            text: 'Подготовка изображения'
        }).init();
        // кнопка закрытия
        new CreateNode({
            parent: modalHeader,
            tag: 'button',
            class: 'btn-close',
            dataset: {
                bsDismiss: 'modal'
            }
        }).init();
    }

    createModalBody(container) {
        this.modalBody = new CreateNode({
            parent: container,
            class: 'modal-body'
        }).init();

        this.cropDataInput = new CreateNode({
            parent: this.modalBody,
            tag: 'input',
            type: 'hidden',
        }).init();
    }

    createModalFooter(container) {
        const modalFooter = new CreateNode({
            parent: container,
            class: 'modal-footer'
        }).init();

        const saveBtn = new CreateNode({
            parent: modalFooter,
            tag: 'button',
            class: 'btn btn-success',
            text: 'Save'
        }).init();

        saveBtn.onclick = () => { this.onSaveBtn() };
    }

    onSaveBtn() {
        this.saveImage();
        this.modal.hide();
    }
    
    async saveImage() {
        try {
            const formData = new FormData();
            formData.append("file", this.fileInput.files[0]);
            formData.append("course_id", this.course_id);
            formData.append("crop", this.cropDataInput.value);

            const res = await axios({
                method: 'post',
                url: '/api/file/upload',
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            });

            this.addSavedImage(res.data);

            if (!this.isBlock) {
                console.log(res.data);
                console.log(this.parent.parent.parent.attachments);
                this.parent.parent.parent.attachments.push(res.data);
                console.log(this.parent.parent.parent.attachments);

                this.parent.data = res.data.id;
                this.parent.img.src = res.data.url;
            }
        } catch (error) {
            console.log(error);
        }
    }

    addSavedImage(data) {
        let container = null;
        if (this.isBlock) {
            container = new CreateNode({
                parent: this.parent.filesList,
                class: 'preview',
            }).init();
        } else {
            console.log(this.parent.parent.parent.fileController);
            container = new CreateNode({
                parent: this.parent.parent.parent.fileController.filesList,
                class: 'preview',
            }).init();
        }
        
        new CreateNode({
            parent: container,
            tag: 'img',
            id: data.id,
            class: 'w-100 preview-img',
            src: data.url
        }).init();
    }
}
