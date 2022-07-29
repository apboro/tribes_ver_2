<template>
    <transition name="a-table-row">
        <div
            class="table__row"
            v-if="isVisibleHiddenRow"
        >
            <div class="table__item table__full">
                <div class="table__full">
                    <p class="table__full-title">
                        Вопрос
                    </p>
                    <p>{{ data.context }}</p>
                </div>

                <div
                    v-if="data.answer.context"
                    class="table__full table__full--openable"
                    :class="{ 'hide': isLongAnswer }"
                    ref="hiddenRow"
                >
                    <p class="table__full-title">
                        Ответ
                    </p>
                    
                    <template v-if="data.answer.context">
                        <div
                            class="table__openable-block"
                            v-html="data.answer.context"
                        ></div>
                        
                        <template v-if="isVisibleFullAnswerBtn">
                            <button
                                class="button-text table__open-openable-btn button-text--primary"
                                @click="toggleFullAnswerVisibility"
                            >
                                {{ isLongAnswer ? 'Читать полностью' : 'Скрыть ответ' }}
                            </button>
                        </template>
                    </template>
                    
                    <template v-else>
                        <p></p>
                    </template>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        name: 'HiddenRow',

        props: {
            data: {
                type: Object,
                default: () => {},
            },

            isVisibleHiddenRow: {
                type: Boolean,
                default: false,
            },
        },

        data() {
            return {
                isLongAnswer: false,
                isVisibleFullAnswerBtn: false,
            }
        },

        watch: {
            isVisibleHiddenRow() {
                this.$nextTick(() => {
                    if (this.$refs.hiddenRow) {
                        if (this.$refs.hiddenRow.getBoundingClientRect().height > 100) {
                            this.isLongAnswer = true;
                            this.isVisibleFullAnswerBtn = true;
                        }
                    }
                })
            }
        },

        methods: {
            toggleFullAnswerVisibility() {
                this.isLongAnswer = !this.isLongAnswer;
            },
        },
    }
</script>
