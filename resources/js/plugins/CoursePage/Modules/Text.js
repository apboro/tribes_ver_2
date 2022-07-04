import Quill from "quill";
import { debounce } from "../../../functions";
import { CreateNode } from "../../Helper/CreateNode";
import Module from "./Module";

export default class Text extends Module{
    template() {
        
        /* let input = document.createElement('input');
        input.value = this.data;

        this.node = input;

        return this.node; */
        
        this.createEditorNode();
        this.initQuill();
        
        if (this.data) {
            this.quill.root.innerHTML = this.data;
        }

        // сохраняем контент редактора в ходе взаимодейсвтия с ним
        this.quill.on('text-change', (eventName, ...args) => {
            debounce(() => {   
                this.data = this.quill.root.innerHTML;
            }, 200)()
        });

        // присваиваем уроку статус - не сохранен
        this.quill.on('selection-change', (range, oldRange, source) => {
            this.parent.lessonNeedSave();   
        });

        // запретить дроп над редактором
        this.editor.addEventListener('drop', (event) => {
            event.preventDefault();
        });

        this.node = this.editorContainer;

        return this.node;
    }

    createEditorNode() {
        this.editorContainer = new CreateNode({
            //parent: this.container,
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

    initQuill() {
        this.quill = new Quill(this.editor, {
            theme: 'bubble',
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

    saveData() {
        this.parent.lessonNeedSave();
        this.data = this.quill.root.innerHTML;
    }
}
