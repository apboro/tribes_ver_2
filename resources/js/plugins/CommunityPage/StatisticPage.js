import { ChartsController } from "./StatisticPage/ChartsController";
import { ChartsData } from "./StatisticPage/ChartsData";
import { ChartsSwitcher } from "./StatisticPage/ChartsSwitcher";

export class StatisticPage {
    constructor(parent) {
        this.container = parent.container.querySelector('[data-tab="statisticPage"]');
        
        // Событие изменения rank для tariff amounts
        this.tariffAmountsRankEvent = 'TariffAmountsRank: string';
        // Событие изменения count для tariff amounts
        this.tariffAmountsCountEvent = 'TariffAmountsCount: string';
        // Событие обновления данных для tariff amounts
        this.tariffAmountsUpdateEvent = 'TariffAmountsUpdate: data';
        // Событие загрузки данных
        this.loadDataEvent = 'LoadChartData: data';
        

        
        // Диаграммы
        this.chartsController = new ChartsController(this);
        // Переключатели даннных на диаграммах
        this.chartsSwitcher = new ChartsSwitcher(this);
        // Данные диаграмма
        this.chartsData = new ChartsData(this);
    }
}
