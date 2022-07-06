<template>
    <div class="knowledge__multiple-operations">
        <button
            class="button-text button-text--danger knowledge__multiple-btn"
            @click="setOperationType('delete')"
        >
            Удалить
        </button>
        
        <button
            class="button-text button-text--primary knowledge__multiple-btn"
            @click="setOperationType('no_public')"
        >
            Снять с публикации
        </button>

        <button
            class="button-text button-text--primary knowledge__multiple-btn"
            @click="setOperationType('public')"
        >
            Опубликовать
        </button>
        
        <button
            class="button-text button-text--primary knowledge__multiple-btn"
            @click="setOperationType('draft')"
        >
            В черновик
        </button>
        
        <button
            class="button-text button-text--primary knowledge__multiple-btn"
            @click="setOperationType('no_draft')"
        >
            Не черновик
        </button>
    </div>
</template>

<script>
    export default {
        name: "KnowledgeMultipleOperations",

        methods: {
            setOperationType(type) {
                this.$emit('setOperationType', type);
            }
        }
    }
</script>
