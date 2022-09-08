import { numberFormatting } from "../../core/functions";
import { BaseChart } from "../Helper/Chart/BaseChart";

export class AnalyticsListPage {
    constructor(parent) {
        this.container = parent.container.querySelector('[data-tab="analyticsListPage"]');

        this.filterNode = this.container.querySelector('#period_filter');
        
        this.subscribersId = 'subscribers_chart';
        this.messagesId = 'messages_chart';
        this.paymentsId = 'payments_chart';
        
        this.subscribersChart = null;
        this.messagesChart = null;
        this.paymentsChart = null;

        this.subdcribersLeftLabel = this.container.querySelector('#subscribers_left_label');
        this.subdcribersRightLabel = this.container.querySelector('#subscribers_right_label');

        this.messagesLeftLabel = this.container.querySelector('#messages_left_label');
        this.messagesRightLabel = this.container.querySelector('#messages_right_label');

        this.paymentsLeftLabel = this.container.querySelector('#payments_left_label');
        this.paymentsRightLabel = this.container.querySelector('#payments_right_label');

        this.data = {};

        this.init();
    }

    init() {
        this.loadData();
        this.fillLabels();
        this.initCharts();
    }

    loadData() {
        this.data = {
            marks: ['Пон', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
            subscribers: {
                items: [12, 19, 3, 5, 2, 3, 31],
                left: 96,
                right: 13
            },

            messages: {
                items: [102, 190, 30, 50, 20, 30, 31],
                left: 563,
                right: 233
            },

            payments: {
                items: [12, 109, 300, 500, 20, 300, 301],
                left: 24300,
                right: 20300
            },
        }
    }

    loadYear() {
        this.data = {
            marks: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
            subscribers: {
                items: [12, 19, 3, 5, 2, 3, 31, 12, 19, 3, 5, 2,],
                left: 196,
                right: 113
            },

            messages: {
                items: [102, 190, 30, 20, 30, 31, 102, 190, 30, 50, 20, 30],
                left: 1563,
                right: 1233
            },

            payments: {
                items: [12, 109, 300, 500, 20, 300, 301, 12, 109, 300, 20, 300],
                left: 214300,
                right: 210300
            },
        }
    }

    fillLabels() {
        this.subdcribersLeftLabel.textContent = `+${ numberFormatting(this.subscribersLeft) }`;
        this.subdcribersRightLabel.textContent = `+${ numberFormatting(this.subscribersRight) }`;
        this.messagesLeftLabel.textContent = `+${ numberFormatting(this.messagesLeft) }`;
        this.messagesRightLabel.textContent = `+${ numberFormatting(this.messagesRight) }`;
        this.paymentsLeftLabel.textContent = `+${ numberFormatting(this.paymentsLeft) }`;
        this.paymentsRightLabel.textContent = `+${ numberFormatting(this.paymentsRight) }`;
    }

    initCharts() {
        this.subscribersChart = this.initChart(this.subscribersId, this.subscribersItems);
        this.messagesChart = this.initChart(this.messagesId, this.messagesItems);
        this.paymentsChart = this.initChart(this.paymentsId, this.paymentsItems);
    }

    initChart(id, data) {
        return new BaseChart({
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

    switchFilter(event) {
        this.loadYear();
        this.fillLabels()
        this.subscribersChart.changeData(this.marks, this.subscribersItems);
        this.messagesChart.changeData(this.marks, this.messagesItems);
        this.paymentsChart.changeData(this.marks, this.paymentsItems);
    }

    get marks() {
        return this.data.marks;
    }

    get subscribersItems() {
        return this.data.subscribers.items;
    }

    get subscribersLeft() {
        return this.data.subscribers.left;
    }

    get subscribersRight() {
        return this.data.subscribers.right;
    }

    get messagesItems() {
        return this.data.messages.items;
    }

    get messagesLeft() {
        return this.data.messages.left;
    }

    get messagesRight() {
        return this.data.messages.right;
    }

    get paymentsItems() {
        return this.data.payments.items;
    }

    get paymentsLeft() {
        return this.data.payments.left;
    }

    get paymentsRight() {
        return this.data.payments.right;
    }
}
