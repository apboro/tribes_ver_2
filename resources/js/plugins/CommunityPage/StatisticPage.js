import { BaseChart } from '../Helper/Chart/BaseChart';
      

        
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
        // this.chartsController = new ChartsController(this);
        // Переключатели даннных на диаграммах
        //this.chartsSwitcher = new ChartsSwitcher(this);
        // Данные диаграмма
        // this.chartsData = new ChartsData(this);
        this.init()
    }

    init() {
        const labels = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
          ];
        
        const data = {
            labels: labels,
            datasets: [{
                label: 'My First dataset',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [0, 10, 5, 2, 20, 30, 45],
            }]
        };
    
        new BaseChart({
            id: 'tariff-amounts-chart',
            type: 'line',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: "#2AB0EE",
                    hidden: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                radius: 0,
                hoverRadius: 0,
                borderWidth: 1,
                pointBorderColor: 'transparent',
                tension: 0.1,
                
                animation: {
                    duration: 1000,
                    easing: 'easeInOutCubic'
                },
                
                scales: {
                    x: { display: false },
                    y: { display: false }
                },

                plugins: {
                    legend: { display: false },
                    title: { display: false },
                    tooltip: { enabled: false },
                }
            }
        });
    }
}
