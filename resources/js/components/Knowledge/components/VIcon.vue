<template>
    <i class="icon">
        <template v-if="name == 'search'">
            <svg :width="width" :height="height" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__stroke" d="M14.4121 14.4121L20 20" stroke="#B5B4B8" stroke-width="2" stroke-linecap="round"/>
                <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd" d="M10 16C13.3137 16 16 13.3137 16 10C16 6.68629 13.3137 4 10 4C6.68629 4 4 6.68629 4 10C4 13.3137 6.68629 16 10 16Z" stroke="#B5B4B8" stroke-width="2"/>
            </svg>
        </template>

        <template v-if="name == 'image'">
            <svg :width="width" :height="height" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd" d="M50.6668 5.33334L13.3335 5.33334C8.80016 5.33334 5.3335 8.80001 5.3335 13.3333L5.3335 50.6667C5.3335 55.2 8.80016 58.6667 13.3335 58.6667H50.6668C55.2002 58.6667 58.6668 55.2 58.6668 50.6667V13.3333C58.6668 8.80001 55.2002 5.33334 50.6668 5.33334ZM10.6668 13.3333C10.6668 11.7333 11.7335 10.6667 13.3335 10.6667H50.6668C52.2668 10.6667 53.3335 11.7333 53.3335 13.3333V33.6L44.5335 24.8C43.4668 23.7333 41.8668 23.7333 40.8002 24.8L12.5335 53.0667C11.4668 52.8 10.6668 51.7333 10.6668 50.6667V13.3333ZM19.7335 53.3333H50.6668C52.2668 53.3333 53.3335 52.2667 53.3335 50.6667V41.0667L42.6668 30.4L19.7335 53.3333ZM22.6668 29.3333C26.4002 29.3333 29.3335 26.4 29.3335 22.6667C29.3335 18.9333 26.4002 16 22.6668 16C18.9335 16 16.0002 18.9333 16.0002 22.6667C16.0002 26.4 18.9335 29.3333 22.6668 29.3333ZM24.0002 22.6667C24.0002 21.8667 23.4668 21.3333 22.6668 21.3333C21.8668 21.3333 21.3335 21.8667 21.3335 22.6667C21.3335 23.4667 21.8668 24 22.6668 24C23.4668 24 24.0002 23.4667 24.0002 22.6667Z" fill="black"/>
            </svg>
        </template>

        <template v-if="name == 'video'">
            <svg :width="width" :height="height" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd" d="M52.7998 2.66669H11.1998C6.39984 2.66669 2.6665 6.40002 2.6665 11.2V52.8C2.6665 57.6 6.39984 61.3334 11.1998 61.3334H52.7998C57.5998 61.3334 61.3332 57.6 61.3332 52.8V11.2C61.3332 6.40002 57.5998 2.66669 52.7998 2.66669ZM47.9998 21.3334H55.9998V29.3334H47.9998V21.3334ZM21.3332 29.3334H42.6665V8.00002H21.3332V29.3334ZM15.9998 29.3334H7.99984V21.3334H15.9998V29.3334ZM15.9998 34.6667H7.99984V42.6667H15.9998V34.6667ZM21.3332 34.6667H42.6665V56H21.3332V34.6667ZM55.9998 34.6667H47.9998V42.6667H55.9998V34.6667ZM55.9998 11.2V16H47.9998V8.00002H52.7998C54.6665 8.00002 55.9998 9.33335 55.9998 11.2ZM15.9998 8.00002H11.1998C9.33317 8.00002 7.99984 9.33335 7.99984 11.2V16H15.9998V8.00002ZM7.99984 52.8V48H15.9998V56H11.1998C9.33317 56 7.99984 54.6667 7.99984 52.8ZM47.9998 56H52.7998C54.6665 56 55.9998 54.6667 55.9998 52.8V48H47.9998V56Z" fill="black"/>
            </svg>
        </template>
        
        <template v-if="name == 'right-arrow'">
            <svg :width="width" :height="height" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__stroke" d="M1 8L4 4.5L1 1" stroke="#7B7B7B" stroke-linecap="round"/>
            </svg>
        </template>
    </i>
</template>

<script>
    export default {
        name: 'VIcon',

        props: {
            name: {
                type: String,
            },
            size: {
                type: String,
                default: '1'
            },
            sizeParams: {
                type: Object,
                default: null
            }
        },

        data() {
            return {
                width: 0,
                height: 0,
            }
        },

        methods: {
            getSize() {
                if (this.sizeParams) {
                    this.width = this.sizeParams.width;
                    this.height = this.sizeParams.height;
                } else {
                    switch (this.size) {
                        case "1":
                            this.width = 16;
                            this.height = 16;
                            break;
                        case "2":
                            this.width = 24;
                            this.height = 24;
                            break;
                        case "3":
                            this.width = 76;
                            this.height = 76;
                            break;
                    }
                }

            }
        },

        mounted() {
            this.getSize();
        }
    }
</script>
