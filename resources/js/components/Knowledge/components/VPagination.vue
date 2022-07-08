<template>
    <div class="pagination">
        <div class="pagination__item pagination__description">
            <span>
                Кол-во отображаемых строк:
            </span>

            <v-select
                class="pagination__select"
                :options="selectOptions"
                defaultValue="15"
                @getSelectedValue="onChangePerPage"
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
                class="pagination__control"
                :class="{ 'active': item.active }"
                v-for="(item, i) in paginateData"
                :key="i"
            >
                <template v-if="item.label == 'Назад'">
                    <button
                        class="button-text button-text--primary button-text--only-icon"
                        :class="{ 'button-text--disabled': item.disabled }"
                        @click="onPageClick(item.page)"
                    >
                        <v-icon
                            name="arrow-left"
                            size="1"
                            class=""
                        />
                    </button>
                </template>

                <template v-else-if="item.label == 'Далее'">
                    <button
                        class="button-text button-text--primary button-text--only-icon"
                        :class="{ 'button-text--disabled': item.disabled }"
                        @click="onPageClick(item.page)"
                    >
                        <v-icon
                            name="arrow-right"
                            size="1"
                            class="pagination__btn"
                        />
                    </button>
                </template>

                <template v-else>
                    <button
                        class="pagination__page"
                        @click="onPageClick(item.page)"
                    >
                        {{ item.label }}
                    </button>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import VSelect from "./VSelect";
    import VIcon from "./VIcon";

    export default {
        name: 'KnowledgePagination',

        components: { VSelect, VIcon },

        props: {
            paginateData: {
                type: Array,
                default: []
            },

            selectOptions: {
                type: Array,
                default: []
            },
        },

        data() {
            return {
                data: []
            }
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
