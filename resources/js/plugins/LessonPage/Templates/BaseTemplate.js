export class BaseTemplate {
    constructor(id) {
        // id задается при инициализации
        this.id = id;
        // положение экземпляра класса в массиве всех шаблонов
        this._index = null;

        // node шаблона
        this.template = null;
        // подключенные контроллеры
        this.controllers = [];

        this.init();
    }

    init() {
        // создаение тела шаблона
        this.createTemplate();
        // подключение контроллеров
        this.initControllers();
    }

    createTemplate() {}

    initControllers() {}

    addController(controller) {
        // добавляем контроллер в список контроллеров
        this.controllers.push(controller);
    }

    // возвращаем данные из этого шаблона
    getData() {
        // проверяем все контроллеры
        this.controllers.forEach((controller) => {
            // получаем из каждого контроллера данные где ключ - тип контроллера, значение - данные из контроллера
            const controllerData = controller.getData();
            // запись в данные по типу контроллера данных из контроллера
            this.dataId[controllerData.key] = controllerData.value;
        });
        return this.data;
    }

    // получаем данные в этот шаблон
    setData(data) {
        // даются данные массив объектов где ключ тип контроллера, значение данные контроллера
        this.controllers.forEach((controller) => {
            Object.entries(data).forEach(([key, value]) => {
                // если тип существующего контроллера совпадает с переданным типом
                if (controller.type == key) {
                    // передаем данные в контроллер
                    controller.setData(value);
                }
            });
        });
    }

    get index() {
        return this._index;
    }

    set index(id) {
        this._index = id;
    }

    get data() {
        return this._data;
    }

    get dataId() {
        return this._data[this.id];
    }

    set data(data) {
        return this._data = data;
    }
}
