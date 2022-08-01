<template>
    <button
        @click="click"
        :class="className"
    >
        <slot>
            {{ text }}
        </slot>
    </button>
</template>

<script>

    export default {
        name: "v-button",
        props: {

            text: {
                required: false,
                type: String,
                default: '',
            },

            //theme: primary || success || danger
            theme: {
                type: String,
                default: 'primary'
            },

            //type: filled || empty || text
            type: {
                type: String,
                default: 'filled',
            },

            disabled: {
                required: false,
                type: Boolean,
                default: false,
            },

            icon: {
                required: false,
                type: Boolean,
                default: false,
            }
        },

        computed: {
            className() {
                return `button-${ this.type } button-${ this.type }--${ this.theme } ${ this.getDisabled } ${this.getIcon}`.trim()
            },

            getDisabled() {
                return this.disabled ? `button-${ this.type }--disabled` : '';
            },

            getIcon() {
                return this.icon ? `button-${ this.type }--only-icon` : '';
            }
        },

        methods: {
            click() {
                this.$emit('click')
            },
        },
    }
</script>