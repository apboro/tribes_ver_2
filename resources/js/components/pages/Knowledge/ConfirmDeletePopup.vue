<template>
    <v-popup
        theme="danger"
        :confirmOptions="{ type: 'delete' }"
        :isVisiblePopup="isVisibleConfirmDeletePopup"
        @close="closeConfirmDeletePopup"
    >
        <template #body>
            <p class="knowledge-confirm__text">
                Вы уверены, что хотите удалить вопрос-ответ?
            </p>

            <p class="knowledge-confirm__text">
                Это действие нельзя будет отменить.
            </p>
        </template>

        <template #footer>
            <button
                class="button-empty button-empty--primary"
                @click="closeConfirmDeletePopup"
            >
                Отмена
            </button>
            
            <button
                class="button-filled button-filled--danger"
                @click="confirm"
            >
                Удалить
            </button>
        </template>
    </v-popup>
</template>

<script>
    import VPopup from '../../ui/popup/VPopup.vue';
    
    export default {
        name: 'ConfirmDeletePopup',

        components: {
            VPopup
        },

        props: {
            isVisibleConfirmDeletePopup: {
                type: Boolean,
                default: false,
            }
        },

        methods: {
            closeConfirmDeletePopup() {
                this.$emit('closeConfirmDeletePopup');
            },

            confirm() {
                this.$emit('confirm');
            },
        },
    }
</script>
