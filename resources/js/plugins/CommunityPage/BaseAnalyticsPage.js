import { timeFormatting } from "../../core/functions";
import { BaseChart } from "../Helper/Chart/BaseChart";
import { Pagination } from "../Helper/Pagination";
import { Table } from "../Helper/Table";

export class BaseAnalyticsPage {
    constructor(parent) {
        this.communityId = parent.communityId;
        // Настройки графиков
        this.сhart = null;
        this.data = {};
        // Настройки таблицы
        this.table = null;
        this.tableData = null;
        this.headerItems = '';
        this.rowItemsFormat = '';
        // Настройки пагинации
        this.pagination = null;
        this.paginationData = null;
        this.activePage = 1;
        this.paginationEvent = '';
        // Настройки соритровки
        this.sortName = '';
        this.sortRule = 'off';  // asc, desc
        this.sortEvent = '';
        // Настройки фильтров
        this.filterPeriodValue = 'week';
        
        this.init();
    }

    async init() {
        let resData = await this.loadData();
        let resTableData = await this.loadTableData();
        if (!resData || !resTableData) {
            return false;
        }
        this.fillLabels();
        this.initChart();
        this.initTable();
        this.initPagination();
        this.listeners();
    }

    listeners() {
        Emitter.subscribe(this.pagination.event, async ({ pageNumber }) => {
            this.activePage = pageNumber;
            this.updateTable();
        });

        Emitter.subscribe(this.table.sortEvent, async ({ name, rule }) => {
            this.sortName = name;
            this.sortRule = rule;
            this.resetPagination();
            this.updateTable();
        });
    }

    switchTab(event) {
        window.location.href = event.target.value;
    }
    loadData() {}
    loadTableData() {}
    // async loadData() {
    //     try {
    //         const { data } = await axios({
    //             method: 'post',
    //             url: '/api/tele-statistic/member-charts',
    //             data: {
    //                 community_id: this.communityId,
    //                 filter: {
    //                     period: this.filterPeriodValue
    //                 }
    //             }
    //         });

    //         this.data = data;
    //     } catch (error) {
    //         console.log(error);
    //     }
    // }

    // async loadTableData() {
    //     try {
    //         const { data } = await axios({
    //             method: 'post',
    //             url: '/api/tele-statistic/members',
    //             data: {
    //                 community_id: this.communityId,
    //                 filter: {
    //                     period: this.filterPeriodValue,
    //                     sort: {
    //                         name: this.sortName,
    //                         rule: this.sortRule
    //                     },
    //                     page: this.activePage
    //                 }
    //             }
    //         });
        
    //         this.tableData = data.items;
    //         this.paginationData = data.meta;
    //     } catch (error) {
    //         console.log(error);
    //     }
    // }

    fillLabels() {}

    initChart() {
        this.сhart = new BaseChart({
            id: 'chart',
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
                                size: 14,
                                weight: 600,
                                lineHeight: '20px',
                            },
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
        this.table = new Table({
            parent: this.container.querySelector('#table'),
            headerItems: this.headerItems,
            rowItemsFormat: this.rowItemsFormat,
            data: this.tableData,
            sortEvent: this.sortEvent,
            sortName: this.sortName,
            sortRule: this.sortRule
        });
    }
    
    initPagination() {
        this.pagination = new Pagination({
            parent: this.container.querySelector('#pagination'),
            data: this.paginationData,
            event: this.paginationEvent,
        });
    }

    async updateChart() {
        await this.loadData();
        this.сhart.changeData(this.marks, this.chartDatasets);
    }

    async updateTable() {
        await this.loadTableData();
        this.table.update(this.tableData);
        this.pagination.update(this.paginationData);
    }

    async switchFilter(event) {
        this.filterPeriodValue = event.target.value;
        this.resetPagination();
        await this.updateChart();
        await this.updateTable();
    }

    resetPagination() {
        this.activePage = 1;
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

    get chartDatasets() {}
}
