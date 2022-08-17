<template>
    <nav class="nav-community">
        <ul class="nav-community__list">
            <li
                class="nav-community__item"
                v-for="(link, index) in links"
                :key="index"
            >
                <a
                    href=""
                    class="nav-community__link"
                    :class="{ 'active': link.active }"
                >
                    {{ link.text }}
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: 'TabsNav',

        props: {
            // links: {
            //     type: Array,
            //     default: () => [],
            // }
        },

        data() {
            return {
                links: [
                    {
                        text: 'Аналитика',
                        href: '',
                        active: true,
                    },

                    {
                        text: 'Донаты',
                        href: ''
                    },

                    {
                        text: 'Тарифы',
                        href: ''
                    },

                    {
                        text: 'База знаний',
                        href: ''
                    },
                ],
            }
        }
    }
</script>
