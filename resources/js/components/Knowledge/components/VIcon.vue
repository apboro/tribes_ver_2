<template>
    <i class="icon">
        <template v-if="name == 'search'">
            <svg :width="width" :height="height" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__stroke" d="M14.4121 14.4121L20 20" stroke="#B5B4B8" stroke-width="2" stroke-linecap="round"/>
                <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd" d="M10 16C13.3137 16 16 13.3137 16 10C16 6.68629 13.3137 4 10 4C6.68629 4 4 6.68629 4 10C4 13.3137 6.68629 16 10 16Z" stroke="#B5B4B8" stroke-width="2"/>
            </svg>
        </template>

        <template v-if="name == 'sort-asc'">
            <svg :width="width" :height="height" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line class="icon__stroke" x1="1" y1="-1" x2="3" y2="-1" transform="matrix(1 8.74228e-08 8.74228e-08 -1 2 12)" stroke="#4C4957" stroke-width="2" stroke-linecap="round"/>
                <line class="icon__stroke" x1="1" y1="-1" x2="7" y2="-1" transform="matrix(1 8.74228e-08 8.74228e-08 -1 2 8)" stroke="#4C4957" stroke-width="2" stroke-linecap="round"/>
                <line class="icon__stroke" x1="1" y1="-1" x2="11" y2="-1" transform="matrix(1 8.74228e-08 8.74228e-08 -1 2 4)" stroke="#4C4957" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </template>

        <template v-if="name == 'sort-desc'">
            <svg :width="width" :height="height" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line class="icon__stroke" x1="3" y1="4" x2="5" y2="4" stroke="#4C4957" stroke-width="2" stroke-linecap="round"/>
                <line class="icon__stroke" x1="3" y1="8" x2="9" y2="8" stroke="#4C4957" stroke-width="2" stroke-linecap="round"/>
                <line class="icon__stroke" x1="3" y1="12" x2="13" y2="12" stroke="#4C4957" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </template>

        <template v-if="name == 'vertical-dots'">
            <svg :width="width" :height="height" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd" d="M7.66667 3.83333C8.58333 3.83333 9.33333 3.08333 9.33333 2.16667C9.33333 1.25 8.58333 0.5 7.66667 0.5C6.75 0.5 6 1.25 6 2.16667C6 3.08333 6.75 3.83333 7.66667 3.83333ZM9.33333 8C9.33333 8.91667 8.58333 9.66667 7.66667 9.66667C6.75 9.66667 6 8.91667 6 8C6 7.08333 6.75 6.33333 7.66667 6.33333C8.58333 6.33333 9.33333 7.08333 9.33333 8ZM6 13.8333C6 12.9167 6.75 12.1667 7.66667 12.1667C8.58333 12.1667 9.33333 12.9167 9.33333 13.8333C9.33333 14.75 8.58333 15.5 7.66667 15.5C6.75 15.5 6 14.75 6 13.8333Z" fill="#4C4957"/>
            </svg>
        </template>

        <template v-if="name == 'arrow-down'">
            <svg :width="width" :height="height" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__fill" d="M3.53317 5.53317C3.6665 5.39984 3.79984 5.33317 3.99984 5.33317C4.19984 5.33317 4.33317 5.39984 4.4665 5.53317L7.99984 9.0665L11.5332 5.53317C11.7998 5.2665 12.1998 5.2665 12.4665 5.53317C12.7332 5.79984 12.7332 6.19984 12.4665 6.4665L8.4665 10.4665C8.19984 10.7332 7.79984 10.7332 7.53317 10.4665L3.53317 6.4665C3.2665 6.19984 3.2665 5.79984 3.53317 5.53317Z" fill="#4C4957"/>
            </svg>
        </template>

        <template v-if="name == 'arrow-up'">
            <svg :width="width" :height="height" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="icon__fill" d="M3.53317 10.4668C3.6665 10.6002 3.79984 10.6668 3.99984 10.6668C4.19984 10.6668 4.33317 10.6002 4.4665 10.4668L7.99984 6.9335L11.5332 10.4668C11.7998 10.7335 12.1998 10.7335 12.4665 10.4668C12.7332 10.2002 12.7332 9.80016 12.4665 9.5335L8.4665 5.5335C8.19984 5.26683 7.79984 5.26683 7.53317 5.5335L3.53317 9.5335C3.2665 9.80016 3.2665 10.2002 3.53317 10.4668Z" fill="#7367F0"/>
            </svg>
        </template>
        
        <template v-if="name == 'spinner-primary'">
            <svg :width="width" :height="height" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.9995 32.1709C15.1418 32.1709 12.3509 31.3069 9.99311 29.6923C7.63526 28.0778 5.82064 25.7881 4.78739 23.1238C3.75415 20.4595 3.55056 17.545 4.20335 14.7629C4.85613 11.9808 6.3348 9.46107 8.44527 7.53438C10.5557 5.6077 13.1994 4.3641 16.0293 3.9668C18.8592 3.5695 21.7431 4.03706 24.3025 5.30811C26.862 6.57917 28.9773 8.59433 30.3709 11.0891C31.7646 13.5839 32.3714 16.4418 32.1117 19.2876L35.925 19.6355C35.986 18.9678 36.0093 18.2995 35.9958 17.6337C35.936 14.6953 35.1571 11.8052 33.7139 9.2217C31.9436 6.05278 29.2567 3.49309 26.0057 1.87858C22.7547 0.264069 19.0915 -0.32983 15.4969 0.17483C11.9024 0.679489 8.54433 2.25913 5.86358 4.70642C3.18283 7.15372 1.30461 10.3543 0.475433 13.8882C-0.353745 17.422 -0.0951471 21.124 1.2173 24.5083C2.52974 27.8925 4.8347 30.8009 7.82966 32.8518C10.2714 34.5237 13.0787 35.562 15.9995 35.8886C16.6613 35.9626 17.329 36 17.9995 36L17.9995 32.1709Z" fill="url(#paint0_linear_168_941)"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.9995 32.1709C15.1418 32.1709 12.3509 31.3069 9.99311 29.6923C7.63526 28.0778 5.82064 25.7881 4.78739 23.1238C3.75415 20.4595 3.55056 17.545 4.20335 14.7629C4.85613 11.9808 6.3348 9.46107 8.44527 7.53438C10.5557 5.6077 13.1994 4.3641 16.0293 3.9668C18.8592 3.5695 21.7431 4.03706 24.3025 5.30811C26.862 6.57917 28.9773 8.59433 30.3709 11.0891C31.7646 13.5839 32.3714 16.4418 32.1117 19.2876L35.925 19.6355C35.986 18.9678 36.0093 18.2995 35.9958 17.6337C35.936 14.6953 35.1571 11.8052 33.7139 9.2217C31.9436 6.05278 29.2567 3.49309 26.0057 1.87858C22.7547 0.264069 19.0915 -0.32983 15.4969 0.17483C11.9024 0.679489 8.54433 2.25913 5.86358 4.70642C3.18283 7.15372 1.30461 10.3543 0.475433 13.8882C-0.353745 17.422 -0.0951471 21.124 1.2173 24.5083C2.52974 27.8925 4.8347 30.8009 7.82966 32.8518C10.2714 34.5237 13.0787 35.562 15.9995 35.8886C16.6613 35.9626 17.329 36 17.9995 36L17.9995 32.1709Z" fill="url(#paint1_linear_168_941)"/>
                <defs>
                    <linearGradient id="paint0_linear_168_941" x1="26.3995" y1="16" x2="18.3995" y2="30.8" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#7367F0"/>
                        <stop offset="0.0001" stop-color="#766AF0" stop-opacity="0.986207"/>
                        <stop offset="0.722123" stop-color="#766AF0" stop-opacity="0.378504"/>
                        <stop offset="1" stop-color="#766AF0" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="paint1_linear_168_941" x1="-3.20051" y1="14" x2="18.3995" y2="16.8" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#7367F0"/>
                        <stop offset="0.0001" stop-color="#766AF0" stop-opacity="0.69"/>
                        <stop offset="0.453611" stop-color="#766AF0" stop-opacity="0.378504"/>
                        <stop offset="1" stop-color="#766AF0" stop-opacity="0"/>
                    </linearGradient>
                </defs>
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
