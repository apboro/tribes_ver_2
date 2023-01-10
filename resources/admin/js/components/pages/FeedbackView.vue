<template>
  <div v-if="message">
    <div class="card">
      <h2 class="card-header">
        Вопрос клиента:
      </h2>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><b>ID обращения:</b> {{ message.id }}</li>
        <li class="list-group-item"><b>Имя клиента:</b> {{ message.name }}</li>
        <li class="list-group-item"><b>Статус обращения:</b> {{ message.status }}</li>
        <li class="list-group-item"><b>Текст обращения:</b> {{ message.text }}</li>
      </ul>
    </div>

    <div class="card">
      <h2 class="card-header">
        Ваш ответ:
      </h2>
      <div class="card-body">
        <textarea required cols="60" rows="5" class="card-text" v-model="answer" :disabled="message.status !== 'Новый'"></textarea>
        <div>
          <button class="btn btn-primary" v-if="message.status === 'Новый'" @click.prevent="send_answer">Ответить
          </button>
          <button class="btn btn-primary" @click.prevent="goBack">Назад</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "FeedbackView",

  data() {
    return {
      message: null,
      answer: null,
    }
  },

  mounted() {
    axios.get('/api/v2/feedback/' + this.$route.params.id).then((response) => {
      this.message = response.data;
      this.answer = response.data.answer;
    });
  },

  computed: {},

  methods: {
    send_answer() {
      if(confirm("Отправить сообщение?")) {
        axios.post('/api/v2/feedback/answer', {
          message: this.answer,
          id: this.message.id
        }).then(this.$router.push({name: 'feedback'}));
      }
    },

    goBack() {
      this.$router.push({name: 'feedback'});
    }
  }
}
</script>
