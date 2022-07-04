import { BaseChart } from "./BaseChart";

export class DoughnutChart extends BaseChart {
    constructor(options) {
        super(options);
    }

    init() {
        super.init();
        this.initChartDescription();  
    }

    initParams() {
        super.initParams();
        this.chartDescription = this.parent.querySelector('#doughnut_chart_description');
    }

    updateChart(data) {
        this.changeData = data;
        this.updateChartDescription();
        this.updateChartInstance();
    }

    // Верстка для диаграмы типа догнут
    addDescriptionElement(label, value, color) {
        const chartDescriptionBlock = document.createElement('div');
        chartDescriptionBlock.innerHTML = `
            <div class="d-flex justify-content-between mb-1">
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background-color: ${ color }"></div>
                    <span class="fw-bold ms-75 me-25">${ label }</span>
                </div>
                <div>
                    <span>${ value }</span>
                </div>
            </div>
        `;
       
        this.chartDescription.append(chartDescriptionBlock);
    }

    // Набор цветов для всех значений данных
    getLabelsBackground() {
        return this.chartColors.slice(0, this.dataLabels.length);
    }

    // Экземпляр диаграмы типа догнут
    initChart() {
        this.chartInstance = new Chart(this.chartEx, {
            type: 'doughnut',

            data: {
                datasets: [
                    {
                        data: this.dataValues,
                        backgroundColor: this.labelBackground,
                        borderWidth: 0,
                        pointStyle: 'rectRounded'
                    },
                ],
                labels: this.dataLabels,
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                responsiveAnimationDuration: 500,
                cutoutPercentage: 60,
                

                plugins: {
                    legend: { 
                        display: false,
                        position: 'bottom',
                        //align: 'start',
                        maxHeight: 100,
                        maxWidth: 10,
                        fullSize: false,
                        labels: {
                            boxWidth: 20,
                            boxHeight: 20
                        }
                    },

                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.formattedValue;
                                return ` ${ label } : ${ value }`;
                            }
                        },
                        // Updated default tooltip UI
                        backgroundColor: '#000',
                        titleFontColor: '#fff',
                        bodyFontColor: '#000'
                    }
                }
            },
        });
    }
}
