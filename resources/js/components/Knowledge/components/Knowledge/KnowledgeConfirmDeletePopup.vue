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
                Егоfdfdfdfdстатус будет изменен на «Опубликовано», и вопрос-ответ <b>будет виден вашим пользователям.</b>
            </p>
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
        name: 'KnowledgeConfirmDeletePopup',

        components: {
            VPopup
        },

        methods: {
            closeConfirmDraftPopup() {
                this.$emit('closeConfirmDraftPopup');
            },

            confirm() {
                this.$emit('confirm', result);
            },
            
            
        },
    }
</script>
