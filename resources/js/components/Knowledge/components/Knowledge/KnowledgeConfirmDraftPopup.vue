<template>
    <v-popup
        theme="primary"
        :confirmOptions="{ type: 'info' }"
        @close="closeConfirmDraftPopup"
    >
        <template #body>
            <p class="knowledge-confirm__text">
                Среди  выбранных вами вопросов есть <b>черновик.</b> 
            </p>

            <p class="knowledge-confirm__text">
                Его статус будет изменен на «Опубликовано», и вопрос-ответ <b>будет виден вашим пользователям.</b>
            </p>

            <div class="knowledge-confirm__table">
                <div class="knowledge-confirm__table-header">
                    <h3 class="knowledge-confirm__table-header-title">
                        Выбранные черновики
                    </h3>
                </div>

                <div class="knowledge-confirm__table-body">
                    <div
                        class="knowledge-confirm__table-item"
                        v-for="question in questions"
                        :key="question.id"    
                    >
                        <label
                            :for="`draft_${ question.id }`"
                            class="knowledge-confirm__question"
                        >
                            {{ question.context }}
                        </label>

                        <div class="knowledge-confirm__action">
                            <div class="checkbox">
                                <input
                                    type="checkbox"
                                    :id="`draft_${ question.id }`"
                                    class="checkbox__input"
                                    :value="question.id"
                                    v-model="ids"
                                >

                                <label
                                    :for="`draft_${ question.id }`"
                                    class="checkbox__label"
                                ></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                class="button-empty button-empty--primary"
                @click="closeConfirmDraftPopup"
            >
                Отмена
            </button>
            
            <button
                class="button-filled button-filled--primary"
                @click="confirm"
            >
                Опубликовать
            </button>
        </template>
    </v-popup>
</template>

<script>
    import VPopup from '../VPopup.vue';
    
    export default {
        name: 'KnowledgeConfirmDraftPopup',

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
            closeConfirmDraftPopup() {
                this.$emit('closeConfirmDraftPopup');
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
