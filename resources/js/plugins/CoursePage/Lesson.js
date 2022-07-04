import { CreateNode } from "../Helper/CreateNode";
import { Image } from "./Modules/Image";
import Text from "./Modules/Text";
import Video from "./Modules/Video";
import {log10} from "chart.js/helpers";

export default class Lesson {
    constructor(id, parent) {
        this.meta = {
            title: 'lesson 1'
        };
        // конструкторы модулей
        this.assoc = {
            'text' : Text,
            'video' : Video,
            'image' : Image, 
        }
        
        this.parent = parent;
        // блок вывода модулей текущего урока
        this.container = null;
        // ид урока
        this.id = id;
        this.tabindex = this.id !== 0 ? this.id : Math.floor(Math.random() * 99999999);
        // шаблоны из бд
        this.modulesSource = parent.modulesSource;
        // данные шаблонов
        this.modulesData = [];
        // кнопка добавления блока в случае их отсутствия
        this.addFirstBlock = null;
        // новый это урок или загруженный с сервера
        this.isNew = id === 0;
        // сохранены ли изменения этого урока
        this.saved = true;
        // загружен ли урок
        this.loaded = false;
        // статус сохранения
        this.isSaving = false;
        // индекс перетаскиваемого модуля
        this.activeDragModuleId = null;
        // индекс модуля в который хотим встваить перетаскиваемый
        this.currentDropModuleId = null;        
        // все данные о модулях (id, template_id, modules, rendered)
        this.templates = {};

        this.activeDragElement = null;
        this.init();
    }

    async init() {
        this.container = new CreateNode({
            id: `lesson_${ this.tabindex }`,
        }).init();
        if (!this.isNew){
            await this.load(this.id);
        } else {
            this.addFirstBlock = this.createAddTemplateBlock(this.container, 0);
        }

        this.listeners();    
    }

    listeners() {
        // события drag&drop
        this.container.addEventListener('dragstart', (event) => { this.onDragStart(event) });
        this.container.addEventListener('dragend', (event) => { this.onDragEnd(event) });
        this.container.addEventListener('dragover', (event) => { this.onDragOver(event) });
        this.container.addEventListener('drop', () => { this.onDrop() });
    }

    getContainer(){
        return this.container;
    }

    // загрузка урока по ид
    async load(id) {
        try {
            const res = await axios({
                method: 'post',
                url: '/api/lesson/edit',
                headers: { 'accept' : 'application/json'},
                data: { id }
            });
            res.data = res.data.data;
            // записываем мета данные
            this.meta = res.data.lesson_meta;
            this.id = res.data.id;
            this.isNew = false;
            // записываем данные шаблонов
            this.modulesData = res.data.templates;
            // отрисовываем шаблоны
            this.modulesData.forEach((data, index) => {
                // console.log(this.modulesSource)
                let template = this.findTemplateById(data.template_id);
                this.drawTemplate(template, data, index + 1, true);
            });

            this.lessonLoaded();
            this.lessonSaved();
        } catch (error) {
            console.log(error);
        }
    }

    drawTemplate(template, data, idx, isLoading = false) {
        // если до начала отрисовки имелся элемент, добавляющий первый модуль - удаляем его
        if (this.addFirstBlock) {
            this.addFirstBlock.remove();
            this.addFirstBlock = null;
        }
        
        // если данные получены с сервера - добавляем их по порядку переданного индекса (порядковый номер)
        if (isLoading) {
            // переводим данные для использования в классе
            this.templates[idx] = data;
        } else {
            // создаем объект который заменит templates
            let newTemplates = {};
            // создаем элемент который будет добавлен в templates
            let newTemplate = {
                id: 0
            };
            // если на момент добавления templates пуст (были удалены все модули)
            if (!Object.keys(this.templates).length) {
                newTemplate.template_id = data.template_id;
                newTemplates[data.index] = newTemplate;
            } else {
                Object.entries(this.templates).forEach(([key, item], index) => {
                    if (Number(key) >= idx) {
                        newTemplates[Number(key) + 1] = item;
                    } else if (Number(key) < idx) {
                        newTemplates[key] = item;
                    }
                    newTemplate.template_id = data.template_id;
                    newTemplates[data.index] = newTemplate;
                });
            }
            this.templates = newTemplates;
        }
        
        this.parseTags(template, data, idx, isLoading);
    }

