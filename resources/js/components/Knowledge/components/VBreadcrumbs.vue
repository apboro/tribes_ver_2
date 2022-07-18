<template>
    <nav class="breadcrumbs">
        <ul class="breadcrumbs__list">
            <li
                class="breadcrumbs__item"
                v-for="(link, index) in links"
                :key="index"
            >   
                <template v-if="isLastLink(index)">
                    <span
                        class="breadcrumbs__link--last"
                    >
                        {{ link.text }}
                    </span>
                </template>

                <template v-else>
                    <a
                        class="link breadcrumbs__link"
                        :href="link.href"
                    >
                        {{ link.text }}
                    </a>
                </template>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: 'VBreadcrumbs',
        
        props: {
            links: {
                type: Array,
                default: [],
            }
        },

        methods: {
            isLastLink(index) {
                return index == this.links.length - 1 ? true : false;
            }
        }
    }
</script>
