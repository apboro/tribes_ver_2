<template>
    <div class="breadcrumbs">
        <ul class="breadcrumbs__list">
            <li
                class="breadcrumbs__item"
                v-for="(link, index) in links"
                :key="index"
            >
                <a
                    class="breadcrumbs__link"
                    :class="{ 'breadcrumbs__link--last': isLastLink(index) }"
                    :href="link.href"
                >
                    {{ link.text }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    import VIcon from './VIcon.vue';

    export default {
        name: 'VBreadcrumbs',

        components: { VIcon },
        
        props: {
            links: {
                type: Array,
            }
        },

        methods: {
            isLastLink(index) {
                return index == this.links.length - 1 ? true : false;
            }
        }
    }
</script>
