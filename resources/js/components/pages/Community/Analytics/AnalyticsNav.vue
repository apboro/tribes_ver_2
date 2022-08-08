<template>
    <nav class="nav-analytics-community analytics-community__nav">
        <ul class="nav-analytics-community__list">
            <li
                class="nav-analytics-community__item"
                v-for="link in links"
                :key="link.tabName"
            >
                <button
                    class="link nav-analytics-community__link"
                    :class="{ 'active': link.tabName == visibleTab }"
                    @click="switchTab(link.tabName)"
                >
                    {{ link.text }}
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: 'AnalyticsNav',

        props: {
            links: {
                type: Array,
                default: () => [],
            },

            visibleTab: {
                type: String,
                default: '',
            }
        },

        methods: {
            switchTab(tabName) {
                this.$emit('switchTab', tabName);
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>