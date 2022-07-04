import { CreateNode } from "../../Helper/CreateNode";
import { TextController } from "../Controllers/TextController";
import { BaseTemplate } from "./BaseTemplate";

export class Template2 extends BaseTemplate {
    constructor(id) {
        super(id);
        
        this._data = {
            [this.id]: {
                text: '',
            }
        };
    }

    createTemplate() {
        this.template = new CreateNode({
            class: 'template-2 row',
            id: 'template_1'
        }).init();

        this.image = new CreateNode({
            parent: this.template,
            class: 'image-2 col-6',
            id: 'image_2',
            text: 'Изображение template 2'
        }).init();

        this.text = new CreateNode({
            parent: this.template,
            class: 'text-2 col-6',
            id: 'text_2',
            
        }).init();
    }

    initControllers() {
        this.addController(new TextController(this.text));
    }
}
