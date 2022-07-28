<template>
    <transition name="a-table-row">
        <div
            class="table__row"
            v-if="isVisibleHideSection"
        >
            <div class="table__item table__full">
                <div class="table__full">
                    <p
                        v-if="data.openable.titleMain"
                        class="table__full-title"
                    >
                        {{ data.openable.titleMain }}
                    </p>
                    <p>{{ data.openable.mainText }}</p>
                </div>

                <div
                    v-if="data.openable.hiddenContent"
                    class="table__full table__full--openable"
                    :class="{ 'hide': isLongAnswer }"
                    ref="hiddenRow"
                >
                    <p
                        v-if="data.openable.titleContent"
                        class="table__full-title"
                    >
                        {{ data.openable.titleContent }}
                    </p>
                    
                    <template v-if="data.openable.hiddenContent">
                        <div
                            v-if="data.openable.hiddenContentType == 'editor'"
                            class="table__openable-block"
                            v-html="data.openable.hiddenContent"
                        ></div>
                        
                        <div
                            v-else-if="data.openable.hiddenContentType == 'text'"
                            class="table__openable-block"
                        >
                            {{ data.openable.hiddenContent }}
                        </div>
                        
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

            isVisibleHideSection: {
                type: Boolean,
                default: false,
            },

            isVisibleFullAnswerBtn: {
                type: Boolean,
                default: false,
            },

            isLongAnswer: {
                type: Boolean,
                default: false,
            }
        },

        methods: {
            toggleFullAnswerVisibility() {
                this.isLongAnswer = !this.isLongAnswer;
            },
        }
    }
</script>
