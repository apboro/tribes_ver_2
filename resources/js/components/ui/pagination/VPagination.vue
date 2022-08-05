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

            <span> из {{ paginateData.total }}</span>
            <!-- <span>Показано записей с {{ data.from }} по {{ data.to }} из {{ data.total }}</span> -->
        </div>

        <div class="pagination__item">
            <div class="pagination__control">
                <button      
                    class="button-text button-text--primary button-text--only-icon"
                    :class="{ 'button-text--disabled': isDisabledBackBtn }"
                    @click="onPageClick(activePage - 1)"
                >
                    <v-icon
                        name="arrow-left"
                        size="1"
                        class=""
                    />
                </button>
            </div>

            <div
                class="pagination__control"
                :class="{ 'active': isActive(pageNumber) }"
                v-for="pageNumber in pageCount"
                :key="pageNumber"
            >
                <button
                    class="pagination__page"
                    @click="onPageClick(pageNumber)"
                >
                    {{ pageNumber }}
                </button>

                <!-- <button
                    v-if="item.label == 'Назад'"       
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

                
                <button
                    v-else-if="item.label == 'Далее'"
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

                <button
                    v-else
                    class="pagination__page"
                    @click="onPageClick(item.page)"
                >
                    {{ item.label }}
                </button> -->
            </div>

            <div class="pagination__control">
                <button      
                    class="button-text button-text--primary button-text--only-icon"
                    :class="{ 'button-text--disabled': isDisabledNextBtn }"
                    @click="onPageClick(activePage + 1)"
                >
                    <v-icon
                        name="arrow-right"
                        size="1"
                        class=""
                    />
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import VSelect from "../form/VSelect";
    import VIcon from "../icon/VIcon";

    export default {
        name: 'VPagination',

        components: { VSelect, VIcon },

        props: {
            paginateData: {
                type: Object,
                default: {}
            },

            selectOptions: {
                type: Array,
                default: []
            },
        },

        data() {
            return {
                activePage: 1,
            }
        },

        computed: {
            pageCount() {
                return Math.ceil(this.paginateData.total / Number(this.paginateData.per_page));
            },

            isDisabledBackBtn() {
                return this.activePage <= 1;
            },

            isDisabledNextBtn() {
                return this.paginateData.current_page == this.pageCount;
            }
        },

        methods: {
            onChangePerPage(value) {
                this.$emit('onChangePerPage', value);
            },

            onPageClick(value) {
                this.activePage = value;
                this.$emit('onPageClick', value);
            },

            isActive(pageNumber) {
                return pageNumber == this.paginateData.current_page;
            },

            
        },
    }
</script>
