<template>
    <div class="multiple-operations-knowledge knowledge__multiple-operations">
        <button
            class="button-text multiple-operations-knowledge__btn button-text--danger"
            @click="setOperationType('delete')"
        >
            Удалить
        </button>
        
        <button
            class="button-text multiple-operations-knowledge__btn button-text--primary"
            @click="setOperationType('no_public')"
        >
            Снять с публикации
        </button>

        <button
            class="button-text multiple-operations-knowledge__btn button-text--primary"
            @click="setOperationType('public')"
        >
            Опубликовать
        </button>
        
        <button
            class="button-text multiple-operations-knowledge__btn button-text--primary"
            @click="setOperationType('draft')"
        >
            В черновик
        </button>
        
        <button
            class="button-text multiple-operations-knowledge__btn button-text--primary"
            @click="setOperationType('no_draft')"
        >
            Не черновик
        </button>
    </div>
</template>

<script>
    export default {
        name: "MultipleOperations",

        methods: {
            setOperationType(type) {
                this.$emit('setOperationType', type);
            }
        }
    }
</script>
