<template>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-muted">
                    показать
                    <div class="mx-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" :value="filter_data.filter.entries" @input="changePage" size="3">
                    </div>
                    на странице
                </div>
                <div class="ms-auto text-muted">
                    Поиск:
                    <div class="ms-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" v-model="filter_data.filter.search" aria-label="поиск">
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
                    <th>Телефон</th>
                    <th>Создан</th>
                    <th>Комиссия</th>
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

    watch: {
        filter_data: {
            deep: true,
            handler: _.debounce(function(v) {
                this.$store.dispatch('get_users', v);
            },400)
        }
    },
    
    async mounted(){
        await this.$store.dispatch('get_users', this.filter_data).then(() => {
        });
    },

    computed: {
        users() {
            return this.$store.getters.users;
        }
    },
    methods: {
        changePage(event) {
            this.filter_data.filter.entries = event.target.value;
            this.filter_data.filter.page = 1;
        },
    }
}
</script>
