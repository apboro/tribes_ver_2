import { BaseChart } from "../Helper/Chart/BaseChart";

export class AnalyticsListPage {
    constructor(parent) {
        this.container = parent.container.querySelector('[data-tab="analyticsListPage"]');
        
        this.subscribersId = 'subscribers_chart';
        this.messagesId = 'messages_chart';
        this.paymentsId = 'payments_chart';

        this.data = {};

        this.init();
    }

    init() {
        this.loadData();
        
        this.initCharts();
    }

    loadData() {
        this.data = {
            marks: ['Пон', 'Вт', 'Ср', 'ЧТ', 'Пт', 'Сб', 'Вс'],
            subscribers: {
                items: [12, 19, 3, 5, 2, 3, 31],
            },

            messages: {
                items: [102, 190, 30, 50, 20, 30, 31],
            },

            payments: {
                items: [12, 109, 300, 500, 20, 300, 301],
            },
        }
    }

    initCharts() {
        this.initChart(this.subscribersId, this.subscribersItems);
        this.initChart(this.messagesId, this.messagesItems);
        this.initChart(this.paymentsId, this.paymentsItems);
    }

    initChart(id, data) {
        new BaseChart({
            id,
            type: 'line',
            data: {
                labels: this.marks,
                datasets: [{
                    data,
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

    get marks() {
        return this.data.marks;
    }

    get subscribersItems() {
        return this.data.subscribers.items;
    }

    get messagesItems() {
        return this.data.messages.items;
    }

    get paymentsItems() {
        return this.data.payments.items;
    }
}
