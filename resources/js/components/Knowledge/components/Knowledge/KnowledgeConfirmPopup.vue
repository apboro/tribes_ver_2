<template>
    <v-popup
        @close="closeConfirmPopup"
    >
        <template #title>
            <h2 class="v-popup__title">Требуется подтверждение</h2>
        </template>

        <template #body>
            <div class="knowledge-confirm-table">
                <div class="knowledge-confirm-table__header">
                    <h3 class="knowledge-confirm-table__title">
                        Выбранные черновики
                    </h3>
                </div>

                <div class="knowledge-confirm-table__body">
                    <div
                        class="knowledge-confirm-table__item"
                        v-for="question in questions"
                        :key="question.id"    
                    >
                        <div class="knowledge-confirm-table__question">
                            {{ question.context }}
                        </div>

                        <div class="knowledge-confirm-table__action">
                            <input
                                type="checkbox"
                                :value="question.id"
                                v-model="ids"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                class="v-popup__footer-btn"
                @click="closeConfirmPopup"
            >
                Cancel
            </button>
            
            <button
                class="v-popup__footer-btn"
                @click="confirm"
            >
                Submit
            </button>
        </template>
    </v-popup>
</template>

<script>
    import VPopup from '../VPopup.vue';
    
    export default {
        name: 'KnowledgeConfirmPopup',

        components: {
            VPopup
        },

        props: {
            questions: {
                type: Array,
                default: []
            }
        },

        data() {
            return {
                ids: this.questionsIds(),
            }
        },

        methods: {
            closeConfirmPopup() {
                this.$emit('closeConfirmPopup');
            },

            confirm() {
                // передаем ид которые должны остаться в статусе черновика
                let arr = this.questionsIds();
                const result = arr.filter(el => !this.ids.includes(el));
                this.$emit('confirm', result);
            },
            
            questionsIds() {
                let arr = [];
                this.questions.forEach((question) => {
                    arr.push(question.id);
                });
                return arr;
            }
        },
    }
</script>
