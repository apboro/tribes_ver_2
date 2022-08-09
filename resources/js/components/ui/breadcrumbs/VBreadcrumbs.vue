<template>
    <nav class="breadcrumbs">
        <ul class="breadcrumbs__list">
            <li
                class="breadcrumbs__item"
                v-for="(link, index) in links"
                :key="index"
            >   
                <span
                    v-if="isLastLink(index)"
                    class="breadcrumbs__link--last"
                >
                    {{ link.text }}
                </span>

                <a
                    v-else
                    class="link breadcrumbs__link"
                    :href="link.href"
                >
                    {{ link.text }}
                </a>
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
