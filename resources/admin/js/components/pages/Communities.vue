<template>
  <div class="card">
    <div class="card-body border-bottom py-3">
      <div class="d-flex align-items-center justify-content-between">
        <div class="text-muted">
          показать
          <div class="mx-2 d-inline-block">
            <input type="text" class="form-control" :value="this.filter_data.filter.entries" @input="changePageEntries" size="3">
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
          Поиск (ID, название, владелец):
          <div class="ms-2 d-inline-block">
            <input type="text" class="form-control" v-model="filter_data.filter.search" @input="" aria-label="поиск">
          </div>
        </div>
      </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table card-table table-vcenter text-nowrap datatable">
        <thead>
        <tr>
          <th>ID</th>
          <th>Название
          <i
              class="col-1"
              style="cursor: pointer;"
              @click="sortBy('title')"
          >
            <template v-if="sortRuleOnTitle == 'off'">
              <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
              </svg>
            </template>

            <template v-else-if="sortRuleOnTitle == 'asc'">
              <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#36344050"/>
              </svg>
            </template>

            <template v-else-if="sortRuleOnTitle == 'desc'">
              <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#36344050"/>
                <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
              </svg>
            </template>
          </i>
          </th>
          <th>Владелец</th>
          <th>Телеграм</th>
          <th>Дата подключения
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
          <th>Кол-во подписчиков
          <i
              class="col-1"
              style="cursor: pointer;"
              @click="sortBy('followers')"
          >
            <template v-if="sortRuleOnFollowers == 'off'">
              <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
              </svg>
            </template>

            <template v-else-if="sortRuleOnFollowers == 'asc'">
              <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#36344050"/>
              </svg>
            </template>

            <template v-else-if="sortRuleOnFollowers == 'desc'">
              <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#36344050"/>
                <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
              </svg>
            </template>
          </i>
          </th>
          <th>Сумма поступлений, руб
            <i
                class="col-1"
                style="cursor: pointer;"
                @click="sortBy('balance')"
            >
              <template v-if="sortRuleOnBalance == 'off'">
                <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                  <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                  <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
                </svg>
              </template>

              <template v-else-if="sortRuleOnBalance == 'asc'">
                <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                  <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#363440"/>
                  <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#36344050"/>
                </svg>
              </template>

              <template v-else-if="sortRuleOnBalance == 'desc'">
                <svg style="margin: 0;" width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon text-dark icon-thick">
                  <path d="M12.4654 21.5337C12.332 21.4003 12.1987 21.3337 11.9987 21.3337C11.7987 21.3337 11.6654 21.4003 11.532 21.5337L7.9987 25.067L4.46536 21.5337C4.1987 21.267 3.7987 21.267 3.53203 21.5337C3.26536 21.8003 3.26536 22.2003 3.53203 22.467L7.53203 26.467C7.7987 26.7337 8.1987 26.7337 8.46536 26.467L12.4654 22.467C12.732 22.2003 12.732 21.8003 12.4654 21.5337Z" fill="#36344050"/>
                  <path d="M12.4654 10.4663C12.332 10.5997 12.1987 10.6663 11.9987 10.6663C11.7987 10.6663 11.6654 10.5997 11.532 10.4663L7.9987 6.93301L4.46536 10.4663C4.1987 10.733 3.7987 10.733 3.53203 10.4663C3.26536 10.1997 3.26536 9.79967 3.53203 9.53301L7.53203 5.53301C7.7987 5.26634 8.1987 5.26634 8.46536 5.53301L12.4654 9.53301C12.732 9.79967 12.732 10.1997 12.4654 10.4663Z" fill="#363440"/>
                </svg>
              </template>
            </i></th>
          <th></th>
        </tr>
        </thead>
        <tbody v-if="communities">
        <community-table-row v-for="(community,index) in communities.data" :key="index" :community="community" />
        </tbody>
      </table>
      <div>
        <div v-if="communities.meta && communities.meta.per_page < communities.meta.total" class="card-footer d-flex align-items-center">
          <p class="m-0 text-muted">Показано <span>{{ communities.meta.per_page }}</span> из <span>{{ communities.meta.total }}</span> записей</p>
          <ul class="pagination m-0 ms-auto">
            <li
                v-for="(link, idx) in communities.meta.links"
                class="page-item"
                :class="{'active' : link.active}"
                :key="idx"
            >
              <a class="page-link" @click="setPageByUrl(link.url)" href="#">{{ link.label }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CommunityTableRow from "../common/CommunityTableRow.vue";
import Pagination from "../common/Pagination.vue";
import FilterDataCommunities from "../../mixins/filterData";
import Preloader from '../common/Preloader.vue'

export default {
  name: "Community",
  components: {Pagination, CommunityTableRow, Preloader},
  mixins: [FilterDataCommunities],

  data() {
    return {
      sortName: 'date',
      sortRules: {
        sortRuleOnDate: 'off',
        sortRuleOnTitle: 'off',
        sortRuleOnFollowers: 'off',
        sortRuleOnBalance: 'off',

      },
    }
  },

  watch: {
    filter_data: {
      deep: true,
      handler: _.debounce(function (v) {
        this.$store.dispatch('loadCommunities', v);
      }, 400)
    }
  },

    mounted(){
      this.$store.dispatch('loadCommunities', this.filter_data);
    },

  computed: {
    communities() {
      return this.$store.getters.getCommunities;
    },
    sortRuleOnDate: {
      get() {
        return this.sortRules.sortRuleOnDate;
      },
      set(value) {
        this.sortRules.sortRuleOnDate = value;
      }
    },
    sortRuleOnBalance: {
      get() {
        return this.sortRules.sortRuleOnBalance;
      },
      set(value) {
        this.sortRules.sortRuleOnBalance = value;
      }
    },
    sortRuleOnFollowers: {
      get() {
        return this.sortRules.sortRuleOnFollowers;
      },
      set(value) {
        this.sortRules.sortRuleOnFollowers = value;
      }
    },
    sortRuleOnTitle: {
      get() {
        return this.sortRules.sortRuleOnTitle;
      },
      set(value) {
        this.sortRules.sortRuleOnTitle = value;
      }
    },
  },

  methods: {

    changePageEntries(event) {
      this.filter_data.filter.entries = event.target.value;
      this.filter_data.filter.page = 1;
    },


    sortBy(name) {
      this.sortName = name;
      Object.keys(this.sortRules).forEach((rule) => rule == 'off');

      // костыляка на случай если будет не одна сортировка
      if (this.sortName == 'date') {
        switch (this.sortRuleOnDate) {
          case 'off': this.sortRuleOnDate = 'asc'; break;
          case 'asc': this.sortRuleOnDate = 'desc'; break;
          case 'desc': this.sortRuleOnDate = 'off'; break;
        }
        this.filter_data.filter.sort.rule = this.sortRuleOnDate;
      }

      if (this.sortName == 'balance') {
        switch (this.sortRuleOnBalance) {
          case 'off': this.sortRuleOnBalance = 'asc'; break;
          case 'asc': this.sortRuleOnBalance = 'desc'; break;
          case 'desc': this.sortRuleOnBalance = 'off'; break;
        }
        this.filter_data.filter.sort.rule = this.sortRuleOnBalance;
      }
      if (this.sortName == 'title') {
        switch (this.sortRuleOnTitle) {
          case 'off': this.sortRuleOnTitle = 'asc'; break;
          case 'asc': this.sortRuleOnTitle = 'desc'; break;
          case 'desc': this.sortRuleOnTitle = 'off'; break;
        }
        this.filter_data.filter.sort.rule = this.sortRuleOnTitle;
      }

      if (this.sortName == 'followers') {
        switch (this.sortRuleOnFollowers) {
          case 'off': this.sortRuleOnFollowers = 'asc'; break;
          case 'asc': this.sortRuleOnFollowers = 'desc'; break;
          case 'desc': this.sortRuleOnFollowers = 'off'; break;
        }
        this.filter_data.filter.sort.rule = this.sortRuleOnFollowers;
      }
      this.filter_data.filter.sort.name = this.sortName;
    },

    async excelLoad() {
      try {
        const res = await axios({
          method: 'post',
          url: '/api/v2/communities-export',
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
  },
}
</script>
