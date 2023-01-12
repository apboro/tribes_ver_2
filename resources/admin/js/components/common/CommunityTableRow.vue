<template>
  <tr>
    <td>{{ community.id }}</td>
    <td>
      <transition>
        <router-link
            :to="{ name:'Profile', params: {id: community.user_id} }"
        >
          {{ community.title }}
        </router-link>
      </transition>
    </td>
    <td>
      {{ community.owner }}
    </td>
    <td>
      {{ community.telegram }}
    </td>
    <td>
      {{ date }}
    </td>
    <td>
      {{ community.followers }}
    </td>
    <td>
      {{ community.balance }}
    </td>
  </tr>
</template>

<script>
import formatDateTime from '../../mixins/formatDateTime'

export default {
  name: 'TableRow',
  mixins: [formatDateTime],

  props: {
    community: {}
  },

  data() {
    return {}
  },

  computed: {
    date() {
      return this.formatDateTime(this.community.created_at);
    }
  },

  methods: {
    close(){
      if(confirm("Закрыть обращение?")) {
        axios.post('/api/v2/feedback/close/' + this.community.id).then(this.community.status = 'Закрыт');
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

