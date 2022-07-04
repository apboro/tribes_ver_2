import { resizeVideo } from "../../functions";
import { CreateNode } from "../Helper/CreateNode";
import { LoadImage } from "./LoadImage";
import { LoadVideo } from "./LoadVideo";

export class FileController {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container;

        this.file = null;
        this.course_id = parent.id;

        this.filesList = this.container.querySelector('#files_list');
        // перетаскиваемы элемент
        this.activeDragElement = null;
        // куда перетаскиваем элемент
        this.current = null;
        this.init();
    }

    init() {
        if(this.parent.course.id){
            this.parent.attachments.forEach((item) => {
                if (item.isImage) {
                    this.drawImgPreview(item);                    
                } else if (item.isVideo) {
                    this.drawVideoPreview(item);
                }
            });
        }

        this.listeners();
    }

    drawImgPreview(obj) {
        const container = new CreateNode({
            parent: this.filesList,
            class: 'preview',
        }).init();
        
        const img = new CreateNode({
            parent: container,
            tag: 'img',
            id: obj.id,
            class: 'w-100 preview-img',
            src: obj.url
        }).init();

        img.draggable = true;
    }

    drawVideoPreview(obj) {
        const container = new CreateNode({
            parent: this.filesList,
            class: 'preview',
        }).init();

        container.style.position = 'relative';

        const icon = new CreateNode({
            parent: container,
            tag: 'button',
            id: obj.id,
            class: 'preview-video',
            text: 'V'
        }).init();

        icon.style.position = 'absolute';
        icon.style.top = 0;
        icon.style.left = 0;
        icon.draggable = true


        let src = obj.url;
        let frame = obj.remoteFrame;
        const newFrame = resizeVideo(frame, src, 120, 120);
        container.append(newFrame);
    }

    listeners() {
        // события drag&drop
        this.container.addEventListener('dragstart', (event) => { this.onDragStart(event) });
        this.container.addEventListener('dragend', (event) => { this.onDragEnd(event) });
        this.container.addEventListener('dragover', (event) => { this.onDragOver(event) });
        this.container.addEventListener('drop', () => { this.onDrop() });
    }

    onDragStart(event) {
        // если перетаскиваем нужный объект
        if (event.target.classList.contains('preview-img')) {
            this.activeDragElement = event.target;
        
            this.container.querySelectorAll('.template-img').forEach((img) => {
                img.classList.add('drag-img');
            });
        } else if (event.target.classList.contains('preview-video')) {
            this.activeDragElement = event.target;

            this.container.querySelectorAll('.template-video').forEach((img) => {
                img.classList.add('drag-img');
            });

            this.container.querySelectorAll('iframe').forEach((iframe) => {
                iframe.style.pointerEvents = 'none';
            });
        }
    }

    onDragEnd(event) {
        if (event.target.classList.contains('preview-img')) {
            
            this.activeDragElement = null;
            this.current = null;

            this.container.querySelectorAll('.template-img').forEach((img) => {
                img.classList.remove('drag-img');
            });
        } else if (event.target.classList.contains('preview-video')) {
            this.activeDragElement = null;
            this.current = null;

            this.container.querySelectorAll('.template-video').forEach((img) => {
                img.classList.remove('drag-img');
            });
        }
    }

    onDragOver(event) {
        if (this.activeDragElement) {
            // элемент на которым находится перетаскиваемый элемент
            const currentElement = event.target;
            // если это элемент имеющий класс тех элементов которые могут замениться
            const isMoveable = currentElement.classList.contains(`template-img`);
            
            if (!isMoveable) {
                return;
            }
            this.current = currentElement;
        } else if (this.activeDragElement) {
            // элемент на которым находится перетаскиваемый элемент
            const currentElement = event.target;
            // если это элемент имеющий класс тех элементов которые могут замениться
            const isMoveable = currentElement.classList.contains(`template-video`);
            console.log(isMoveable);
            if (!isMoveable) {
                return;
            }
            this.current = currentElement;
        }
    }

    onDrop() {
        if (this.activeDragElement && this.current) {
            this.current.parentNode.querySelector('img').src = this.activeDragElement.src;
            // инициируем событие дропа и передаем ид дропнутого файла
            Emitter.emit(`drop_${ this.course_id }_${ this.current.dataset.containerId }`, {
                id: this.activeDragElement.id
            });
        } else if (this.activeDragElement && this.current) {
            // this.current.parentNode.querySelector('img').src = this.activeDragElement.src;
            // инициируем событие дропа и передаем ид дропнутого файла
            Emitter.emit(`drop_${ this.course_id }_${ this.current.dataset.containerId }`, {
                id: this.activeDragElement.id
            });
        }
        this.activeDragElement = null;
            this.current = null;
    }

    load(el) {
        const fileType = el.files[0].type.split('/');
        const type = fileType[0];
        const extension = fileType[1];
        const size = el.files[0].size;
        const uploadDate = Date(el.files[0].lastModified);

        this.file = new FileReader();
        this.file.readAsDataURL(el.files[0]);

        // когда файл загружен
        this.file.onload = (event) => {
            if (type === 'image') {
                this.image = new LoadImage({
                    parent: this,
                    file: this.file,
                    node: event.target,
                    fileInput: el
                });
            } else {
                this.video = new LoadVideo({
                    parent: this,
                    file: this.file,
                    node: event.target,
                    fileInput: el
                });
            }
        };


        console.log(Date(el.files[0].lastModified));
    }
}
