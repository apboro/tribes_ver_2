import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

export class BaseChart {
    constructor (options) {
        this.id = options.id;
        this.type = options.type;
        this.data = options.data;
        this.options = options.options;

        this.chartInstance = null;

        this.init();
    }

    init() {
        this.chartInstance = new Chart(this.id, {
            type: this.type,
            data: this.data,
            options: this.options,
        });
    }

    update() {
        this.chartInstance.update();
    }

    changeData(labels, datasets) {
        this.chartInstance.data.labels = labels;
        this.chartInstance.data.datasets = datasets;

        this.update();
    }

    removeData() {
        this.chartInstance.data.labels.pop();
        this.chartInstance.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
        this.update();
    }
}
