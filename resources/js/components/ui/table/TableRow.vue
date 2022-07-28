<template>
    <div
        class="table__row-wrapper"
        :class="{ 'open': isVisibleHideSection }"
    >
        <!-- Строка -->
        <div class="table__row" :class="{ 'active': isAddedField }">
            <!-- Выделить -->
            <div class="table__item" v-for="(col, index) in row" :key="index">
                <template v-if="col.type == 'multiple'">
                    <v-checkbox
                        :id="`field_${ data.id }`"
                        :value="col.getValue(data.id)"
                        :modelValue="col.getValue(data.id)"
                        @change="changeMultiple(col.setValue, $event, data.id)"
                    />
                </template>

                <template v-else-if="col.type == 'openable'">
                    <div
                        class="table__item table__item--openable table__item--changable"
                        @click="toggleQuestion"
                    >
                        <p class="table__item-truncate-text">{{ data.openable.mainText }}</p>
                        
                        <transition name="a-question-arrow" mode="out-in">
                            <template v-if="isVisibleHideSection">
                                <v-icon
                                    class="table__item-arrow-icon"
                                    key="0"
                                    name="arrow-up"
                                    size="1"
                                />
                            </template>

                            <template v-else>
                                <v-icon
                                    class="table__item-arrow-icon"
                                    key="1"
                                    name="arrow-down"
                                    size="1"
                                />
                            </template>
                        </transition>
                    </div>
                </template>

                <template v-else-if="col.type == 'time'">
                    <time-format
                        :value="data.created_at"
                        typeValue="date"
                    />
                </template>
            </div>


        </div>

        <template v-if="data.openable">
            <hidden-row
                :data="data"
                :isVisibleHideSection="isVisibleHideSection"
                :isVisibleFullAnswerBtn="isVisibleFullAnswerBtn"
                :isLongAnswer="isLongAnswer"
            />
        </template>
    </div>
</template>

<script>
    import VCheckbox from"../form/VCheckbox.vue";
    import TimeFormat from '../format/TimeFormat.vue';
    import VIcon from "../icon/VIcon.vue";
    import HiddenRow from "./HiddenRow.vue";

    export default {
        name: 'TableRow',
        
        components: {
            VCheckbox,
            VIcon,
            HiddenRow,
            TimeFormat,
        },

        props: {
            data: {
                type: Object,
                default: {},
            },

            row: {
                type: Array,
                default: () => [],
            },
        },

        data() {
            return {
                isVisibleHideSection: false,
                isAddedField: false,
                isLongAnswer: false,
                isVisibleFullAnswerBtn: false,
            }
        },

        methods: {
            toggleQuestion() {
                this.isVisibleHideSection = !this.isVisibleHideSection;
                
                this.$nextTick(() => {
                    if (this.$refs.hiddenRow) {
                        if (this.$refs.answer.getBoundingClientRect().height > 100) {
                            this.isLongAnswer = true;
                            this.isVisibleFullAnswerBtn = true;
                        }
                    }
                })
            },

            changeMultiple(change, value, id) {
                this.isAddedField = value;
                change(value, id);
            },
        },
    }
</script>
