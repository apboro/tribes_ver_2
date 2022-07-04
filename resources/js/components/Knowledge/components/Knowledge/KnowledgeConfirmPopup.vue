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
                @click="cancelConfirm"
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
                ids: this.questionsIds()
            }
        },

        methods: {
            closeConfirmPopup() {
                this.$emit('closeConfirmPopup');
            },

            onChangeQuestionCheck(event) {
                console.log(event.target.value);
            },

            cancelConfirm() {
                this.$emit('closeConfirmPopup');
            },

            confirm() {
                let arr = [];
                
                this.questions.forEach((question) => {
                    arr.push(question.id);
                });
                const result = arr.filter(el => !this.ids.includes(el));
                console.log(result);
                
               
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

        mounted() {
            this.questions
        }
    }
</script>