    parseTags(template, data, idx, isLoading = false) {
        let raw = template.html;
        let record = false;
        let rendered = raw;

        let modules = [];

        let templateData = null;
        // если были загружены данные с сервера

        // templateData = this.findTemplateDataById(data.id);

        if (isLoading) {
            // используем для вывода данные сервера
            templateData = this.findTemplateDataById(data.id);
        } else {
            // иначе используем данные шаблонов
            // templateData = this.findTemplateById(template.template_id);
            this.lessonNeedSave();
        }
       
        let moduleHTML = raw;
        let tag = '';
        
        for (let i = 0; i < raw.length; i++) {
            if (raw.charAt(i) === '[' && raw.charAt(i + 1) === '[' ) {
                record = true;
            }
            if (raw.charAt(i) === ']' && raw.charAt(i + 1) === ']' ) {
                record = false;
                
                let moduleData = tag.split('_');

                let d = null;

                if (templateData !== null && templateData[tag]) {
                    d = templateData[tag];
                }
                
                module = new this.assoc[moduleData[0]](d, data, this);
                
                module.setTag(tag);
                
                // добавление модулей
                this.addModule(idx, module);

                modules.push(module);
                
                const cont = new CreateNode({
                    id: tag
                }).init();
                
                moduleHTML = moduleHTML.replace('[[' + tag + ']]', cont.outerHTML);
                tag = '';
            }
            if (record) {
                tag += raw.charAt(i + 1).replace('[', '').replace(']', '');
            }
        }
        
        this.createLessonNode(moduleHTML, data, modules, idx);
    }

    addModule(id, module) {
        // если в модулях уже существует перечень подмодулей
        Object.entries(this.templates).forEach(([key, item]) => {
            if (key == id) {
                if (item.modules) {
                    item.modules.push(module);
                } else if (!item.modules) {
                    item.modules = [module];
                }
            }
        });
    }

    createLessonNode(moduleHTML, data, modules, id) {
        let templateContainer = null;
        // если было добавление не в конец массива
        if (id <= Object.values(this.templates).length - 1) {
            this.clearContainer();
            // создаем обертку
            templateContainer = new CreateNode({
                id,
                class: 'module-container',
                dataset: {
                    'id': data.id
                },
            }).init();
            // создаем наполнение обертки
            this.createNodeItem(moduleHTML, modules, templateContainer);

            // добавляем по id остальным меняем id node элементов
            Object.entries(this.templates).forEach(([key, item]) => {
                if (key == id) {
                    item.rendered = templateContainer;
                } else {
                    item.rendered.id = key;
                }
            });
            
            // отрисовываем
            this.drawTemplates();
        } else {
            // создаем обертку
            templateContainer = new CreateNode({
                parent: this.container,
                id,
                class: 'module-container',
                dataset: {
                    'id': data.id
                },
            }).init();
            // создаем наполнение обертки
            this.createNodeItem(moduleHTML, modules, templateContainer);
            // добавляем по id
            Object.entries(this.templates).forEach(([key, item]) => {
                if (key == id) {
                    templateContainer.id = id; 
                    item.rendered = templateContainer;
                }
            });
        }
    }

    createNodeItem(moduleHTML, modules, container) {
        // добавляем кнопку удаления текущего шаблона
        this.createRemoveTemplateBtn(container);
        // добавляем секцию блоков шаблона
        let section = this.createSection(moduleHTML, modules);
        container.append(section);
        // добавляем блок добавления нового шаблона 
        this.createAddTemplateBlock(container);
    }

    createSection(moduleHTML, modules, id = null) {
        // создание блока с модлуями шаблона
        const temp = new CreateNode({
            html: moduleHTML
        }).init();
        
        let section = temp.querySelector('section');
        section.id = id ? id : Object.values(this.templates).length + 1;
        section.draggable = true;

        modules.forEach((module) => {
            section.querySelector('#' + module.tag).append(module.template());
        });

        return section;
    }

