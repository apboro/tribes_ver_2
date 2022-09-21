<template>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    показать
                    <div class="mx-2 d-inline-block">
                        <input type="text" class="form-control" :value="this.filter_data.filter.entries" @input="changePage" size="3">
                    </div>
                    на странице
                </div>
                
                <div class="d-flex">
                    <input type="date" class="form-control" id="datepicker-default"  v-model="filter_data.filter.date"/>
                </div>

                <div class="">
                    <select 
                        type="text" 
                        class="form-select" 
                        placeholder="Выбрать пользователя" 
                        id="select-users" 
                        value=""
                        v-model="customer_data.select"
                    >
                        <option 
                            v-for="customer in customers" 
                            :key="customer.id"
                        >
                            {{customer.name}}
                        </option>
                    </select>
                </div>

                <div class="text-muted">
                    Поиск:
                    <div class="ms-2 d-inline-block">
                        <input type="text" class="form-control" v-model="filter_data.filter.search" aria-label="поиск"/>
                    </div>
                </div>
                <button class="btn" @click="reset">Сбросить фильтр</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                <tr>
                    <th class="w-1">ID Платежа</th>
                    <th>Дата платежа</th>
                    <th>Пользователь</th>
                    <th>Тип платежа</th>
                    <th>Сообщество</th>
                    <th>Статус</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>

                    <tr v-for="payment in payments.data" :key="payment.OrderId">
                        <td><span class="text-muted">{{ payment.OrderId }}</span></td>
                        <td>{{formatDateTime(payment.created_at)}}</td>
                        <td>{{payment.from ?? 'ФИО пользователя'}}</td>
                        <td>{{translatePaymentType(payment.type)}}</td>
                        <td>{{payment.community}}</td>
                        <td>{{translateStatus(payment.status)}}</td>
                        <td>{{formatCash(payment.add_balance)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="payments.meta && payments.meta.per_page < payments.meta.total" class="card-footer d-flex align-items-center">
            <p class="m-0 text-muted">Показано <span>{{ payments.meta.per_page }}</span> из <span>{{ payments.meta.total }}</span> записей</p>
            <ul class="pagination m-0 ms-auto">
                <li 
                    v-for="(link, idx) in payments.meta.links"
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

import Preloader from '../common/Preloader.vue'
import FilterDataPayments from '../../mixins/filterData'
import Translations from '../../mixins/translations'
import FormatDateTime from '../../mixins/formatDateTime'
import FormatCash from '../../mixins/formatCash'

export default {
    name: "Payments",
    mixins: [ FilterDataPayments, Translations, FormatDateTime, FormatCash ],
    components: { Preloader },

    watch: {
        filter_data: {
            deep: true,
            handler: _.debounce(function(v) {
                this.$store.dispatch('loadPayments', v);
            },400)
        },

        customer_data: {
            deep: true,
            handler: _.debounce(function(v) {
                this.$store.dispatch('loadUniqUsersPayments', v);
            },400)
        }
    },

    mounted(){
        this.$store.dispatch('loadPayments', this.filter_data);
        this.$store.dispatch('loadUniqUsersPayments', this.customer_data);
    },

    computed: {
        payments() {
            return this.$store.getters.getPayments;
        },

        customers() {
            return this.$store.getters.customersHasName;
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

<style scoped>
select.form-select {
    max-width: 200px;
}
option {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

</style>