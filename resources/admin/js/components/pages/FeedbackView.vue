<template>
  <div class="card">
    <div v-if="message" class="card-body border-bottom py-3">
      <p>Message ID: {{message.id}}</p>
      <p>Name: {{message.name}}</p>
      <div>Message: {{message.text}}</div>
    </div>
    <label>Ответ:</label>
    <textarea cols="50" rows="5" v-model="answer"></textarea>
    <button @click.prevent="send_answer">Ответить</button>

  </div>
</template>

<script>

export default {
  name: "FeedbackView",

  data() {
    return{
      message: null,
      answer: null,
    }
  },

  mounted() {
    this.message = this.$route.params.message;
  },

  computed: {
  },

  methods: {
    send_answer(){
      axios.post('/api/v2/feedback/answer', {message: this.answer, id: this.message.id});//.then(this.$router.push({name:'feedback'}));
    }
  }
}
</script>
