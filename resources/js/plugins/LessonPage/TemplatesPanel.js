import { CreateNode } from "../Helper/CreateNode";

export class TemplatesPanel {
    constructor(options) {
        // заголовок
        this.title = options.title;
        // параметры шаблонов
        this.templates = options.templates;
        // панель шаблонов
        this.templatesPanel = null;
    }

    open() {
        // создаем боковую панель, задаем заголовок и тело панели
        this.templatesPanel = new SidePanel({
            title: this.title,
            content: this.createTempaltesPanel() 
        });
        // показываем
        this.templatesPanel.show();
    }

    createTempaltesPanel() {
        // создаем обертку для панели
        const content = new CreateNode({
            class: 'content',
        }).init();
        // создаем кнопки выбора шаблонов
        this.createTemplateBtns(content);

        return content;
    }

    createTemplateBtns(parent) {
        // перебираем параметры для кадого шаблона
        this.templates.forEach((template) => {
            // обертка выступающая кнопкой
            const templateBtn = new CreateNode({
                parent,
                tag: 'div',
                id: template.id,
                class: 'pointer mb-1'
            }).init();
            // при клике 
            templateBtn.onclick = () => {
                // скрываем панель
                this.templatesPanel.hide();
                // инициализруем событие клика для слушающих
                Emitter.emit(template.event, {
                    id: template.id,
                });
            };
            // изображение
            new CreateNode({
                parent: templateBtn,
                tag: 'img',
                class: 'w-100',
                src: template.src
            }).init();
        });
    }
}
