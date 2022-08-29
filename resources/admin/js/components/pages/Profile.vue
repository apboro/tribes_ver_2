<template>
<div>
    <div>
        <div class="col-md-12 d-flex justify-content-between pb-5">
            <div class="d-flex align-items-center col-md-3">
                <span class="avatar avatar-xl avatar-rounded">JL</span>
                <div class="d-inline-block m-3">
                    <h3 class="m-0 mb-1"><a href="#">{{ user.name ?? 'Без имени' }}</a></h3>
                    <div class="text-muted">{{ user.created_at ?? 'Дата создания'}}</div>
                    <div>{{ user.email ?? 'email' }}</div>
                    <div>{{ user.phone ?? 'phone' }}</div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <h1>ПЛАТЕЖИ</h1>
            </div>
            <div class="col-md-3 text-center">
                <h1>МЕДИАТОВАРЫ</h1>
            </div>
        </div>
        <div class="text-center">
            <h1>СООБЩЕСТВА</h1>
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

</style>