import { numberFormatting } from "../../core/functions";
import { BaseAnalyticsPage } from "./BaseAnalyticsPage";

export class AnalyticsMessagesPage extends BaseAnalyticsPage {
    constructor(parent) {
        super(parent);
        this.container = parent.container.querySelector('[data-tab="analyticsMessagesPage"]');
        // Настройки таблицы
        this.headerItems = [
            { text: 'Сообщения/реакция', sortName: false },
            { text: 'Имя автора', sortName: 'name' },
            { text: 'Никнейм', sortName: 'nick_name' },
            { text: 'Дата', sortName: 'message_date' },
            { text: 'Реакции', sortName: 'count_reactions' },
            { text: 'Ответы', sortName: 'answers' },
            { text: 'Полезность', sortName: 'utility' },
        ];
        this.rowItemsFormat = [
            { type: 'text&array', key: 'text', arrayKey: 'reactions' },
            { type: 'text', key: 'name' },
            { type: 'text', key: 'nick_name' },
            { type: 'date', key: 'message_date' },
            { type: 'text', key: 'count_reactions' },
            { type: 'text', key: 'answers' },
            { type: 'text', key: 'utility' },
        ];
        // Настройки пагинации
        this.paginationEvent = 'pagination: messages';
        // Настройки соритровки
        this.sortName = 'message_date';
        this.sortNameDefault = 'message_date';
        this.sortEvent = 'sort: messages';

        this.countAllMessageNode = this.container.querySelector('#count_all_message');
        this.countNewMessageNode = this.container.querySelector('#count_new_message');
        this.countNewUtilityNode = this.container.querySelector('#count_new_utility');

        this.isMessageHidden = false;
        this.isUtilityHidden = false;
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
        } catch (error) {
            console.log(error);
            this.data = false;
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
        this.countAllMessageNode.textContent = numberFormatting(this.countAllMessage);

        this.countNewMessageNode.textContent = `+${ numberFormatting(this.countNewMessage) }`;
        this.countNewMessageNode.style.color = this.chartDatasets[1].borderColor;

        this.countNewUtilityNode.textContent = `+${ numberFormatting(this.countNewUtility) }`;
        this.countNewUtilityNode.style.color = this.chartDatasets[0].borderColor;
    }

    toggleChartVisibility(name) {
        if (name == 'messages') {
            this.isMessageHidden = !this.isMessageHidden;
        } else if (name == 'utility') {
            this.isUtilityHidden = !this.isUtilityHidden;
        }
        this.сhart.changeData(this.marks, this.chartDatasets);
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
                hidden: this.isUtilityHidden,
            },
            {
                data: this.messages,
                borderColor: "#30AAF0",
                hidden: this.isMessageHidden,
            }
        ]
    }

    get countAllMessage() {
        return this.data.meta.count_all_message;
    }

    get countNewMessage() {
        return this.data.meta.count_new_message;
    }
    
    get countNewUtility() {
        return this.data.meta.count_new_utility;
    }
}