    createAddTemplateBlock(container, id = null) {
        const addTemplateButtContainer = new CreateNode({
            parent: container,
            class: 'addTemplateContainer',
        }).init();
        
        const addTemplateButt = new CreateNode({
            parent: addTemplateButtContainer,
            tag: 'button',
            class: '',
            html: `Добавить`,
        }).init();
        
        // добавление нового блока (берем ид из родителя)
        addTemplateButt.onclick = () => {
            this.parent.templatesController.open(this.id, id ?? addTemplateButtContainer.parentNode.id);
        };
        
        // если был передан id возвращаем блок (нужно когда не осталось блоков)
        if (id != null) {
            return addTemplateButtContainer;
        }
    }

    createRemoveTemplateBtn(container) {
        const removeTmpltBtn = new CreateNode({
            parent: container,
            tag: 'button',
            class: 'remove-module-btn',
            text: '×',
            title: 'Удалить'
        }).init();

        removeTmpltBtn.onclick = (event) => { this.onRemoveBtn(event) };
    }

    onRemoveBtn(event) {
        // очищаем контейнер
        this.clearContainer();
        // ищем ид шаблона (порядковый) по ид родителя
        Object.entries(this.templates).forEach(([index, item]) => {
            // если находим - удаляем его
            if (index == event.target.parentNode.id) {
                delete this.templates[index];
            }
            // если этот элемент находился в объекте дальше искомого
            // делаем индекс отрендеренного элемента меньше на 1
            else if (index > event.target.parentNode.id) {
                item.rendered.id = Number(item.rendered.id) - 1; 
            }
        });
        // делаем перерасчет индексов порядковых в объекте, через создание нового
        const newTemplates = {};
        Object.entries(this.templates).forEach(([key, item], index) => {
            newTemplates[index + 1] = item;
        });
        this.templates = newTemplates;
        // отрисовываем список модули после удаления
        this.drawTemplates();
        // если после удаления не осталось блоков - добавляем кнопку Добавить
        if (this.contentIsEmpty()) {
            this.addFirstBlock = this.createAddTemplateBlock(this.container, 0);
        }
        // переводим урок в состояние - "не сохранен"
        this.lessonNeedSave();
    }

    drawTemplates() {
        Object.values(this.templates).forEach((template) => {
            this.container.append(template.rendered);
        });
    }

    contentIsEmpty() {
        return Object.values(this.templates).length == 0 ? true : false;
    }

    clearContainer() {
        this.container.innerHTML = '';
    }

    clearTemplates() {
        this.clearContainer();
        this.modulesData = [];
        this.templates = {}; 
    }

    findTemplateById(id) {
        return this.parent.templatesController.templates.find((elem) => {
            return elem.template_id === id;
        })
    }

    findTemplateDataById(id) {
        return this.modulesData.find((elem) => {
            return elem.id === id;
        })
    }

    getData() {
        const req = {
            status: 'ok',
            message: '',
            id: this.isNew ? 0 : this.id,
            course_id: this.parent.id,
            lesson_meta: this.meta,
            modules: []
        };
        
        Object.values(this.templates).forEach((template) => {
            let templateData = {
                id: template.id,
                template_id: template.template_id,
            }

            template.modules.forEach((module) => {
                if (module.data) {
                    templateData[module.tag] = module.data;
                } else {
                    req.status = 'error';
                    req.message = 'Zapolnite template';
                    //templateData[module.tag] = 'empty';
                }
            });
            req.modules.push(templateData);
        });

        return req;
    }

    onDragStart(event) {
        if (event.target.classList.contains('baseTemplate')) {
            this.activeDragElement = event.target
            event.target.classList.add('draggable');
            
            Object.values(this.templates).forEach((template) => {
                template.rendered.querySelector('section').classList.add('drag-mode');
            });

        }
    }

    onDragEnd(event) {
        if (event.target.classList.contains('baseTemplate')) {
            this.activeDragElement = null
            Object.values(this.templates).forEach((template) => {
                template.rendered.querySelector('section').classList.remove('drag-mode');
            });
            
            event.target.classList.remove('draggable');
        }
    }

