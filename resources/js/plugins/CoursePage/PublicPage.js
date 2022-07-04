import {EditorState} from "prosemirror-state"
import {EditorView} from "prosemirror-view"
import {Schema, DOMParser, CodeSpec} from "prosemirror-model"
import {schema} from "prosemirror-schema-basic"
import {addListNodes, wrapInList, splitListItem, liftListItem} from "prosemirror-schema-list"
import {exampleSetup} from "prosemirror-example-setup"
import {undo, redo, history} from "prosemirror-history"
import {keymap} from "prosemirror-keymap"
import {baseKeymap, toggleMark, setBlockType, wrapIn} from "prosemirror-commands"
import { Transform } from "prosemirror-transform";  
import { Plugin } from "prosemirror-state"

export class PublicPage {
    constructor(options) {
        this.container = options.container;

        console.log('hi');
        this.init();
    }

    init() {
        class MenuView {
            constructor(items, editorView) {
                this.items = items;
                this.editorView = editorView;

                this.dom = document.createElement('div');
                this.dom.className = 'content-editor__list';
                items.forEach(({ dom }) => this.dom.append(dom));
                this.update();

                this.dom.addEventListener('mousedown', (event) => {
                    event.preventDefault();
                    editorView.focus();
                    items.forEach(({ command, dom }) => {
                        if (dom.contains(event.target)) {
                            command(editorView.state, editorView.dispatch, editorView);
                        }
                    });
                });
            }

            update() {
                this.items.forEach(({ command, dom }) => {
                    let active = command(this.editorView.state, null, this.editorView);
                    //dom.style.display = active ? '' : 'none';
                });
            }

            destroy() {
                this.dom.remove();
            }
        }

        function menuPlugin(items) {
            return new Plugin({
                view(editorView) {
                    let menuView = new MenuView(items, editorView);
                    editorView.dom.parentNode.insertBefore(menuView.dom, editorView.dom);
                    return menuView;
                }
            });
        }

        function icon(text, name) {
            let span = document.createElement('span');
            span.className = `content-editor__item menuicon ${ name }`;
            span.title = name;
            //span.textContent = text;

            let i = document.createElement('i');
            i.className = `content-editor__item-icon content-editor__item-icon--${ name }`;
            span.append(i);
            return span;
        }

        function heading(level) {
            return {
                command: setBlockType(mySchema2.nodes.heading, { level }),
                dom: icon(`H${ level }`, `h${ level }`)
            }
        }
        
        const mySchema = new Schema({
            nodes: addListNodes(schema.spec.nodes, "paragraph block*", "block"),
            marks: schema.spec.marks
        })

        const mySchema2 = new Schema({
            nodes: {
                doc: {
                    content: 'block+'
                },
                
                text: {
                    group: 'inline',
                    inline: true
                },
                
                paragraph: {
                    content: 'inline*',
                    group: 'block',
                    marks: '_',
                    parseDOM: [{ tag: 'p' }],
                    toDOM(node) {
                        return ['p', 0]
                    }
                },

                heading: {
                    content: "inline*",
                    group: 'block',
                    attrs: { level: { default: 1 } },
                    maxLevel: 3,
                    parseDOM: [
                        { tag: 'h1', attrs: { level: 1 } },
                        { tag: 'h2', attrs: { level: 2 } },
                        { tag: 'h3', attrs: { level: 3 } },
                    ],
                    toDOM(node) {
                        return [`h${ node.attrs.level }`, 0]
                    },
                },

                code_block: {
                    content: 'text*',
                    //group:  'block',
                    marks: '',
                    parseDOM: [{ tag: 'pre' }],
                    toDOM(node) {
                        return ['pre', 0]
                    },
                },

                bullet_list: {
                    content: 'list_item+',
                    group: 'block',
                    parseDOM: [{ tag: 'ul' }],
                    toDOM(node) {
                        return ['ul', 0]
                    },
                },

                ordered_list: {
                    content: 'list_item+',
                    group: 'block',
                    parseDOM: [{ tag: 'ol' }],
                    toDOM(node) {
                        return ['ol', 0]
                    },
                },

                list_item: {
                    content: 'block*',
                    //group: 'block',
                    parseDOM: [{ tag: 'li' }],
                    toDOM(node) {
                        console.log(node);
                        return ['li', 0]
                    },
                }
            },

            marks: {
                strong: {
                    parseDOM: [
                        { tag: "strong" },
                        { tag: 'b' },
                        { style: 'font-weight=bold' },
                    ],
                    toDOM(node) { return ["strong", 0] },
                },

                em: {
                    parseDOM: [
                        { tag: "em" },
                        { tag: 'i' },
                        { style: 'font-style=italic' },
                    ],
                    toDOM(node) { return ["em", 0] },
                },

                code: {
                    parseDOM: [{ tag: "code" }],
                    toDOM(node) { return ["code", 0] },
                },

                strike: {
                    parseDOM: [{ tag: "s" }],
                    toDOM(node) { return ["s", 0] },
                }
            }
            //marks: schema.spec.marks
        });

        console.log(mySchema2);

        console.log(mySchema);
        
        let menu = menuPlugin([
            { command: toggleMark(mySchema2.marks.strong), dom: icon('B', 'strong') },
            { command: toggleMark(mySchema2.marks.em), dom: icon('i', 'italic') },
            { command: toggleMark(mySchema2.marks.code), dom: icon('code', 'code-block') },
            { command: toggleMark(mySchema2.marks.strike), dom: icon('code', 'strike') },
            { command: wrapInList(mySchema2.nodes.bullet_list), dom: icon('ul', 'bullet-list') },
            { command: wrapInList(mySchema2.nodes.ordered_list), dom: icon('ol', 'ordered-list') },
            { command: setBlockType(mySchema2.nodes.paragraph), dom: icon('p', 'paragraph') },
            heading(1),
            heading(2),
            heading(3),
            //{ command: wrapIn(mySchema.nodes.blockquote), dom: icon('>', 'blockquote') }
        ]);

        
        

        
        let state = EditorState.create({
            doc: DOMParser.fromSchema(mySchema2).parse(document.querySelector("#content")),
            plugins: [history(), keymap({"Mod-z": undo, "Mod-y": redo}), keymap(baseKeymap), menu]
        });
        
        window.view = new EditorView(document.querySelector("#editor"), { state });

        /*let myPlugin = new Plugin({
            props: {
                handleKeyDown(view, event) {
                console.log("A key was pressed!")
                return false // We did not handle this
                }
            }
        });*/
    }
}


