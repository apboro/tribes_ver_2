export class BaseChart {
    constructor (options) {
        // Верстка
        this.parent = options.parent;
        this.chartWrapper = this.parent.querySelector(options.chartWrapperId);
        this.chartEx = this.chartWrapper.querySelector('.chartjs');
        this.chartWrapper.style.height = `${ this.chartEx.dataset.height }px`;
        
        // блок описания диаграммы
        this.chartDescription = null;
        // цвет(а) лейблов
        this.labelBackground = null;

        // экземпляр диаграммы
        this.chartInstance = null;
        // данные для вывода
        this.data = options.data;

        // база цветов
        this.chartColors = [
            '#28dac6',
            '#f7ab74',
            '#f7db74',
            '#8ef774',
            '#74f799',
            '#74a9f7',
            '#f774d2',
            '#d4f774',
            '#f7749c',
            '#74e8f7',
            '#f77d74',
            '#c574f7',
            '#f77474'
        ];

        // минифицированя версия (без шкал, с маленькими точками)
        this.mini = options.mini ?? false;
        // закрасить область под линией
        this.fill = options.fill ?? false;
        // выводить информацию о валюте
        this.currency = options.currency ?? false;
        // цвета для линий
        this.lineColors = options.lineColors;

        this.init();
    }

    // Базовая инициализация: -параметры, -создание диаграммы
    // Дополнительно: -блок описания диаграммы
    init() {
        this.initParams();
        this.initChart();
    }

    // параметры
    initParams() {
        // Вычисление нужного(ых) цвета(ов) для диаграммы(описания)
        this.updateLabelBackground = this.getLabelsBackground();
    }

    // создание диаграммы
    initChart() {}

    // блок описания диаграммы
    initChartDescription() {
        Object.keys(this.data).forEach((key, index) => {
            this.addDescriptionElement(key, this.data[key], this.labelBackground[index]);
        });
    }

    // Добавление верстки описывающей каждое значение данных
    addDescriptionElement() {}

    // обновление данных для диаграммы
    // базвовое: -обновление данных, -обновление экземпляра диаграммы
    // дополнительно: -обновление описания диаграммы
    updateChart() {}

    // обновление описания диаграммы
    updateChartDescription() {
        this.chartDescription.innerHTML = '';
        this.updateLabelBackground = this.getLabelsBackground();
        this.initChartDescription();
    }

    // Определение цвета / набора цветов
    getLabelsBackground() {}

    // Обновление экземпляра диаграммы: -данные, -набор цветов, -лейблы
    updateChartInstance() {
        this.updateChartInstanceData = this.dataValues;
        this.updateChartInstanceBackround = this.labelBackground;
        this.updateChartInstanceLabels = this.dataLabels;
        this.chartInstance.update();
    }

    // labels
    get dataLabels() {
        return Object.keys(this.data);
    }

    // values
    get dataValues() {
        return Object.values(this.data);
    }

    set updateChartInstanceData(data) {
        this.chartInstance.data.datasets[0].data = data;
    }

    set updateChartInstanceBackround(background) {
        this.chartInstance.data.datasets[0].backgroundColor = background;
    }

    set updateChartInstanceLabels(labels) {
        this.chartInstance.data.labels = labels;
    }

    set changeData(data) {
        this.data = data;
    }

    set updateLabelBackground(background) {
        this.labelBackground = background;
    }
}
