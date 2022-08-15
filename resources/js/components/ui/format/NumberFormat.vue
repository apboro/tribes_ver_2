<template>
    <span>
        {{ formatedNumber }}
    </span>
</template>

<script>
    export default {
        name: 'NumberFormat',
        
        props: {
            value: {
                type: [Number, String],
                default: 0,
            },

            decimal: {
                type: Number,
                default: 2,
            }
        },

        computed: {
            formatedNumber() {
                /* if (typeof(this.value) == 'string') {
                    
                    this.value = this.value.match(/\d/g).join('');
                } */

                if (this.value === 0) {
                    return n.toFixed(this.decimal);
                }

                const notations = ["", "K", "M", "B", "T", "P", "E", "Z", "Y"];
                const id = Math.floor(Math.log(this.value) / Math.log(1000));

                if (id < 0) {
                    return this.value.toString();
                }

                return `${ parseFloat((this.value / Math.pow(1000, id)).toFixed(this.decimal)) }${ notations[id] }`;
            }
        }
    }
</script>
