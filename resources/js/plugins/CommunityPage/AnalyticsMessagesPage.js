import { BaseAnalyticsPage } from "./BaseAnalyticsPage";

export class AnalyticsMessagesPage extends BaseAnalyticsPage {
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
                url: '/api/tele-statistic/message-charts',
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
                url: '/api/tele-statistic/messages',
                data: {
                    community_id: this.communityId,
                    filter: {
                        period: this.filterPeriodValue,
                        sort: {
                            name: this.sortName,
                            rule: this.sortRule
                        },
                        page: this.activePage
                    }
                }
            });
        
            this.tableData = data.items;
            this.paginationData = data.meta;
        } catch (error) {
            console.log(error);
        }
    }

    get utility() {
        return this.data.items.utility;
    }

    get messages() {
        return this.data.items.messages;
    }

    get chartDatasets() {
        return [
            {
                data: this.utility,
                borderColor: "#21C169",
                hidden: false,
            },
            {
                data: this.messages,
                borderColor: "#E24041",
                hidden: false,
            }
        ]
    }
}
