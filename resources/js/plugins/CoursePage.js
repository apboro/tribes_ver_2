import Page from "./Abstract/Page";
import { PublicPage } from "./CoursePage/PublicPage";
import Lesson from "./CoursePage/Lesson";
import TabController from "./CoursePage/TabController";
import TemplateController from "./CoursePage/TemplateController";
import { FileController } from "./CoursePage/FileController";

export default class CoursePage extends Page {
    constructor(container) {
        super(container);

        let url = new URL(window.location.href);
        this.exists = url.searchParams.get('id') !== null;

        this.lessonContainer = this.container.querySelector('#lessonContainer');

        this.course = {
            id: url.searchParams.get('id') ?? 0,
            meta: {
                title: 'Название курса'
            },
            lessons: []
        };

        // шаблоны
        this.modulesSource = null;
        //this.files = [];
        this.start();
    }

    async start() {
        this.tabController = new TabController(this);
        this.templatesController = new TemplateController(this);

        // загружаем шаблоны
        await this.templatesController.load();

        await this.getCourse();
        this.fileController = new FileController(this);
    }

    templatePicked(template, lesson_id, node_id) {
        // закрываем панель выбора шаблона
        this.templatesController.close();
        // отрисовываем шаблон либо со следующим ид после которого добавляем, или в конец
        this.findLessonById(lesson_id)
            .drawTemplate(
                template,
                {
                    id: Number(node_id) + 1 ?? this.findLessonById(lesson_id).rendered.length + 1,
                    index: Number(node_id) + 1 ?? this.findLessonById(lesson_id).rendered.length + 1,
                    template_id: template.template_id
                },
                Number(node_id) + 1
            );
    }

    findLessonById(id) {
        return this.course.lessons.find((lesson) => {
            if (lesson.id === id) {
                return lesson;
            }
        });
    }

    async getCourse() {
        try {
            if (this.exists) {
                // получаем данные курса
                const res = await axios({
                    method: 'post',
                    url: '/api/course/edit',
                    headers: { 'accept' : 'application/json' },
                    data: this.course
                });
                console.log(res.data);
                // сохраняем в объекте
                this.id = res.data.course.id;
                this.meta = res.data.course.course_meta;

                this.modulesSource = this.templatesController.templates;
                // если в данных есть уроки

                if (res.data.course.lessons.length) {
                    // создаем уроки
                    res.data.course.lessons.forEach((lesson) => {
                        this.newLesson(lesson.id, false);
                        this.tabController.activeFirst();
                    });
                }
            } else {
                this.newLesson(0, true);
            }
            this.tabController.activeFirst();
        } catch (error) {
            console.log(error);
        }
    }

    async saveCourse() {
        try {
            // собираем данные курса
            const data = this.getData();
            
            // отправляем
            const res = await axios({
                method: 'post',
                url: '/api/course/store',
                data
            });
    
            // затем выполняем сохранение урока
            if (res.data.status === 'ok') {
                this.course.id = res.data.id;
                await this.saveLesson();
            }
        } catch (error) {
            console.log(error);
        }
    }

    async getTemplate() {
        try {
            // получаем шаблоны
            const res = await axios.get('/api/lesson/templates');
            // сохраняем в объекте
            this.modulesSource = res.data.data.templates;
            console.log(this.modulesSource)
        } catch (error) {
            console.log(error);
        }
    }

    removeLesson(id) {
        // находим экземпляр удаляемого урока
        let lesson = this.findLessonById(id);
        // удаляем ноду
        this.lessonContainer.removeChild(lesson.container);
        // очищаем массив экземпляров уроков
        this.course.lessons = this.course.lessons.filter((lesson) => lesson.id !== id);
        // делаем актвинвым первый таб
        this.tabController.activeFirst();
        // делаем запрос к серверу на удаление
        this.deleteLesson(id);
    }

    async deleteLesson(id) {
        try {
            const res = await axios({
                method: 'post',
                url: '/api/lesson/delete',
                data: { id }
            });
        } catch (error) {
            console.log(error);
        }
    }

    async newLesson(id, state = true) {
        // добавляем урок
        let less = new Lesson(id ?? 0, this);
        this.course.lessons.push(less);
        let cnt = less.getContainer();
        this.lessonContainer.append(cnt);
        this.tabController.addTab(cnt);
        // если это добавление нового урока - сохраняем не сохраненные, даем уроку статус - загружен
        if (id) {
            await this.saveLesson();
            less.lessonLoaded();
        }
    }

    async saveLesson() {
        try {
            const lesson = this.lessons.find(lesson => !lesson.saved);
            // если есть урок со статусом "не сохранен" и он не находится в данный момент в статусе "сохраняется"
            if (lesson && !lesson.isSaving) {
                // присваиваем статус "сохраняется"
                lesson.lessonSaving();
                // берем данные урока
                const data = lesson.getData();
                if (data.status === 'ok') {
                    // делаем запрос на сохранение
                    const res = await axios({
                        method: 'post',
                        url: '/api/lesson/store',
                        data
                    });
                    // присваиваем статус "сохранен" и убираем статус "сохранеяется"
                    lesson.lessonSaved();
                    // присваиваем статус "не загружен"
                    //lesson.lessonNotLoaded();
                    // очищаем контейнер урока, и данные о шаблонах
                    lesson.clearTemplates();
                    // lesson.id = res.data.id;
                    // выполняем загрузку "обновленного урока" по новому ид, присвоенному на сервере               
                    lesson.load(res.data.lesson.id);
                } else if (data.status === 'error') {
                    console.log(data.message);
                }
            }
        } catch (error) {
            console.log(error);
        }
    }

    async loadLesson(id) {
        console.log(id);
        try {
            const lesson = this.lessons.find((lesson) => lesson.id == id && !lesson.loaded);
            // если есть урок со статусом "Не загружен" и он не находится в данный момент в статусе "сохраняется"
            if (lesson && !lesson.isSaving) {
                // выполняем запрос на нужный ид
                await lesson.load(id);
            }
        } catch (error) {
            console.log(error);
        }
    }

    draw() {
        this.course.lessons.forEach((lesson) => {
            this.getMediaContent();
        })
    }

    getData() {
        const data = {
            id: this.id,
            lesson_meta: this.meta,
            lessons: []
        };

        this.lessons.forEach((lesson) => {
            if (lesson.isNew) {
                data.lessons.push({ id: 0 });
            } else {
                data.lessons.push({ id: lesson.id });
            }
        });

        return data;
    }
    
    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }

    get id() {
        return this.course.id;
    }

    set id(value) {
        this.course.id = value;
    }

    get lessons() {
        return this.course.lessons;
    }

    get meta() {
        return this.course.meta;
    }

    set meta(value) {
        this.course.meta = value;
    }

    get attachments() {
        return this.meta.attachments ?? [];
    }
}
