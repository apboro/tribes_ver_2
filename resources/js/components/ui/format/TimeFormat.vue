<template>
    <span>
        {{ time }}
    </span>
</template>

<script>
    import { timeFormatting } from '../../../core/functions';
    
    export default {
        name: 'TimeFormat',

        props: {
            value: {
                type: String,
                default: ''
            },

            typeValue: {
                type: String,
                default: 'date&time'
            },

            format: {
                type: String,
                default: 'short'
            }
        },

        data() {
            return {
                time: '',
            }
        },

        methods: {
            toFormat() {
                if (this.typeValue == 'date') {
                    this.time = timeFormatting({
                        date: this.value,
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric',
                    });
                } else if (this.typeValue == 'date&time') {
                    this.time = timeFormatting({
                        date: this.value,
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                    });
                } else if (this.typeValue == 'time') {
                    this.time = timeFormatting({
                        date: this.value,
                        hour: 'numeric',
                        minute: 'numeric',
                    });
                }
            },
        },

        mounted() {
            this.toFormat();
        }
    }
</script>
