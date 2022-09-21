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
                    community_id: this.communityId,
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
                    community_id: this.communityId,
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
                    community_id: this.communityId,
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
        this.subdcribersLeftLabel.textContent = `-${ numberFormatting(this.subscribersLeft) }`;
        this.subdcribersRightLabel.textContent = `+${ numberFormatting(this.subscribersRight) }`;
        this.messagesLeftLabel.textContent = `+${ numberFormatting(this.messagesLeft) }`;
        this.messagesRightLabel.textContent = `+${ this.messagesRight ? numberFormatting(this.messagesRight) : '0' }`;
        this.paymentsLeftLabel.textContent = `+${ numberFormatting(convertToRub(this.paymentsLeft)) }`;
        this.paymentsRightLabel.textContent = `+${ numberFormatting(convertToRub(this.paymentsRight)) }`;
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
        return this.subscribersData.items.users;
    }

    get subscribersLeft() {
        return this.subscribersData.meta.count_exit_users;
    }

    get subscribersRight() {
        return this.subscribersData.meta.count_join_users;
    }

    get messagesItems() {
        return this.messagesData.items.messages;
    }

    get messagesLeft() {
        return this.messagesData.meta.count_new_message;
    }

    get messagesRight() {
        return this.messagesData.meta.count_new_utility;
    }

    get paymentsItems() {
        return this.paymentsData.items.balance;
    }

    get paymentsLeft() {
        return this.paymentsData.meta.total_amount;
    }

    get paymentsRight() {
        return this.paymentsData.meta.all;
    }
}
