<template>
    <div class="progress-analytics-community">
        <ul class="progress-analytics-community__list">
            <li
                class="progress-analytics-community__item"
                v-for="(progressItem, index) in progressItems"
                :key="index"
            >
                <span class="progress-analytics-community__title">
                    {{ progressItem.text }}
                </span>

                <span class="progress-analytics-community__value">
                    {{ progressItem.value }}%
                </span>

                <v-progress :value="progressItem.value" />
            </li>
        </ul>
    </div>
</template>

<script>
    import VProgress from '../../../ui/progress/VProgress.vue';

    export default {
        name: 'ProgressList',

        components: { VProgress },

        props: {
            progressItems: {
                type: Array,
                default: () => [],
            }
        }
    }
</script>
