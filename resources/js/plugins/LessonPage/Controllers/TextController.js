import Quill from "quill";
import { CreateNode } from "../../Helper/CreateNode";
import { debounce } from "../../../functions";

export class TextController {
    constructor(container) {
        // тип контроллера, нужен для получения данных с сервера
        this.type = 'text';
        // нод шаблон переданный из шаблона
        this.container = container;
        // контейнер редактора
        this.editorContainer = null;
        // поле редактирования
        this.editor = null;
        // панель форматирования
        this.toolbar = null;
        // экземпляр квилл редактора
        this.quill = null;
        // данные
        this._data = {
            text: '',
        };
        
        this.init();
    }

    init() {
        // создаем нод тело для редактора
        this.initEditor();
        // инициализируем редактор квилл
        this.initQuill();
        // если имеются данные редактора(сохраненные)
        if (this.text) {
            //добавляем в редактор
            this.outputDataToEditor();
        }
        // запускаем слушатели событий
        this.listeners();
    }

    // создание нод тела для редактора
    initEditor() {
        this.editorContainer = new CreateNode({
            parent: this.container,
            id: 'snow-container'
        }).init();

        this.toolbar = new CreateNode({
            parent: this.editorContainer,
            id: 'toolbar'
        }).init();

        this.editor = new CreateNode({
            parent: this.editorContainer,
            class: 'ql-editor-text',
        }).init();
    }

    // иниц-я ред-ра квилл
    initQuill() {
        this.quill = new Quill(this.editor, {
            theme: 'snow',
            scrollingContainer: '.ql-editor-text',
            modules: {
                toolbar: {
                    container: [
                        ['bold', 'italic', 'underline', 'strike'], // жирный, курсив, подчеркнутый, зачеркнутый
                        ['link', 'code'], // ссылка, код
                        ['blockquote'], // цитата
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }], // списки нумерованный, точечный
                        [{ 'script': 'sub'}, { 'script': 'super' }],      // выше/ниже строки
                        //[{ 'size': ['small', false, 'large', 'huge'] }],  // размер текста
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }], // заголовки
                        //[{ 'color': [] }, { 'background': [] }], // цвет шрифта/заливки
                        [{ 'align': [] }], // положение текста                    
                        ['clean'], // очистить форматирование
                        //['image', 'video'],
                    ],
                }
            },
        });
    }

    listeners() {
        // сохраняем контент редактора в ходе взаимодейсвтия с ним
        this.quill.on('editor-change', (eventName, ...args) => {
            debounce(() => {
                this.text = this.quill.root.innerHTML;
            }, 200)()
        });

        // запретить дроп над редактором
        this.editor.addEventListener('drop', (event) => {
            event.preventDefault();
        });
    }

    // вывести данные в редактор
    outputDataToEditor() {
        this.quill.root.innerHTML = this.text;
    }

    // дать данные
    getData() {
        return { 
            key: this.type,
            value: this.text
        };
    }

    // получить данные
    setData(data) {
        this.text = data;
        this.outputDataToEditor();
    }

    get text() {
        return this._data.text;
    }

    set text(value) {
        return this._data.text = value;
    }
}
