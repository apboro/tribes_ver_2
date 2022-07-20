<template>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex justify-content-end mb-3">
                <button class="btn" @click="reset">Сбросить фильтр</button>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    показать
                    <div class="mx-2 d-inline-block">
                        <input type="text" class="form-control form-control-sm" v-model="filter_data.entries" size="3">
                    </div>
                    на странице
                </div>
                
                <div class="d-flex">
                    <input type="date" class="form-control form-control-sm" id="datepicker-default"  v-model="filter_data.date"/>
                </div>

                <div class="text-muted">
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
                    <th class="w-1">ID Платежа
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="6 15 12 9 18 15"></polyline></svg>
                    </th>
                    <th>Дата платежа</th>
                    <th>Пользователь</th>
                    <th>Тип платежа</th>
                    <th>Сообщество</th>
                    <th>Статус</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                    <tr v-for="payment in payments.data" :key="payment.OrderId">
                        <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                        <td><span class="text-muted">{{ payment.OrderId }}</span></td>
                        <td>{{formatDateTime(payment.created_at)}}</td>
                        <td>{{payment.from}}</td>
                        <td>{{translatePaymentType(payment.type)}}</td>
                        <td>{{payment.community}}</td>
                        <td>{{translateStatus(payment.status)}}</td>
                        <!-- <span>{{(payment.status)}}</span> -->
                        <!-- </td> -->
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

import FilterDataPayments from '../../mixins/filterData'
import Translations from '../../mixins/translations'

export default {
    name: "Payments",
    mixins: [FilterDataPayments, Translations],

    watch: {
        filter_data: {
            deep: true,
            handler: _.debounce(function(v) {
                this.$store.dispatch('LOAD_PAYMENTS', v);
            },400)
        }
    },

    mounted(){
        this.$store.dispatch('LOAD_PAYMENTS', this.filter_data).then(() => {
        });
    },

    computed: {
        payments() {
            return this.$store.getters.GET_PAYMENTS;
        }
    },

    methods:{
        formatDateTime(str){
            let date = new Date(str);
            return `${date.toLocaleDateString('ru')} ${date.toLocaleTimeString('ru')}`;
        },
        formatCash(num){
            return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(num)
        },
    }
}
</script>

<style scoped>

</style>