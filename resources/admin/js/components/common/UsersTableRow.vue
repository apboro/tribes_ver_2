<template>
    <tr>
        <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
        <td><span class="text-muted">{{ user.id }}</span></td>
        <td>
            <transition>
                <router-link 
                    :to="{ name:'Profile', params: {id: user.id} }"
                >
                    {{ user.name }}
                </router-link>
            </transition>
        </td>
        <td :title="user.phone_confirmed ? 'Подтвержден' : 'Не подтвержден'">
            {{ user.phone }}
            <svg  v-if="user.phone_confirmed" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>
        </td>
        <td>
            {{ val }}
        </td>
        <td>
            <editable-value
                :isEditMode="isEditComissionMode"
                :value="user.commission"
            />
        </td>
        <td class="text-end">
            <button type="button" class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown" aria-expanded="false">
                Действия
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <button @click.prevent="loginAs(user.id)" class="dropdown-item">Войти от этого пользователя</button>
                </li>

                <li>
                    <button
                        v-if="!isEditComissionMode"
                        class="dropdown-item"
                        @click="toggleEditComissionMode"
                    >
                        Изменить процент
                    </button>
                </li>
            </ul>
        </td>
    </tr>
</template>

<script>
    //import FormatDateTime from '../../mixins/formatDateTime'
    import EditableValue from '../common/EditableValue.vue';

    export default {
        name: 'TableRow',
        components: { EditableValue },

        props: {
            user: {
                type: Object,
                default: () => {}
            }
        },

        data() {
            return {
                isEditComissionMode: false,
            }
        },

        computed: {
            val() {
                return this.formatDateTime(this.user.created_at);
            }
        },

        methods: {
            loginAs(userId){
                return new Promise((resolve, reject) => {
                    axios({url: '/api/login-as', data: {'id' : userId}, method: 'POST' })
                        .then(resp => {
                            window.location.href = '/';
                            resolve(resp);
                        })
                        .catch(err => {
                            console.log('Err');
                            reject(err);
                        })
                })
            },

            changePage(event) {
                this.filter_data.entries = event.target.value;
                this.filter_data.page = 1;
            },

            formatDateTime(str){
                let date = new Date(str);
                return `${date.toLocaleDateString('ru')} ${date.toLocaleTimeString('ru')}`;
            },

            toggleEditComissionMode() {
                this.isEditComissionMode = !this.isEditComissionMode;
            }
        }
    }
</script>
