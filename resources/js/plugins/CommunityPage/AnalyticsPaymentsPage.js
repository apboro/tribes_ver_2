import { BaseAnalyticsPage } from "./BaseAnalyticsPage";

export class AnalyticsPaymentsPage extends BaseAnalyticsPage {
    constructor(parent) {
        super(parent);
        this.container = parent.container.querySelector('[data-tab="analyticsPaymentsPage"]');
        // Настройки таблицы
        this.headerItems = [
            { text: 'Имя подписчика', sortName: 'first_name' },
            { text: 'Никнейм', sortName: 'tele_login' },
            { text: 'Название транзакций', sortName: 'status' },
            { text: 'Тип транзакции', sortName: 'payable_type' },
            { text: 'Дата', sortName: 'buy_date' },
            { text: 'Сумма', sortName: 'amount' }
        ];
        this.rowItemsFormat = [
            { type: 'text', key: 'first_name' },
            { type: 'text', key: 'tele_login' },
            { type: 'text', key: 'status' },
            { type: 'text', key: 'payable_type' },
            { type: 'date', key: 'buy_date' },
            { type: 'text', key: 'amount' },
        ];
        // Настройки пагинации
        this.paginationEvent = 'pagination: payments';
        // Настройки соритровки
        this.sortName = 'buy_date';
        this.sortNameDefault = 'buy_date';
        this.sortEvent = 'sort: payments';

        this.countDonationsNode = this.container.querySelector('#count_donations');
        this.countDonationsValueNode = this.countDonationsNode.querySelector('#count_donations_value');
        this.countTariffsNode = this.container.querySelector('#count_tariffs');
        this.countTariffsValueNode = this.countTariffsNode.querySelector('#count_tariffs_value');
        this.countCoursesNode = this.container.querySelector('#count_courses');
        this.countCoursesValueNode = this.countCoursesNode.querySelector('#count_courses_value');

        this.isDonationsHidden = false;
        this.isTariffsHidden = false;
        this.isCoursesHidden = false;
    }

    async loadData() {
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
                    community_id: this.communityId,
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
        }
    }

    fillLabels() {
        this.countDonationsNode.style.color = this.chartDatasets[0].borderColor;
        //this.countDonationsValueNode = this.countDonationsNode.querySelector('#count_donations_value');
        this.countTariffsNode.style.color = this.chartDatasets[1].borderColor;
        //this.countTariffsValueNode = this.countTariffsNode.querySelector('#count_tariffs_value');
        this.countCoursesNode.style.color = this.chartDatasets[2].borderColor;
        //this.countCoursesValueNode = this.countCoursesNode.querySelector('#count_courses_value');


        // this.countNewMessageNode.textContent = `+${ numberFormatting(this.countNewMessage) }`;
        // this.countNewMessageNode.style.color = this.chartDatasets[1].borderColor;

        // this.countNewUtilityNode.textContent = `+${ numberFormatting(this.countNewUtility) }`;
        // this.countNewUtilityNode.style.color = this.chartDatasets[0].borderColor;
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

    get donations() {
        return this.data.items.donate_balance;
    }

    get tariffs() {
        return this.data.items.tariff_balance;
    }

    get courses() {
        return this.data.items.course_balance;
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
            {
                data: this.courses,
                borderColor: "#FF9F43",
                hidden: this.isCoursesHidden,
            }
        ]
    }
}
