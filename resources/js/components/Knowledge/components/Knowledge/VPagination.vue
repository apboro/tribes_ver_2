<template>
    <div class="v-pagination">
        <div class="v-pagination__item">
            <label for="">
                Кол-во отображаемых строк:
            </label>

            <select
                class="v-pagination__select"
                style="max-width: 45px"
                @change="onChangePerPage($event.target.value)"
            >
                <option value="15" selected>15</option>
                <option value="30">30</option>
                <option value="45">45</option>
            </select>
            <span>Показано записей с {{ data.from }} по {{ data.to }} из {{ data.total }}</span>
        </div>

        <div class="v-pagination__item">
            <div
                class="v-pagination__page"
                :class="{ 'active': page === data.current_page }"
                v-for="page in data.last_page"
                :key="page"
                @click="onPageClick(page)"
            >
                {{ page }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'KnowledgePagination',

        props: {
            data: {
                type: Object,
                default: {}
            },
        },

        data() {
            return {}
        },

        methods: {
            onChangePerPage(value) {
                this.$emit('onChangePerPage', value);
            },

            onPageClick(value) {
                this.$emit('onPageClick', value);
            },
        },
    }
</script>
