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
        <div class="ms-auto text-muted">
        Статус
        <div class="mx-2 d-inline-block">
          <select class="form-control" v-model="filter_data.filter.status">
            <option>Все статусы</option>
            <option>Новый</option>
            <option>Отвечен</option>
            <option>Закрыт</option>
          </select>
        </div>
        </div>

        <div class="ms-auto text-muted">
          Поиск (ID, имя, email):
          <div class="ms-2 d-inline-block">
            <input type="text" class="form-control" v-model="filter_data.filter.search" @input="" aria-label="поиск">
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table card-table table-vcenter text-nowrap datatable">
        <thead>
        <tr>
          <th>ID</th>
          <th>Имя</th>
          <th>Почта</th>
          <th>Телефон</th>
          <th>Сообщение</th>
          <th>Создан</th>
          <th>Статус</th>
          <th></th>
        </tr>
        </thead>
        <tbody v-if="feedbacks">
        <feedback-table-row v-for="(message, index) in feedbacks.data" :key="index" :message="message"/>
        </tbody>
      </table>
      <div>
        <div
             class="card-footer d-flex align-items-center">
          <ul class="pagination m-0 ms-auto">
            <li
                v-for="(link, idx) in feedbacks.links"
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
import FeedbackTableRow from "../common/FeedbackTableRow.vue";
import Pagination from "../common/Pagination.vue";
import FilterDataFeedbacks from "../../mixins/filterData";


export default {
  name: "Feedback",
  components: {Pagination, FeedbackTableRow},
  mixins: [FilterDataFeedbacks],

  data() {
    return {
    }
  },

  watch: {
    filter_data: {
      deep: true,
      handler: _.debounce(function (v) {
        this.$store.dispatch('loadFeedbackList', v);
      }, 400)
    }
  },

  mounted() {
    this.$store.dispatch('loadFeedbackList', this.filter_data);
  },

  computed: {
    feedbacks() {
      return this.$store.getters.getFeedbacks;
    },
  },

  methods: {

    changePageEntries(event) {
      this.filter_data.filter.entries = event.target.value;
      this.filter_data.filter.page = 1;
    },

  }
}
</script>
