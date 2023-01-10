<template>
  <tr>
    <td>{{ message.id }}</td>
    <td>
      <transition>
        <router-link
            :to="{ name:'Profile', params: {id: message.user_id} }"
        >
          {{ message.name }}
        </router-link>
      </transition>
    </td>
    <td>
      {{ message.email }}
    </td>
    <td>
      {{ message.phone }}
    </td>
    <td>
      <div class="short_text">
      {{ message.text }}
      </div>
    </td>
    <td>
      {{ date }}
    </td>
    <td>
      {{ message.status }}
    </td>
    <td>
      <button type="button" class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown" aria-expanded="false">
        Действия
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li v-if="message.status === 'Новый'">
          <button @click.prevent="answer(message.id)" class="dropdown-item">Ответить</button>
        </li>
        <li v-if="message.status === 'Новый'">
          <button @click.prevent="close" class="dropdown-item">Закрыть обращение</button>
        </li>
        <li v-if="message.status !== 'Новый'">
          <button @click.prevent="answer(message.id)" class="dropdown-item">Просмотреть</button>
        </li>
      </ul>
    </td>
  </tr>
</template>

<script>
import formatDateTime from '../../mixins/formatDateTime'

export default {
  name: 'TableRow',
  mixins: [formatDateTime],

  props: {
    message: {}
  },

  data() {
    return {}
  },

  computed: {
    date() {
      return this.formatDateTime(this.message.created_at);
    }
  },

  methods: {
    close(){
      if(confirm("Закрыть обращение?")) {
        axios.post('/api/v2/feedback/close/' + this.message.id).then(this.message.status = 'Закрыт');
      }
    },

    answer(message_id){
      this.$router.push({ name: 'answer', params: {id: message_id} })
    },
  }
}
</script>

<style>
.short_text {
  white-space: nowrap;
  overflow: hidden;
  width: 150px;
  text-overflow: ellipsis;
}
</style>

