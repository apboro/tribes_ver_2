import { BarChart } from "../../Helper/Chart/BarChart";
import { DoughnutChart } from "../../Helper/Chart/DoughnutChart";
import { LineChart } from "../../Helper/Chart/LineChart";

export class ChartsController {
    constructor(parent) {
        this.container = parent.container;
        this._data = {};

        //this.tariffAmountCountSelects = this.container.querySelectorAll('[data-tariff-amount-select]');

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
        console.log(1);
        this.tariffAmountsChart = new LineChart({
            parent: this.container,
            data: this.tariffAmounts,
            chartWrapperId: '#tariff-amounts-chart',
            currency: true,
            
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
