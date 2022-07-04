import { CreateNode } from "../../Helper/CreateNode";
import { TextController } from "../Controllers/TextController";
import { BaseTemplate } from "./BaseTemplate";

export class Template1 extends BaseTemplate {
    constructor(id) {
        super(id);
        
        this.text = null;
        this._data = {
            [this.id]: {
                text: '',
            }
        };
    }

    createTemplate() {
        this.template = new CreateNode({
            class: 'template-1',
            id: 'template_1'
        }).init();

        this.text = new CreateNode({
            parent: this.template,
            class: 'text-1',
            id: 'text_1',
        }).init();

        this.image = new CreateNode({
            parent: this.template,
            class: 'image-1',
            id: 'image_1',
            text: 'Изображение'
        }).init();
    }

    initControllers() {
        this.addController(new TextController(this.text));
    }
}
