<template>
    <div class="progress-bar">
        <span
            class="progress-bar__progress"
            :style="{ width: `${ value }%` }"
        ></span>
    </div>
</template>

<script>
    export default {
        name: 'VProgress',

        props: {
            value: {
                type: [String, Number],
                default: 50
            }
        }
    }
</script>
