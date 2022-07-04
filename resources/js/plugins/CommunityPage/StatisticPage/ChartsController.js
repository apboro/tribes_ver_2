import { BarChart } from "../../Helper/Chart/BarChart";
import { DoughnutChart } from "../../Helper/Chart/DoughnutChart";
import { LineChart } from "../../Helper/Chart/LineChart";

export class ChartsController {
    constructor(parent) {
        this.container = parent.container;
        this._data = {};

        this.tariffAmountCountSelects = this.container.querySelectorAll('[data-tariff-amount-select]');

        // Событие изменения данных суммы тарифов
        this.tariffAmountsUpdateEvent = parent.tariffAmountsUpdateEvent;
        // Событие изменения типа rank
        this.tariffAmountsRankEvent = parent.tariffAmountsRankEvent;

        this.loadDataEvent = parent.loadDataEvent;


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
        
        this.init();
    }

    init() {
        Emitter.subscribe(this.loadDataEvent, ({ data }) => {
            
            this.saveData(data);
            this.initCharts();
            this.listeners();
        });
    }

    // инициализация диаграмм
    initCharts() {
        this.tariffAmountsChart = new LineChart({
            parent: this.container,
            data: this.tariffAmounts,
            chartWrapperId: '#tariff-amounts-chart',
            currency: true,
            
        });

        this.uniqueVisitorsChart = new LineChart({
            parent: this.container,
            data: this.uniqueVisitors,
            chartWrapperId: '#unique-visitors-chart',
            mini: true,
            fill: true,
            lineColors: [this.chartColors[2]]
        });

        this.paidTariffsCountChart = new BarChart({
            parent: this.container,
            data: this.paidTariffsCount,
            chartWrapperId: '#paid-tariffs-count-chart',
            mini: true,
            lineColors: [this.chartColors[5]],
        });

        this.paidDonationsCountChart = new LineChart({
            parent: this.container,
            data: this.paidDonationsCount,
            chartWrapperId: '#paid-donations-count-chart',
            mini: true,
            fill: true,
            lineColors: [this.chartColors[4]]
        });
    }

    // подписки на события
    listeners() {
        // обновление данных суммы донатов
        Emitter.subscribe(this.tariffAmountsUpdateEvent, ({ data }) => {
            this.saveData(data);
            this.tariffAmountsChart.updateChart(this.tariffAmounts);
        });

        // изменение rank
        Emitter.subscribe(this.tariffAmountsRankEvent, ({ rank }) => {
            this.tariffAmountCountSelects.forEach((select) => {
                if (select.dataset.tariffAmountSelect === rank) {
                    select.children[0].selected = true
                    select.classList.remove('hide');
                } else {
                    select.classList.add('hide');
                }
            });
        });
    }
    
    // сохраняем данные в классе
    saveData(data) {
        this.data = data;
    }

    get data() {
        return this._data;
    }

    set data(value) {
        this._data = value;
    }

    get tariffAmounts() {
        return this._data.tariffAmounts;
    }

    get uniqueVisitors() {
        return this._data.uniqueVisitors;
    }

    get paidTariffsCount() {
        return this._data.paidTariffsCount;
    }

    get paidDonationsCount() {
        return this._data.paidDonationsCount;
    }
}
