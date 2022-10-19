import { convertToRub, numberFormatting } from "../../core/functions";
import { BaseChart } from "../Helper/Chart/BaseChart";

export class AnalyticsListPage {
    constructor(parent) {
        this.container = parent.container.querySelector('[data-tab="analyticsListPage"]');
        this.communityId = parent.communityId;

        // Настройки фильтров
        this.filterPeriodValue = 'week';

        this.subscribersData = null;
        this.messagesData = null;
        this.paymentsData = null;
        
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

        this.init();
    }

    async init() {
        await this.loadData();
        this.initCharts();
        this.fillLabels();
    }

    async loadData() {
        await this.loadSubscribers();
        await this.loadMessages();
        await this.loadPayments();
    }

    async loadSubscribers() {

        try {
            const { data } = await axios({
                method: 'post',
                url: '/api/tele-statistic/member-charts',
                data: {
                    community_ids: window.community_ids,
                    filter: {
                        period: this.filterPeriodValue
                    }
                }
            });

            this.subscribersData = data;
        } catch (error) {
            console.log(error);
            this.subscribersData = false;
        }
    }

    async loadMessages() {
        try {
            const { data } = await axios({
                method: 'post',
                url: '/api/tele-statistic/message-charts',
                data: {
                    community_ids: window.community_ids,
                    filter: {
                        period: this.filterPeriodValue
                    }
                }
            });

            this.messagesData = data;
        } catch (error) {
            console.log(error);
            this.messagesData = false;
        }
    }

    async loadPayments() {
        try {
            const { data } = await axios({
                method: 'post',
                url: '/api/tele-statistic/payments-charts',
                data: {
                    community_ids: window.community_ids,
                    filter: {
                        period: this.filterPeriodValue
                    }
                }
            });

            this.paymentsData = data;
            return true;
        } catch (error) {
            console.log(error);
            this.paymentsData = false;
        }
    }

    fillLabels() {
        this.subdcribersLeftLabel.textContent = this.subscribersLeft != 0 ? `-${ numberFormatting(this.subscribersLeft) }` : 0;
        this.subdcribersRightLabel.textContent = this.subscribersRight != 0 ? `+${ numberFormatting(this.subscribersRight) }` : 0;
        this.messagesLeftLabel.textContent = this.messagesLeft != 0 ? `+${ numberFormatting(this.messagesLeft) }` : 0;
        this.messagesRightLabel.textContent = this.messagesRight != 0 ? `+${ numberFormatting(this.messagesRight) }` : 0;
        this.paymentsLeftLabel.textContent = this.paymentsLeft != 0 ? `+${ numberFormatting(this.paymentsLeft / 100) }` : 0;
        this.paymentsRightLabel.textContent = this.paymentsRight != 0 ? `+${ numberFormatting(this.paymentsRight / 100) }` : 0;
        console.log(this.paymentsLeft);
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

    async switchFilter(event) {
        this.filterPeriodValue = event.target.value;
        await this.loadData();
        this.fillLabels();
        this.subscribersChart.changeData(this.marks, this.setDataset(this.subscribersItems));
        this.messagesChart.changeData(this.marks, this.setDataset(this.messagesItems));
        this.paymentsChart.changeData(this.marks, this.setDataset(this.paymentsItems));
    }

    setDataset(data) {
        return [{
            data,
            borderColor: "#2AB0EE",
            hidden: false,
        }];
    }

    get marks() {
        return this.subscribersData.meta.marks;
    }

    get subscribersItems() {
        return this.subscribersData.items.users ? this.subscribersData.items.users : 0;
    }

    get subscribersLeft() {
        return this.subscribersData.meta.count_exit_users ? this.subscribersData.meta.count_exit_users : 0;
    }

    get subscribersRight() {
        return this.subscribersData.meta.count_join_users ? this.subscribersData.meta.count_join_users : 0;
    }

    get messagesItems() {
        return this.messagesData.items.messages ? this.messagesData.items.messages : 0;
    }

    get messagesLeft() {
        return this.messagesData.meta.count_new_message ? this.messagesData.meta.count_new_message : 0;
    }

    get messagesRight() {
        return this.messagesData.meta.count_new_utility ? this.messagesData.meta.count_new_utility : 0;
    }

    get paymentsItems() {
        return this.paymentsData.items.balance ? this.paymentsData.items.balance : 0;
    }

    get paymentsLeft() {
        return this.paymentsData.meta.total_amount ? this.paymentsData.meta.total_amount : 0;
    }

    get paymentsRight() {
        return this.paymentsData.meta.all ? this.paymentsData.meta.all : 0;
    }
}
