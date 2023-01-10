<template>
  <div class="card">
    <div class="card-body border-bottom py-3">
      <div class="d-flex align-items-center justify-content-between">
          <div class="ms-auto text-muted">
            Статус:
            <div class="ms-2 d-inline-block">
                <select class="form-select" v-model="filter" @change="filteredMessages" aria-label="filter">
                  <option selected>Все статусы</option>
                  <option>Новый</option>
                  <option>Отвечен</option>
                  <option>Закрыт</option>
                </select>
            </div>
          </div>
        <div class="ms-auto text-muted">
          Поиск по ФИО:
          <div class="ms-2 d-inline-block">
            <input type="text" class="form-control" v-model="search" @input="searchMessages" aria-label="поиск">
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
        <tbody v-if="messages">
        <feedback-table-row v-for="(message,index) in filtered_messages" :key="index" :message="message" />
        </tbody>
      </table>
      <div>
      <Pagination :pagination="pagination" @pagination="(page, per_page) => load(page, per_page)"/>
      </div>
    </div>
  </div>
</template>

<script>
import FeedbackTableRow from "../common/FeedbackTableRow.vue";
import Pagination from "../common/Pagination.vue";

export default {
  name: "Feedback",
  components: {Pagination, FeedbackTableRow },

  data() {
    return{
      messages: null,
      filtered_messages: null,
      filter: 'Все статусы',
      search: null,
      pagination: null,
    }
  },

  mounted() {
    this.load();
  },

  computed: {
  },

  methods: {
    load(page = 1, per_page = 10){
      axios.get(`/api/v2/feedback/list?page=${page}&per_page=${per_page}`).then((response)=>{
        this.messages = response.data.data;
        this.filtered_messages = response.data.data;
        this.pagination = response.data;
      });
    },
    filteredMessages(){
      this.filtered_messages = this.messages.filter(message => message.status === this.filter);
      if (this.filter === 'Все статусы')
      {
        this.filtered_messages = this.messages;
      }
    },
    searchMessages(){
      this.filtered_messages = this.messages.filter(message => message.name.match(this.search))
    }
  }
}
</script>
