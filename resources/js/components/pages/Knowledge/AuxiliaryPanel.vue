<template>
    <div class="auxiliary-knowledge">
        <a
            :href="metaData.how_it_works_link"
            target="_blank"
            class="link auxiliary-knowledge__link"
        >
            Как это работает?
        </a>

        <a
            :href="metaData.public_list_link"
            target="_blank"
            class="link auxiliary-knowledge__link"
        >
            Посмотреть как пользователь
        </a>

        <button
            class="link auxiliary-knowledge__link"
            @click="copyLink"
        >
            Копировать ссылку
        </button>
    </div>
</template>

<script>
    import { copyText } from '../../../core/functions';
    
    export default {
        name: 'AuxiliaryPanel',
        
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
