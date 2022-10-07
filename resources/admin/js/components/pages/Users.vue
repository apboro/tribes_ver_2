<template>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-muted">
                    показать
                    <div class="mx-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" :value="filter_data.filter.entries" @input="changePage" size="3">
                    </div>
                    на странице
                </div>

                <div class="d-flex align-items-center">
                    <button
                        class="btn mx-3"
                        @click="excelLoad"
                    >
                        Выгрузить в Excel
                    </button>

                    <div class="ms-auto text-muted">
                        Поиск:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" v-model="filter_data.filter.search" aria-label="поиск">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                <tr>
                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                    <th class="w-1">ID
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="6 15 12 9 18 15"></polyline></svg>
                    </th>
                    <th>Имя</th>
                    <th>Почта</th>
                    <th>Телефон</th>
                    <th>
                        Создан
                        <i
                            class="col-1"
                            style="cursor: pointer;"
                            @click="sortBy('date')"
                        >
                            <template v-if="sortRuleOnDate == 'off'">
                                <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                                    <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                                    <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
                                </svg>
                            </template>
                            
                            <template v-else-if="sortRuleOnDate == 'asc'">
                                <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                                    <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                                    <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#36344050"/>
                                </svg>
                            </template>

                            <template v-else-if="sortRuleOnDate == 'desc'">
                                <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                                    <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#36344050"/>
                                    <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
                                </svg>
                            </template>
                        </i>    
                    </th>
                    <th>Комиссия, %</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <users-table-row v-for="user in users.data" :key="user.id" :user="user" />
                </tbody>
            </table>
        </div>
        <div v-if="users.meta && users.meta.per_page < users.meta.total" class="card-footer d-flex align-items-center">
            <p class="m-0 text-muted">Показано <span>{{ users.meta.per_page }}</span> из <span>{{ users.meta.total }}</span> записей</p>
            <ul class="pagination m-0 ms-auto">
                <li
                    v-for="(link, idx) in users.meta.links"
                    class="page-item"
                    :class="{'active' : link.active}"
                    :key="idx"
                >
                    <a class="page-link" @click="setPageByUrl(link.url)" href="#">{{ link.label }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import FilterDataUsers from '../../mixins/filterData';
import UsersTableRow from '../common/UsersTableRow.vue';

export default {
    name: "Users",
    components: { UsersTableRow },
    mixins: [FilterDataUsers],

    data() {
        return {
            sortName: 'date',
            sortRuleOnDate: 'off'
        }
    },

    watch: {
        filter_data: {
            deep: true,
            handler: _.debounce(function(v) {
                this.$store.dispatch('get_users', v);
            },400)
        }
    },
    
    async mounted(){
        await this.loadUsersData();
    },

    computed: {
        users() {
            return this.$store.getters.users;
        }
    },

    methods: {
        async loadUsersData() {
            await this.$store.dispatch('get_users', this.filter_data);
        },

        changePage(event) {
            this.filter_data.filter.entries = event.target.value;
            this.filter_data.filter.page = 1;
        },

        sortBy(name) {
            this.sortName = name;
            switch (this.sortRuleOnDate) {
                case 'off': this.sortRuleOnDate = 'asc'; break;
                case 'asc': this.sortRuleOnDate = 'desc'; break;
                case 'desc': this.sortRuleOnDate = 'off'; break;
            }

            this.filter_data.filter.sort.name = this.sortName;
            this.filter_data.filter.sort.rule = this.sortRuleOnDate;
        },

        async excelLoad() {
            try {
                const res = await axios({
                    method: 'post',
                    url: '/api/v2/users-export',
                    responseType: "blob",
                    data: {
                        type: 'xlsx',
                        filter: {
                            sort: {
                                name: this.sortName,
                                rule: this.sortRuleOnDate
                            }
                        }
                    }
                });

                let blob = new Blob([res.data], {
                    type: res.headers['content-type'],
                });

                let anchor = document.createElement('a');
                anchor.download = `StatisticExport(${ res.headers.date })`;
                anchor.href = (window.webkitURL || window.URL).createObjectURL(blob);
                anchor.dataset.downloadurl = [res.headers['content-type'], anchor.download, anchor.href].join(':');
                anchor.click();
            } catch (error) {
                console.log(error);
            }
        }
    }
}
</script>
