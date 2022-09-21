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
            {{ date }}
        </td>
        <td>
            <editable-value
                :isEditMode="isEditCommissionMode"
                :value="user.commission"
                @switchEditMode="switchEditMode"
                @edit="editCommission"
            />
            
            <span v-if="isCommissionError">
                {{ commissionErrorText }}
            </span>
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
                        v-if="!isEditCommissionMode"
                        class="dropdown-item"
                        @click="toggleEditCommissionMode"
                    >
                        Изменить процент
                    </button>
                </li>
            </ul>
        </td>
    </tr>
</template>

<script>
    import formatDateTime from '../../mixins/formatDateTime'
    import EditableValue from '../common/EditableValue.vue';

    export default {
        name: 'TableRow',
        components: { EditableValue },
        mixins: [formatDateTime],

        props: {
            user: {
                type: Object,
                default: () => {}
            }
        },

        data() {
            return {
                isEditCommissionMode: false,
                isCommissionError: false,
                commissionErrorText: '',
            }
        },

        computed: {
            date() {
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

            toggleEditCommissionMode() {
                this.isEditCommissionMode = !this.isEditCommissionMode;
            },

            switchEditMode(bool) {
                this.isCommissionError = false;
                this.isEditCommissionMode = bool;
            },

            async editCommission(value) {
                try {
                    await axios({
                        method: 'POST',
                        url: '/api/v2/user/commission',
                        data: {
                            id: this.user.id,
                            percent: value
                        }
                    });
                    
                    this.isCommissionError = false;
                    this.switchEditMode(false);
                    this.user.commission = value;
                } catch (error) {
                    this.isCommissionError = true;
                    this.commissionErrorText = error.response.data.errors.percent[0];
                }
            }
        }
    }
</script>
