import { timeFormatting } from "../../core/functions";
import { BaseChart } from "../Helper/Chart/BaseChart";
import { SubscribersTable } from "./StatisticPage/SubscribersTable";

export class AnalyticsSubscribersPage {
    constructor(parent) {
        this.container = parent.container.querySelector('[data-tab="analyticsSubscribersPage"]');
        this.communityId = parent.communityId;

        this.messagesId = 'messages_chart';
        this.messagesChart = null;

        this.data = {};

        this.table = null;
        this.tableData = null;

        this.filterPeriodValue = 'week';
        this.init();
    }

    async init() {
        await this.loadData();
        await this.loadTableData();
        this.fillLabels();
        this.initChart();
        this.initTable();
    }

    switchTab(event) {
        window.location.href = event.target.value;
    }

    async loadData() {
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

            this.data = data;
            
            // this.tableData = [
            //     { name: 'Oleg', username: 'Pyatak', date: new Date(), messages: 11, reaction_g: 7, reaction_b: 5, profit: 5 },
            //     { name: 'Oleg', username: 'Pyatak', date: new Date(), messages: 11, reaction_g: 7, reaction_b: 5, profit: 5 }
    
            // ]
        } catch (error) {
            console.log(error);
        }
    }

    async loadTableData() {
        try {
            const { data } = await axios({
                method: 'post',
                url: '/api/tele-statistic/members',
                data: {
                    community_id: this.communityId,
                    filter: {
                        period: this.filterPeriodValue
                    }
                }
            });
            console.log(data);
            this.tableData = data.items;
        } catch (error) {
            console.log(error);
        }
    }

    fillLabels() {}

    initChart() {
        this.messagesChart = new BaseChart({
            id: this.messagesId,
            type: 'line',
            data: {
                labels: this.marks,
                datasets: this.chartDatasets
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

    initTable() {
        this.table = new SubscribersTable({
            parent: this.container.querySelector('#subscribers_table'),
            headerItems: [
                { text: 'Имя подписчика', sortValue: 'name' },
                { text: 'Никнейм', sortValue: 'nick_name' },
                { text: 'Дата', sortValue: 'accession_date' },
                { text: 'Сообщения', sortValue: 'c_messages' },
                { text: 'Реакции (оставил)', sortValue: 'c_put_reactions' },
                { text: 'Реакции (получил)', sortValue: 'c_got_reactions' },
                { text: 'Телеграм (id)', sortValue: 'tele_id' },
            ],
            rowItemsFormat: [
                { type: 'text', key: 'name' },
                { type: 'text', key: 'nick_name' },
                { type: 'date', key: 'accession_date' },
                { type: 'text', key: 'c_messages' },
                { type: 'text', key: 'c_put_reactions' },
                { type: 'text', key: 'c_got_reactions' },
                { type: 'text', key: 'tele_id' },
            ],
            data: this.tableData,
        });
    }

    async switchFilter(event) {
        this.filterPeriodValue = event.target.value;
        await this.loadData();
        await this.loadTableData();
        this.messagesChart.changeData(this.marks, this.chartDatasets);
        this.table.clear();
    }

    get marks() {
        if (this.filterPeriodValue === 'week') {
            return this.data.meta.marks.map((mark) => timeFormatting({
                date: mark,
                weekday: 'long'
            }));
        } else if (this.filterPeriodValue === 'day') {
            return this.data.meta.marks.map((mark) => timeFormatting({
                date: mark,
                hour: 'numeric',
                minute: 'numeric',
            }));
        } else if (this.filterPeriodValue === 'month') {
            return this.data.meta.marks.map((mark) => timeFormatting({
                date: mark,
                month: 'long',
                day: 'numeric'
            }));
        } else if (this.filterPeriodValue === 'year') {
            return this.data.meta.marks.map((mark) => timeFormatting({
                date: mark,
                month: 'long',
            }));
        }
    }

    get messagesItems() {
        return this.data.items.users;
    }

    get messagesItems2() {
        return this.data.items.exit_users;
    }

    get chartDatasets() {
        return [
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
    }

    get messagesLeft() {
        return this.data.messages.left;
    }

    get messagesRight() {
        return this.data.messages.right;
    }
}
