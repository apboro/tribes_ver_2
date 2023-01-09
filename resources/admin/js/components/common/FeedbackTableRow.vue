<template>
  <tr>
    <td>{{ message.id }}</td>
    <td>
      {{ message.name }}
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
    <td v-if="message.status !== 'Закрыт'" class ="text-end">
      <button type="button" class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown" aria-expanded="false">
        Действия
      </button>
      <ul class="dropdown-menu dropdown-menu-end">

        <li>
          <button @click.prevent="view(message)" class="dropdown-item">Ответить</button>
        </li>

        <li>
          <button @click.prevent="close" class="dropdown-item">Закрыть обращение</button>
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
    view(message){
      this.$router.push({ name: 'Answer', params: {message} })
    }

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

