<template>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-muted">
                    показать
                    <div class="mx-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" size="3">
                        
                    </div>
                    на странице
                </div>
                <div class="ms-auto text-muted">
                    Поиск:
                    <div class="ms-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" aria-label="поиск">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                <tr>
                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                    <th>Search</th>
                    <th>Date</th>
                    <th>Sorting user</th>
                    <th>Sorting date</th>
                    <th>Entries</th>
                    <th>Page</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>


                <tr>
                    <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                    <td><span class="text-muted"></span></td>
                    <td><span class="flag flag-country-pl">Date</span></td>
                    <td>Sorting user</td>
                    <td>Sorting date</td>
                    <td>Entries</td>
                    <td>Page</td>
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
    </div>
</template>

<script>
export default {
    name: "Payments",
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
                console.log(v)
                this.$store.dispatch('LOAD_PAYMENTS', v);
                console.log(this.$store)
            },400)
        }
    },
    mounted(){
        this.$store.dispatch('LOAD_PAYMENTS', this.filter_data).then(() => {
        });
    },
    computed: {
        payments() {
            console.log(this.$store.getters.payments.data)
            return this.$store.getters.payments;
        }
    },
    methods:{
        // setPageByUrl(url){
        //     if(url){
        //         this.filter_data.page = getParameterByName('page', url);
        //     }
        // }
    }
}
</script>

<style scoped>

</style>