    onDragOver(event) {
        event.preventDefault();
        if (this.activeDragElement) {
            // перетаскиваемый элемент
            const activeElement = this.container.querySelector('.draggable');
            // элемент на которым находится перетаскиваемый элемент
            const currentElement = event.target;
            
            // если это элемент имеющий класс тех элементов которые могут замениться, и если это не один и тот же елемент то замена возможна
            const isMoveable = activeElement.parentNode.id != currentElement.parentNode.id && currentElement.classList.contains(`baseTemplate`);
    
            if (!isMoveable) {
                return;
            }
    
            // элемент перед которым будет вставлен перетаскиваемы элемент
            let nextElement = null;
            if (currentElement.parentNode === activeElement.parentNode.nextElementSibling) {
                nextElement = currentElement.parentNode.nextElementSibling;
            } else {
                nextElement = currentElement.parentNode;
            }
    
            // сохраняем индексы перетаскиваемого элемента и элемента вместо которго хотим вставить
            this.activeDragModuleId = Number(activeElement.parentNode.id);
            this.currentDropModuleId = Number(currentElement.parentNode.id);
    
            // Вставляем activeElement перед nextElement
            this.container.insertBefore(activeElement.parentNode, nextElement);
        }
    }

    onDrop() {
        if (this.activeDragElement) {
            // если отсутствует индекс для элемента куда хотим вставить - выходим
            if (!this.currentDropModuleId) {
                return; 
            }         
            // проверить произошла ли после драга перестановка элементов
            const isIdenticalList = Object.values(this.templates).some((module, index) => this.container.children[index].id != module.rendered.id);
            
            if (isIdenticalList) {
                // обновляем объект экземпляров модулей после перемещения
                let newModules = {};
                Object.entries(this.templates).forEach(([key, template]) => {
                    const moduleId = Number(key);
                    // если индекс перетаскиваемого элемента больше индекса элемента вместо которого мы хотим вставить
                    if (this.activeDragModuleId < this.currentDropModuleId) {
                        // если рассматриваемый индекс равен индексу перетаскиваемого элемента
                        // то в новом объекте под индексом элемента в который вставляем будет перетаскиваемы модуль
                        if (moduleId == this.activeDragModuleId) {
                            newModules[this.currentDropModuleId] = template;
                            template.rendered.id = this.currentDropModuleId;
                        } 
                        // если рассматриваемый индекс больше индекса перетаскиваемого элемента
                        // и меньше или равен индексу элемента куда хотим вставить
                        // то в новом объекте такие элементы сместятся на 1 назад
                        else if (moduleId > this.activeDragModuleId && moduleId <= this.currentDropModuleId) {
                            newModules[moduleId - 1] = template;
                            template.rendered.id = moduleId - 1;
                        }
                        // если рассматриваемый индекс меньше перетаскиваемого и больше индекса куда хотим вставить
                        // то в новом объекте они будут представлены без изменнений
                        else if (moduleId < this.activeDragModuleId || moduleId > this.currentDropModuleId) {
                            newModules[moduleId] = template;
                            template.rendered.id = moduleId;
                        }
                    } // если индекс перетаскиваемого элемента меньше индекса элемента вместо которого мы хотим вставить
                    // то за исключением первого условия нужно отзеркалить условия
                    else if (this.activeDragModuleId > this.currentDropModuleId) {
                        if (moduleId == this.activeDragModuleId) {
                            newModules[this.currentDropModuleId] = template;
                            template.rendered.id = this.currentDropModuleId;
                        } else if (moduleId >= this.currentDropModuleId && moduleId < this.activeDragModuleId) {
                            newModules[moduleId + 1] = template;
                            template.rendered.id = moduleId + 1;
                        } else if (moduleId > this.activeDragModuleId || moduleId < this.currentDropModuleId) {
                            newModules[moduleId] = template;
                            template.rendered.id = moduleId;
                        }
                    }
                    
                });
                this.templates = newModules;
            }
        }

        this.lessonNeedSave();
        
        // очистить индексы
        this.activeDragModuleId, this.currentDropModuleId = null;
    }

    lessonSaved() {
        this.saved = true;
        this.isSaving = false;
    }

    lessonNeedSave() {
        this.saved = false;
    }

    lessonSaving() {
        this.isSaving = true;
    }

    lessonLoaded() {
        this.loaded = true;
    }

    lessonNotLoaded() {
        this.loaded = false;
    }

    lessonIsLoaded() {
        return this.loaded;
    }
};
