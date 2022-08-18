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

    linkSettings() {
        // let Link = Quill.import('formats/link');
        
        // Link.sanitize = function(value) {
        //     console.log(/^https?:/.test(val));
        //     return `https://${ value }`;
        // }
        
        const Link = Quill.import('formats/link');
        const builtInFunc = Link.sanitize;
        Link.sanitize = function customSanitizeLinkInput(linkValueInput) {
            let val = linkValueInput;
            // do nothing, since this implies user already using a custom protocol
            if (/^\w+:/.test(val));
            else if (!/^https?:/.test(val))
                val = "https://" + val;
            return builtInFunc.call(this, val); // retain the built-in logic
        };
    }

    // инициализируем редактор
    initQuill() {
        this.quill = new Quill(this.editor, {
            theme: 'snow',
            
            scrollingContainer: '.ql-editor-tariff',
            modules: {
                toolbar: "#toolbar",
               /*  handlers: {
                    // handlers object will be merged with default handlers object
                    'link': function(value) {
                      if (value) {
                        var href = prompt('Enter the URL');
                        this.quill.format('link', href);
                      } else {
                        this.quill.format('link', false);
                      }
                    }
                  } */
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
        this.linkSettings(); // добавление htpps к ссылке
        
        Emitter.emit(this.event, {
            html: this.quill.root.innerHTML,
            formId: this.container.closest('form').getAttribute('id'),
        });
    }
}
