import { CreateNode } from "../Helper/CreateNode";

export default class TemplateController {
    constructor(parent) {
        this.templates = [];
        this.parent = parent;
        this.ref = 0;
        this.id = null;
        this.container = null;

        this.init();
    }

    async init() {
        //await this.load();
        //await this.parent.getTemplate();
      /*   this.templates = this.parent.modulesSource;
        this.createTemplateList(); */
    }

    open(ref, id) {
        this.id = id;
        if(typeof ref !== 'undefined'){
            this.ref = ref;
        }
        
        this.templatesPanel = new SidePanel({
            title: 'Шаблоны',
            content: this.container 
        });
        // показываем
        this.templatesPanel.show();
    }

    close() {
        this.templatesPanel.hide();
    }

    async load() {
        try {
            const res = await axios.get('/api/lesson/templates');
            this.templates = res.data.data.templates;
            this.createTemplateList();
        } catch (error) {
            console.log(error);
        }
    }

    createTemplateList() {
        const container = new CreateNode({
            class: 'row'
        }).init();

        this.templates.forEach((template) => {
            const t = new CreateNode({
                class: 'col-6 mb-1 border border-dark',
                parent: container,
                text: template.title
            }).init();
            
            const button = new CreateNode({
                parent: t,
                tag: 'button',
                text: 'Выбрать'
            }).init();

            button.addEventListener('click', () => {
                this.parent.templatePicked(template, this.ref, this.id);
            });
        });
        this.container = container;
    }
}
