<template>
  <tr>
    <td>{{ community.id }}</td>
    <td>
      <a :href="community.chat_invite_link" target="_blank">
        {{ community.title }}
      </a>

    </td>
    <td>
      <transition>
        <router-link
            :to="{ name:'Profile', params: {id: community.owner_id} }"
        >
          {{ community.owner_name }}
        </router-link>
      </transition>
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
      {{ formatCash(community.balance) }}
    </td>
  </tr>
</template>

<script>
import formatDateTime from '../../mixins/formatDateTime'
import FormatCash from "../../mixins/formatCash";

export default {
  name: 'TableRow',
  mixins: [formatDateTime, FormatCash],

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

