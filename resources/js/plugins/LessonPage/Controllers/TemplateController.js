import { CreateNode } from "../../Helper/CreateNode";

export class TemplateController {
    constructor(options) {
        this.container = options.container;
        // Данные для генерации панели шаблонов (иднтф, путь к изображению, событие клика по элементу шаблона)
        this.templates = options.templates;

        this._data = null;

        this.templateInstances = []

        this.init();
    }

    init() {
        const data = [
            {
                'template1': {
                    'text': '<h2 class="ql-align-center">Title</h2><p><br></p><p>this text</p><ul><li>list item</li><li>list item</li></ul>',
                    'img': 'sadasdas'
                }
            },
            {
                'template1': {
                    'text': '<h1 class="ql-align-center">Title</h1><p><br></p><p>this text</p><ul><li>list item</li><li>list item</li></ul>',
                    'img': 'sadasdas'
                },
            },
            {
                'template2': {
                    'text': '<ul><li>list item shmitem</li><li>list item</li></ul>',
                    'img': 'sadasdas'
                },
            }
        ];
        this.data = data;

        // если пришли сохраненные данные
        if (this.data) {
            // инициализируем шаблоны
            this.initTemplates(); 
        }

        this.listeners();
    }

    // загружаем сохраненные данные
    async loadData() {
        try {
            
        } catch (error) {
            
        }
    }

    initTemplates() {
        this.data.forEach((template) => {
            const id = Object.keys(template)[0];
            const templateData = Object.values(template)[0];
            this.addTemplate(id, templateData);
        });
    }

    listeners() {
        // ожидаем добавления блока из панели выбора шаблона
        this.templates.forEach((template) => {
            Emitter.subscribe(template.event, (data) => {
                // добавляем шаблон
                this.addTemplate(data.id);
            });    
        });
    }

    addTemplate(id, data = null) {
        // создаем обертку над вставляемым шаблоном
        const templateWrapper = new CreateNode({
            parent: this.container,
            class: 'card'
        }).init({});
        templateWrapper.style.position = 'relative';
        
        let templateInstance = {};
        
        this.templates.forEach((template) => {
            // находим по ид нужный конструктор класса
            if (template.id === id) {
                // создаем экземпляр класса шаблона
                templateInstance = new template.class(id);
            }
        });

        // если имеются сохраненные данные
        if (data) {
            // добавляем их в шаблон
            templateInstance.setData(data);
        }
        
        // добавляем шаблон 
        this.addTemplateItem(templateInstance, templateWrapper)
        // создаем кнопку удаления шаблона
        this.createCloseBtn(templateInstance, templateWrapper);
    }

    addTemplateItem(templateInstance, templateWrapper) {
        // добавляем в массив экземпляров шаблонов
        this.templateInstances.push(templateInstance);
        // добавлем нод элемент шаблона из объекта
        templateWrapper.append(templateInstance.template);
        // присваиваем экземпляру номер (последний на данный момент)
        templateInstance.index = this.templateInstances.length - 1;
    }

    createCloseBtn(templateInstance, templateWrapper) {
        // создаем кнопку удаления шаблона
        const closeBtn = new CreateNode({
            parent: templateWrapper,
            tag: 'span',
            class: 'btn btn-danger pointer',
            text: 'X'
        }).init();
        closeBtn.style.position = 'absolute';
        closeBtn.style.right = 0;

        closeBtn.onclick = () => {
            this.removeTemplate(templateWrapper, templateInstance);
        };
    }

    refreshInstances() {
        // заменяем индексы в экземплярах на порядковый номер в массиве
        this.templateInstances.forEach((instance, index) => {
            instance.index = index;
        });
    }

    // удаляем шаблон
    removeTemplate(templateWrapper, templateInstance) {
        // удаляем из массива шаблонов экземпляр класса шаблона
        this.templateInstances.splice(templateInstance.index, 1);
        // удаляем обертку шаблона вместе с шаблоном
        templateWrapper.remove();
        // обновляем индексы экземпляров шаблонов
        this.refreshInstances();
    }

    // получить данные всех шаблонов
    getData() {
        let templatesData = [];
        this.templateInstances.forEach((templateInstance) => {
            templatesData.push(templateInstance.getData());
        });
        return templatesData;
    }

    get data() {
        return this._data;
    }

    set data(data) {
        this._data = data;
    }
}
