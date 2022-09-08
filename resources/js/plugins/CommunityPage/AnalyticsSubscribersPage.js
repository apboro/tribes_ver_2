import { BaseChart } from "../Helper/Chart/BaseChart";
import { SubscribersTable } from "./StatisticPage/SubscribersTable";

export class AnalyticsSubscribersPage {
    constructor(parent) {
        this.container = parent.container.querySelector('[data-tab="analyticsSubscribersPage"]');

        this.messagesId = 'messages_chart';
        this.messagesChart = null;

        this.data = {};

        this.table = new SubscribersTable(this.container.querySelector('#subscribers_table'));
        this.init();
    }

    init() {
        this.loadData();
        this.fillLabels();
        this.initChart();
    }

    switchTab(event) {
        window.location.href = event.target.value;
    }

    loadData() {
        this.data = {
            marks: ['Пон', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
            messages: {
                items: [102, 190, 30, 50, 20, 30, 31],
                items2: [190, 20, 30, 102, 30, 50, 31],
                left: 563,
                right: 233
            },
        }
    }

    fillLabels() {}

    initChart() {
        this.messagesChart = new BaseChart({
            id: this.messagesId,
            type: 'line',
            data: {
                labels: this.marks,
                datasets: [
                    {
                        data: this.messagesItems,
                        borderColor: "#21C169",
                        hidden: false,
                    },
                    {
                        data: this.messagesItems2,
                        borderColor: "#E24041",
                        hidden: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                radius: 1,
                hoverRadius: 0,
                borderWidth: 2,
                pointBorderColor: 'transparent',
                //tension: 0.1,
                
                animation: {
                    duration: 1000,
                    easing: 'easeInOutCubic'
                },
                
                scales: {
                    x: {
                        ticks: {    
                            color: '#000000',

                            font: {
                                //family: "'Montserrat', 'sans-serif'",
                                size: 14,
                                weight: 600,
                                lineHeight: '20px',
                            },

                            // callback: (label, index, labels) => {
                            //     return this.labels[index];
                            // },
                        },
                        grid: {
                            borderColor: '#7367F0',
                            color: 'transparent',
                            tickColor: '#7367F0'
                        },
                    },
                    
                    y: {
                        ticks: {    
                            color: '#000000',

                            font: {
                                size: 14,
                                weight: 600,
                                lineHeight: '20px',
                            },

                            // callback: (label, index, labels) => {
                            //     return this.labels[index];
                            // },
                        },
                        
                        grid: {
                            borderColor: '#7367F0',
                            color: 'transparent',
                            tickColor: '#7367F0'
                        },
                    }
                },

                plugins: {
                    legend: { display: false },
                    title: { display: false },
                    tooltip: { enabled: false },
                }
            }
        });
    }

    switchFilter(event) {
        console.log(event.target.value);
        // this.loadYear();
        // this.fillLabels()
        // this.subscribersChart.changeData(this.marks, this.subscribersItems);
        // this.messagesChart.changeData(this.marks, this.messagesItems);
        // this.paymentsChart.changeData(this.marks, this.paymentsItems);
    }

    get marks() {
        return this.data.marks;
    }

    get messagesItems() {
        return this.data.messages.items;
    }

    get messagesItems2() {
        return this.data.messages.items2;
    }

    get messagesLeft() {
        return this.data.messages.left;
    }

    get messagesRight() {
        return this.data.messages.right;
    }
}
