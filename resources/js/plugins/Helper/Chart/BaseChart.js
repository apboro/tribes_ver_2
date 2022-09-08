import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

export class BaseChart {
    constructor (options) {
        this.id = options.id;
        this.type = options.type;
        this.data = options.data;
        this.options = options.options;

        this.init();
    }

    init() {
        new Chart(this.id, {
            type: this.type,
            data: this.data,
            options: this.options,
        });
    }    
}
