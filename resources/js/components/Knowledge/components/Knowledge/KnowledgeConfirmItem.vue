<template>
    <div class="knowledge-confirm-table__item">
        <div class="knowledge-confirm-table__question">
            {{ question.context }}
        </div>

        <div class="knowledge-confirm-table__action">
            <input type="checkbox" v-model="isPublic">
        </div>
    </div>
</template>

<script>
    export default {
        name: 'KnowledgeConfirmItem',

        props: {
            question: {
                type: Object,
                default: {}
            }
        },

        data() {
            return {
                isPublic: true,
            }
        },

        methods: {
            onChangeQuestionCheck(event) {
                if (event.target.value == true) {
                    console.log(true);
                } else {
                    console.log(false);
                }
            }
        }
    }
</script>
