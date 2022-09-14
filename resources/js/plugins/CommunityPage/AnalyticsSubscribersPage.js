import { numberFormatting } from "../../core/functions";
import { BaseAnalyticsPage } from "./BaseAnalyticsPage";

export class AnalyticsSubscribersPage extends BaseAnalyticsPage {
    constructor(parent) {
        super(parent);
        this.container = parent.container.querySelector('[data-tab="analyticsSubscribersPage"]');
        // Настройки таблицы
        this.headerItems = [
            { text: 'Имя подписчика', sortName: 'name' },
            { text: 'Никнейм', sortName: 'nick_name' },
            { text: 'Дата', sortName: 'accession_date' },
            { text: 'Сообщения', sortName: 'c_messages' },
            { text: 'Реакции (оставил)', sortName: 'c_put_reactions' },
            { text: 'Реакции (получил)', sortName: 'c_got_reactions' },
            { text: 'Полезность', sortName: 'utility' },
        ];
        this.rowItemsFormat = [
            { type: 'text', key: 'name' },
            { type: 'text', key: 'nick_name' },
            { type: 'date', key: 'accession_date' },
            { type: 'text', key: 'c_messages' },
            { type: 'text', key: 'c_put_reactions' },
            { type: 'text', key: 'c_got_reactions' },
            { type: 'text', key: 'utility' },
        ];
        // Настройки пагинации
        this.paginationEvent = 'pagination: subscribers';
        // Настройки соритровки
        this.sortName = 'accession_date';
        this.sortNameDefault = 'accession_date';
        this.sortEvent = 'sort: subscribers';

        this.countAllUsersNode = this.container.querySelector('#count_all_users');
        this.countExitUsersNode = this.container.querySelector('#count_exit_users');
        this.countJoinUsersNode = this.container.querySelector('#count_join_users');

        this.isUsersHidden = false;
        this.isExitUsersHidden = false;
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
        } catch (error) {
            console.log(error);
            this.data = false;
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
        this.countAllUsersNode.textContent = numberFormatting(this.countAllUsers);

        this.countExitUsersNode.textContent = `-${ numberFormatting(this.countExitUsers) }`;
        this.countExitUsersNode.style.color = this.chartDatasets[1].borderColor;

        this.countJoinUsersNode.textContent = `+${ numberFormatting(this.countJoinUsers) }`;
        this.countJoinUsersNode.style.color = this.chartDatasets[0].borderColor;
    }

    toggleChartVisibility(name) {
        if (name == 'exit_users') {
            this.isExitUsersHidden = !this.isExitUsersHidden;
        } else if (name == 'users') {
            this.isUsersHidden = !this.isUsersHidden;
        }
        this.сhart.changeData(this.marks, this.chartDatasets);
    }
    
    get users() {
        return this.data.items.users;
    }

    get exitUsers() {
        return this.data.items.exit_users;
    }

    get chartDatasets() {
        return [
            {
                data: this.users,
                borderColor: "#21C169",
                hidden: this.isUsersHidden,
            },
            {
                data: this.exitUsers,
                borderColor: "#E24041",
                hidden: this.isExitUsersHidden,
            }
        ]
    }

    get countAllUsers() {
        return this.data.meta.all_users;
    }

    get countExitUsers() {
        return this.data.meta.count_exit_users;
    }
    
    get countJoinUsers() {
        return this.data.meta.count_join_users;
    }
}
