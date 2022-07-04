import { CreateNode } from "../Helper/CreateNode";

export default class TabController {
    constructor(parent) {
        this.tabs = [];
        this.parent = parent;
        this.activeTab = null;
        this.oldTab = null;
    }

    addTab(tab){
        let t = new Tab(tab, this.tabs.length + 1, this);
        this.tabs.push(t);
        this.active(t.tag);
    }

    activeFirst() {
        if(this.tabs.length){
            this.activeTab = 1;
            this.deactiveAll();
            this.tabs[0].active(true);
        }
  
    }

    activeLast() {
        this.deactiveAll();
        this.tabs[0].active(true);
    }

    active(tag) {
        this.activeTab = this.findTabByTag(tag).index
        this.deactiveAll();
        this.findTabByTag(tag).active(true);
    }

    deactiveAll() {
        this.tabs.forEach((tab) => {
            tab.active(false);
        })
    }

    removeTab(tag) {
        this.tabs.forEach((tab, index) => {
            if (tab.tag === tag) {
                this.removeTabNode(tab, index);
                this.updateTabNodeList(tab);
                this.tabs.splice(index, 1);
            }
        });

        // обновить ид табов
        this.tabs.map((tab, index) => tab.index = index + 1);

        this.parent.removeLesson(Number(tag.split('_')[1]));
        
        console.log(this.tabs);
        /* this.parent.course.lessons.forEach((lesson, index) => {
            console.log(Number(lesson.container.id.split('_')[1])   );
            if (Number(lesson.container.id.split('_')[1]) == Number(tag.split('_')[1])) {
                //lesson.id = index + 1;
                lesson.container.id = `lesson_${ index + 1 }`;
                //lesson.containe
            } else if (lesson.id > Number(tag.split('_')[1])) {
                //lesson.id = index + 1;
                //lesson.container.id = `lesson_${ index + 1 }`;
                
            }   
        });

        this.tabs.forEach((tab, index) => {
            //console.log(tag);
            if (tab.tag.split('_')[1] > Number(tag.split('_')[1])) {
                tab.tag = `lesson_${ index + 1 }`
            }
        });
        console.log(this.tabs); */
    }

    removeTabNode(tab, idx) {
        Object.values(tab.container.children).forEach((item, index) => {
            if (index === idx) {
                item.remove();
            }
        });
    }

    updateTabNodeList(tab) {
        Object.values(tab.container.children).forEach((item, index) => {
            item.querySelectorAll('button')[0].textContent = `Часть ${ index + 1 }`;
        });
    }

    findTabByTag(tag) {
        return this.tabs.filter((tab) => {
            return tab.tag === tag;
        })[0];
    }
}

class Tab {
    constructor(tab, index, parent) {
        this.parent = parent;
        this.container = parent.parent.container.querySelector('#tabNavs');
        this.tab = tab;
        this.state = false;
        this.tag = tab.id;
        this.index = index;

        this.init();
    }

    init() {
        this.createTabList();
    }

    createTabList() {
        const tabButton = new CreateNode({
            parent: this.container,
            tag: 'li',
        }).init();

        const button = new CreateNode({
            parent: tabButton,
            tag: 'button',
            text: `Часть ${ this.index }`
        }).init();

        button.onclick = () => {
            this.onSwitchTab();
        };

        const closeTabButton = new CreateNode({
            parent: tabButton,
            tag: 'button',
            class: 'removeTab',
            text: '×'
        }).init();

        closeTabButton.onclick = () => {
            this.parent.removeTab(this.tag);
        };

        this.button = tabButton;
    }

    async onSwitchTab() {
        // проверяем что клик был совершен не по активному в данный момент табу
        if (this.parent.activeTab != this.index) {
            //this.parent.oldTab = this.parent.activeTab;
            this.parent.active(this.tag);
            // сохраняем не сохраненные уроки
            await this.parent.parent.saveLesson();
            // загружаем урок на который переходим
            //await this.parent.parent.loadLesson(this.tag.split('_')[1]);
        }
    }

    active(status = true) {
        if (status) {
            this.tab.classList.add('active');
            this.button.classList.add('active');
        } else {
            this.tab.classList.remove('active');
            this.button.classList.remove('active');
        }
        this.state = status;
    }
}
