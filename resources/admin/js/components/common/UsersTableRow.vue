<template>
    <tr>
        <td><span class="text-muted">{{ user.id }}</span></td>
        <td>
            <transition>
                <router-link 
                    :to="{ name:'Profile', params: {id: user.id} }"
                >
                  <p style="background-color: red;"
                     v-if="user.is_blocked">заблокирован</p>
                    {{ user.name }}
                </router-link>
            </transition>
        </td>
      <td>
        <a :href="'https://t.me/'+user.telegram" target="_blank">
        {{ user.telegram }}
        </a>
      </td>
        <td>
            <a :href="`mailto:${ user.email }`">
                {{ user.email }}
            </a>
        </td>
        <td :title="user.phone_confirmed ? 'Подтвержден' : 'Не подтвержден'">
            {{ user.phone }}
            <svg  v-if="user.phone_confirmed" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>
        </td>
        <td>
            {{ date }}
        </td>
      <td>
        {{user.community_owner_num}}
      </td>
      <td>{{formatDateTime(user.updated_at)}}</td>
      <td>{{ user.payins }}</td>
      <td>{{ user.payouts }}</td>
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
              <li>
                <button v-if="!user.is_blocked" @click.prevent="blockUser(user.id)" class="dropdown-item">Заблокировать пользователя</button>
                <button v-if="user.is_blocked" @click.prevent="un_blockUser(user.id)" class="dropdown-item">Разблокировать пользователя</button>
              </li>
              <li>
                <button @click.prevent="send_new_password(user.id)" class="dropdown-item">Выслать пароль</button>
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

          blockUser(user_id){
            axios.post('/api/v2/user/block', {id: user_id}).
            then(()=>alert('Пользователь заблокирован'))
                .then(()=>window.location.reload());
          },

          send_new_password(user_id){
            axios.post('/api/v2/user/sendNewPassword', {id: user_id}).
            then(()=>alert('Новый пароль отправлен'))
          },


          un_blockUser(user_id){
            axios.post('/api/v2/user/unblock', {id: user_id}).
            then(()=>alert('Пользователь разблокирован'))
                .then(()=>window.location.reload());
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
