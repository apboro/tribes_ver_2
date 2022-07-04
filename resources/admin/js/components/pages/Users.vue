<template>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-muted">
                    показать
                    <div class="mx-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" v-model="filter_data.entries" size="3">
                    </div>
                    на странице
                </div>
                <div class="ms-auto text-muted">
                    Поиск:
                    <div class="ms-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" v-model="filter_data.search" aria-label="поиск">
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
                    <th>Invoice Subject</th>
                    <th>Имя</th>
                    <th>Телефон</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users.data">
                    <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                    <td><span class="text-muted">{{ user.id }}</span></td>
                    <td><a href="invoice.html" class="text-reset" tabindex="-1">Icons</a></td>
                    <td>
                        <span class="flag flag-country-pl"></span>
                        {{ user.name }}
                    </td>
                    <td :title="user.phone_confirmed ? 'Подтвержден' : 'Не подтвержден'">
                        {{ user.phone }}
                        <svg  v-if="user.phone_confirmed" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>
                    </td>
                    <td>
                        {{ user.created_at }}
                    </td>
                    <td>
                        <span class="badge bg-success me-1"></span> Paid Today
                    </td>
                    <td>$940</td>
                    <td class="text-end">
                            <span class="dropdown">
                              <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Actions</button>
                              <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">
                                  Action
                                </a>
                                <a class="dropdown-item" href="#">
                                  Another action
                                </a>
                              </div>
                            </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-if="users.per_page < users.total" class="card-footer d-flex align-items-center">
            <p class="m-0 text-muted">Показано <span>{{ users.per_page }}</span> из <span>{{ users.total }}</span> записей</p>
            <ul class="pagination m-0 ms-auto">
                <li v-for="link in users.links" class="page-item" :class="{'active' : link.active}"><a class="page-link" @click="setPageByUrl(link.url)" href="#">{{ link.label }}</a></li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "Users",
    data() {
        return {
            filter_data:{
                search : null,
                entries : 10,
                page : 1,
            }
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
    mounted(){
        this.$store.dispatch('get_users', this.filter_data).then(() => {
        });
    },
    computed: {
        users() {
            return this.$store.getters.users;
        }
    },
    methods:{
        setPageByUrl(url){
            if(url){
                this.filter_data.page = getParameterByName('page', url);
            }
        }
    }
}
</script>

<style scoped>

</style>