<template>
    <div class="knowledge__auxiliary">
        <a
            :href="metaData.how_it_works_link"
            target="_blank"
            class="link knowledge__auxiliary-link"
        >
            Как это работает?
        </a>

        <a
            :href="metaData.public_list_link"
            target="_blank"
            class="link knowledge__auxiliary-link"
        >
            Посмотреть как пользователь
        </a>

        <button
            class="link knowledge__auxiliary-link"
            @click="copyLink"
        >
            Копировать ссылку
        </button>
    </div>
</template>

<script>
    import { copyText } from '../../../../core/functions';
    
    export default {
        name: 'KnowledgeAuxiliary',
        
        props: {
            metaData: {
                type: Object,
                default: {}
            }
        },

        methods: {
            copyLink() {
                copyText(this.metaData.public_list_link);
            },
        }
    }
</script>
