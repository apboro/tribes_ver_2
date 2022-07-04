import { TemplateController } from "./Controllers/TemplateController";
import { Template1 } from "./Templates/Template1";
import { Template2 } from "./Templates/Template2";
import { TemplatesPanel } from "./TemplatesPanel";

export class CommonPage  {
    constructor(parent) {
        this.container = parent.container;
        // контейнер для втсавки шаблонных блоков
        this.contentContainer = this.container.querySelector('#content_container');
        
        // Данные для генерации панели шаблонов (иднтф, путь к изображению, событие клика по элементу шаблона)
        this.templates = [
            {
                id: 'template1',
                src: '/images/bot-avatar.jpg',
                event: 'onTemplate1Click',
                class: Template1,
            },
            {
                id: 'template2',
                src: '/images/bot-avatar.jpg',
                event: 'onTemplate2Click',
                class: Template2,
            },
        ];

        // панель шаблонов
        this.templatesPanel = new TemplatesPanel({
            title: 'Шаблоны',
            templates: this.templates
        });
        // контроллер шаблонов
        this.templateController = new TemplateController({
           container: this.contentContainer,
           templates: this.templates
        });
    }

    saveLesson() {
        console.log(this.templateController.getData());
    }
}
