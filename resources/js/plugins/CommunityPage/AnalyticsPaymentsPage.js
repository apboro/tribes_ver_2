import { BaseAnalyticsPage } from "./BaseAnalyticsPage";

export class AnalyticsPaymentsPage extends BaseAnalyticsPage {
    constructor(parent) {
        super(parent);
        this.container = parent.container.querySelector('[data-tab="analyticsMessagesPage"]');
        // Настройки таблицы
        /* this.headerItems = [
            { text: 'Имя подписчика', sortName: 'name' },
            { text: 'Никнейм', sortName: 'nick_name' },
            { text: 'Дата', sortName: 'accession_date' },
            { text: 'Сообщения', sortName: 'c_messages' },
            { text: 'Реакции (оставил)', sortName: 'c_put_reactions' },
            { text: 'Реакции (получил)', sortName: 'c_got_reactions' },
            { text: 'Телеграм (id)', sortName: 'tele_id' },
        ];
        this.rowItemsFormat = [
            { type: 'text', key: 'name' },
            { type: 'text', key: 'nick_name' },
            { type: 'date', key: 'accession_date' },
            { type: 'text', key: 'c_messages' },
            { type: 'text', key: 'c_put_reactions' },
            { type: 'text', key: 'c_got_reactions' },
            { type: 'text', key: 'tele_id' },
        ]; */
        // Настройки пагинации
        this.paginationEvent = 'pagination: messages';
        // Настройки соритровки
        this.sortName = 'accession_date';
        this.sortEvent = 'sort: messages';
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
                hidden: false,
            },
            {
                data: this.tariffs,
                borderColor: "#E24041",
                hidden: false,
            },
            {
                data: this.courses,
                borderColor: "#FF9F43",
                hidden: false,
            }
        ]
    }
}
