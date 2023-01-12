<template>
  <div class="card">
    <div class="card-body border-bottom py-3">
      <div class="d-flex align-items-center justify-content-between">
        <div class="ms-auto text-muted">
          Статус:
          <div class="ms-2 d-inline-block">
            <select class="form-select" v-model="filter" @change="filteredCommunities" aria-label="filter">
              <option selected>Все статусы</option>
              <option>Новый</option>
              <option>Отвечен</option>
              <option>Закрыт</option>
            </select>
          </div>
        </div>
        <div class="ms-auto text-muted">
          Поиск по названию:
          <div class="ms-2 d-inline-block">
            <input type="text" class="form-control" v-model="search" @input="searchCommunities" aria-label="поиск">
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table card-table table-vcenter text-nowrap datatable">
        <thead>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Владелец</th>
          <th>Телеграм</th>
          <th>Дата подключения</th>
          <th>Кол-во подписчиков</th>
          <th>Сумма поступлений, руб</th>
          <th></th>
        </tr>
        </thead>
        <tbody v-if="communities">
        <community-table-row v-for="(community,index) in filtered_communities" :key="index" :community="community" />
        </tbody>
      </table>
      <div>
        <Pagination :pagination="pagination" @pagination="(page, per_page) => load(page, per_page)"/>
      </div>
    </div>
  </div>
</template>

<script>
import CommunityTableRow from "../common/CommunityTableRow.vue";
import Pagination from "../common/Pagination.vue";

export default {
  name: "Feedback",
  components: {Pagination, CommunityTableRow},

  data() {
    return{
      communities: null,
      filtered_communities: null,
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
      axios.post(`/api/v2/communities`) //communities/list?page=${page}&per_page=${per_page}`)
       .then((response)=>{
        this.communities = response.data.data;
        this.filtered_communities = response.data.data;
        this.pagination = response.data;
      });
    },
    filteredCommunities(){
      this.filtered_communities = this.communities.filter(community => community.status === this.filter);
      if (this.filter === 'Все статусы')
      {
        this.filtered_communities = this.communities;
      }
    },
    searchCommunities(){
      this.filtered_communities = this.communities.filter(community => community.name.toUpperCase().match(this.search.toUpperCase()))
    }
  }
}
</script>
