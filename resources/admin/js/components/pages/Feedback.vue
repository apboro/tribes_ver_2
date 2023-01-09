<template>
  <div class="card">
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
        <feedback-table-row v-for="message in messages" :key="message.id" :message="message" />
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import FeedbackTableRow from "../common/FeedbackTableRow.vue";

export default {
  name: "Feedback",
  components: { FeedbackTableRow },

  data() {
    return{
      messages: null,
    }
  },

  mounted() {
    axios.get('/api/v2/feedback/list').then((response)=>{
      this.messages = response.data;
      // console.log(this.messages);
    });
  },

  computed: {
  },

  methods: {
  }
}
</script>
