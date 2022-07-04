import { CreateNode } from "../../Helper/CreateNode";
import { LoadImage } from "../LoadImage";
import Module from "./Module";

export class Image extends Module {
    template() {
        // определяем путь к картинке
        let src = this.getSrc();
        // создаем блок изображения с путем
        this.createImageBlock(src);
        this.listeners();

        return this.node;
    }
    
    listeners() {
        // ловим событие дроп на изображении в шаблоне
        Emitter.subscribe(`drop_${ this.course_id }_${ this.id }`, (data) => {
            this.data = Number(data.id);
        });
    }

    getSrc() {
        let src = null;
        // если есть данные (переданы с сервера)
        if (this.data) {
            // берем путь к нужной картинке для загрузки в изображения в шаблоне
            this.parent.parent.attachments.forEach((item) => {
                if (item.id == this.data) {
                    src = item.url;
                }
            });
        } else {
            // иначе затычка
            src = '/images/no-image.svg'
        }
        
        return src;
    }

    createImageBlock(src) {
        const container = new CreateNode({
            
        }).init();

        this.img = new CreateNode({
            parent: container,
            tag: 'img',
            class: 'w-100 template-img',
            src
        }).init();

        const loadFileInput = new CreateNode({
            parent: container,
            tag: 'input',
            id: `load_${ this.course_id }_${ this.id }`,
            type: 'file',
            class: 'hide'
        }).init();

        loadFileInput.onchange = (event) => { this.onChangeLoadFileInput(loadFileInput) };
        
        const label = new CreateNode({
            parent: container,
            tag: 'label',
            class: 'template-img',
            for: `load_${ this.course_id }_${ this.id }`,
            dataset: {
                containerId: this.id
            }
        }).init();

        container.style.position = 'relative';

        label.style.position = 'absolute';
        label.style.top = 0;
        label.style.right = 0;
        label.style.bottom = 0;
        label.style.left = 0;

        this.node = container;
    }

    onChangeLoadFileInput(loadFileInput) {
        // при загрузке файла изображения присваиваем уроку статус "не сохранен"
        this.parent.lessonNeedSave();
        this.file = new FileReader();
        this.file.readAsDataURL(loadFileInput.files[0]);
        
        this.file.onload = (event) => {
            // выполняем загрузку изображения
            this.image = new LoadImage({
                parent: this,
                file: this.file,
                node: event.target,
                fileInput: loadFileInput,
                isBlock: false
            });
        };
    }
}