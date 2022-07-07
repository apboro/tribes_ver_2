<template>
    <div class="pagination">
        <div class="pagination__item pagination__description">
            <span>
                Кол-во отображаемых строк:
            </span>

            <v-select
                class="pagination__select"
                :options="[{ label: 15, value: 15 }, { label: 30, value: 30 }, { label: 45, value: 45 }]"
                :defaultValue="15"
            />

            <!-- <select
                class="select pagination__select"
                @change="onChangePerPage($event.target.value)"
            >
                <option value="15" selected>15</option>
                <option value="30">30</option>
                <option value="45">45</option>
            </select> -->
            <span> из {{ data.total }}</span>
            <!-- <span>Показано записей с {{ data.from }} по {{ data.to }} из {{ data.total }}</span> -->
        </div>

        <div class="pagination__item">
            <div
                class="pagination__page"
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
    import VSelect from "./VSelect";

    export default {
        name: 'KnowledgePagination',

        components: { VSelect },

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
