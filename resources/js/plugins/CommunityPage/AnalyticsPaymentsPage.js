import { convertToRub, numberFormatting } from "../../core/functions";
import { BaseAnalyticsPage } from "./BaseAnalyticsPage";

export class AnalyticsPaymentsPage extends BaseAnalyticsPage {
    constructor(parent) {
        super(parent);
        this.container = parent.container.querySelector('[data-tab="analyticsPaymentsPage"]');
        // Настройки таблицы
        this.headerItems = [
            { text: 'Имя подписчика', sortName: 'first_name' },
            { text: 'Никнейм', sortName: 'tele_login' },
            { text: 'Оплата', sortName: false },
            { text: 'Тип транзакции', sortName: false },
            { text: 'Дата', sortName: 'buy_date' },
            { text: 'Сумма', sortName: 'amount' }
        ];
        this.rowItemsFormat = [
            { type: 'text', key: 'first_name' },
            { type: 'text', key: 'tele_login' },
            { type: 'text', key: 'status' },
            { type: 'object', key: 'type', value: 'name' },
            { type: 'date', key: 'buy_date' },
            { type: 'rub', key: 'amount' },
        ];
        // Настройки пагинации
        this.paginationEvent = 'pagination: payments';
        // Настройки соритровки
        this.sortName = 'buy_date';
        this.sortNameDefault = 'buy_date';
        this.sortEvent = 'sort: payments';

        this.countTotalAmountNode = this.container.querySelector('#count_total_amount');
        this.countAllNode = this.container.querySelector('#count_all');
        this.countDonationsNode = this.container.querySelector('#count_donations');
        this.countDonationsValueNode = this.countDonationsNode.querySelector('#count_donations_value');
        this.countTariffsNode = this.container.querySelector('#count_tariffs');
        this.countTariffsValueNode = this.countTariffsNode.querySelector('#count_tariffs_value');
        // this.countCoursesNode = this.container.querySelector('#count_courses');
        // this.countCoursesValueNode = this.countCoursesNode.querySelector('#count_courses_value');

        this.isDonationsHidden = false;
        this.isTariffsHidden = false;
        this.isCoursesHidden = false;

        this.fileUploadUrl = '/api/tele-statistic/export-payments';
    }

    async loadData() {
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

            this.data = data;
            
            return true;
        } catch (error) {
            console.log(error);
            return false;
        }
    }

    async loadTableData() {
        try {
            const { data } = await axios({
                method: 'post',
                url: '/api/tele-statistic/payments-list',
                data: {
                    community_ids: window.community_ids,
                    filter: {
                        period: this.filterPeriodValue,
                        sort: {
                            name: this.sortName,
                            rule: this.sortRule
                        },
                        page: this.activePage,
                        per_page: 10
                    }
                }
            });
        
            this.tableData = data.items;
            this.paginationData = data.meta;
        } catch (error) {
            console.log(error);
            this.tableData = false;
            this.paginationData = false;
        }
    }

    fillLabels() {
        this.countTotalAmountNode.textContent = this.countTotalAmount != 0 ? numberFormatting(convertToRub(this.countTotalAmount)) : 0;
        this.countAllNode.textContent = this.countAll != 0 ? numberFormatting(convertToRub(this.countAll)) : 0;

        this.countDonationsValueNode.textContent = this.countDonations != 0 ? numberFormatting(convertToRub(this.countDonations)) : 0;
        this.countDonationsNode.style.color = this.chartDatasets[0].borderColor;

        this.countTariffsValueNode.textContent = this.countTariffs != 0 ? numberFormatting(convertToRub(this.countTariffs)) : 0;
        this.countTariffsNode.style.color = this.chartDatasets[1].borderColor;

        // this.countCoursesValueNode.textContent = this.countCourses != 0 ? numberFormatting(convertToRub(this.countCourses)) : 0;
        // this.countCoursesNode.style.color = this.chartDatasets[2].borderColor;
    }

    toggleChartVisibility(name) {
        if (name == 'donations') {
            this.isDonationsHidden = !this.isDonationsHidden;
        } else if (name == 'tariffs') {
            this.isTariffsHidden = !this.isTariffsHidden;
        } else if (name == 'courses') {
            this.isCoursesHidden = !this.isCoursesHidden;
        }
        this.сhart.changeData(this.marks, this.chartDatasets);
    }

    // async loadFile(type) {
    //     let exportType = type == 'csv' ? 'csv' : 'xlsx';
    //     try {
    //         const res = await axios({
    //             method: 'post',
    //             url: this.url,
    //             responseType: "blob",
    //             data: {
    //                 community_id: this.communityId,
    //                 export_type: exportType,
    //                 filter: {
    //                     period: this.filterPeriodValue,
    //                 }
    //             }
    //         });

    //         let blob = new Blob([res.data], {
    //             type: res.headers['content-type'],
    //         });

    //         let anchor = document.createElement('a');
    //         anchor.download = `StatisticExport(${ res.headers.date })`;
    //         anchor.href = (window.webkitURL || window.URL).createObjectURL(blob);
    //         anchor.dataset.downloadurl = [res.headers['content-type'], anchor.download, anchor.href].join(':');
    //         anchor.click();
    //     } catch (error) {
    //         console.log(error);
    //     }
    // }

    get donations() {
        return this.data.items.donate_balance.map((item) => convertToRub(item));
    }

    get tariffs() {
        return this.data.items.tariff_balance.map((item) => convertToRub(item));
    }

    get courses() {
        return this.data.items.course_balance.map((item) => convertToRub(item));
    }

    get chartDatasets() {
        return [
            {
                data: this.donations,
                borderColor: "#363440",
                hidden: this.isDonationsHidden,
            },
            {
                data: this.tariffs,
                borderColor: "#E24041",
                hidden: this.isTariffsHidden,
            },
            // {
            //     data: this.courses,
            //     borderColor: "#FF9F43",
            //     hidden: this.isCoursesHidden,
            // }
        ]
    }

    get countAll() {
        return this.data.meta.all ? this.data.meta.all : 0;
    }

    get countTotalAmount() {
        return this.data.meta.total_amount ? this.data.meta.total_amount : 0;
    }

    get countDonations() {
        return this.data.meta.donate ? this.data.meta.donate : 0;
    }

    get countTariffs() {
        return this.data.meta.tariff ? this.data.meta.tariff : 0;
    }

    get countCourses() {
        return this.data.meta.course ? this.data.meta.course : 0;
    }
}
