import Quill from "quill";
import { debounce } from "../../functions";

export class ContentEditor {
    constructor(parent, containerSelector, event) {
        this.quill = null;
        this.container = parent.querySelector(containerSelector);
        this.editor = this.container.querySelector('[data-editor]');
        this.event = event; 

        this.init();
    }

    init() {
        this.initQuill();
        this.getHTML();
        this.initListeners();
    }

    // инициализируем редактор
    initQuill() {
        this.quill = new Quill(this.editor, {
            theme: 'snow',
            
            scrollingContainer: '.ql-editor-tariff',
            modules: {
                toolbar: "#toolbar",
            },
        });
    }

    // инициализируем слушатели
    initListeners() {
        this.quill.on('text-change', () => {
            debounce(() => {
                this.getHTML();
            }, 500)()
        });

        this.editor.addEventListener('drop', (event) => {
            event.preventDefault();
        });
    }

    // передаем данные редактора в виде верстки
    getHTML() {
        Emitter.emit(this.event, {
            html: this.quill.root.innerHTML,
            formId: this.container.closest('form').getAttribute('id'),
        });
    }
}
