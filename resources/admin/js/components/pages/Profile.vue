<template>
<div>
    <div style="display: flex; justify-content: center;">
        <div class="col-md-12">
            <div class="user_info">
                <span class="avatar avatar-xl avatar-rounded">JL</span>
                <div class="user_info__data">
                    <h3 class="m-0 mb-1"><a href="#">{{ user.name ?? 'Без имени' }}</a></h3>
                    <div class="text-muted">{{ user.created_at ?? 'Дата создания'}}</div>
                    <div>{{ user.email ?? 'email' }}</div>
                    <div>{{ user.phone ?? 'phone' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import FilterDataUsers from '../../mixins/filterData';
import Preloader from '../common/Preloader.vue';
export default {
    name: "Profile",
    mixins: [FilterDataUsers],
    components: {Preloader},
    data() {
        return {
            user:{}
        }
    },

    created() {
        this.$store.dispatch('get_user', this.$route.params.id).then( resp => {
            this.user = resp.data;
        });
    },

}
</script>

<style lang="scss" scoped>
    .user_info {
        display: flex;
        align-items: center;

        &__data {
            margin: 0 0 0 20px;
            display: inline-block;
        }
    }
</style>