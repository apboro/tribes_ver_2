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
        this.sortNameDefault = '';
        this.sortRule = 'off';  // asc, desc
        this.sortEvent = '';
        // Настройки фильтров
        this.filterPeriodValue = 'week';
        
        this.init();
    }

    async init() {
        await this.loadData();
        if (this.data) {
            this.initChart();
            this.fillLabels();
        }
        await this.loadTableData();
        //if (this.tableData) {
            this.initTable();
            this.initPagination();
        //}
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
            if (rule == 'off') {
                this.sortName = this.sortNameDefault;
            }
            this.resetPagination();
            this.updateTable();
        });
    }

    switchTab(event) {
        window.location.href = event.target.value;
    }

    loadData() {}

    loadTableData() {}
    
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
        if (this.data.length || this.data) {
            this.сhart.changeData(this.marks, this.chartDatasets);
        }
    }

    updateLabels() {
        this.fillLabels();
    }

    async updateTable() {
        await this.loadTableData();
        //if (this.dataTable) {
        this.table.update(this.tableData);
        this.pagination.update(this.paginationData);
        //}
    }

    async switchFilter(event) {
        this.filterPeriodValue = event.target.value;
        this.resetPagination();
        await this.updateChart();
        this.updateLabels();
        await this.updateTable();
    }

    resetPagination() {
        this.activePage = 1;
    }

    toggleChartVisibility() {}

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

    async loadFile(type) {
        let exportType = type == 'csv' ? 'csv' : 'xlsx';
        try {
            const res = await axios({
                method: 'post',
                url: '/api/tele-statistic/export-members',
                data: {
                    community_id: this.communityId,
                    export_type: exportType,
                    filter: {
                        period: this.filterPeriodValue,
                    }
                }
            });
        
            console.log(res);
            // var encodedUri = encodeURI(data);
            // var link = document.createElement("a");
            // link.setAttribute("href", encodedUri);
            // link.setAttribute("download", `my_data.${ type }`);
            // document.body.appendChild(link); // Required for FF
            // link.click();
            //let filename = "TestReport.xlsx";
        //     let filename = type == 'csv' ? 'TestReport.csv' : 'Test.xlsx';
        //     var blob = data;
        // if(window.navigator.msSaveOrOpenBlob) {
        //     window.navigator.msSaveBlob(blob, filename);
        // }
        // else{
        //     var downloadLink = window.document.createElement('a');
        //     downloadLink.href = window.URL.createObjectURL(new Blob([blob], {type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"}));
        //     downloadLink.target = '_blank';
        //     downloadLink.download = filename;
        //     document.body.appendChild(downloadLink);
        //     downloadLink.click();
        //     document.body.removeChild(downloadLink);
        //    }

            console.log(res.headers['content-type']);

        let blob = new Blob([res.data], {type: res.headers['content-type']});

            let anchor = document.createElement('a');
            anchor.download = 'StatisticExport(' + res.headers.date + ')';
            anchor.href = (window.webkitURL || window.URL).createObjectURL(blob);
            anchor.dataset.downloadurl = [res.headers['content-type'], anchor.download, anchor.href].join(':');
            anchor.click();

        } catch (error) {
            console.log(error);
        }
    }

    get chartDatasets() {}
}


