<template>
    <div class="course">
        <perfect-scrollbar class="parts__scrl">
            <div class="course__parts">
                <div v-for="(lesson, index) in course.lessons" :key="lesson.id">
                    <button
                        class="button-outline button-outline--success course__part"
                        v-if="lesson.id !== 0"
                        v-bind:class="{ 'active' : lesson.active}"
                        @click="showLesson(lesson.id)"
                    >
                        Часть {{ index + 1 }}
                    </button>
                </div>
                
                <button class="button-outline button-outline--light course__add-btn" @click="newLesson()">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 7.5C15 8.0625 14.625 8.4375 14.0625 8.4375H8.4375V14.0625C8.4375 14.625 8.0625 15 7.5 15C6.9375 15 6.5625 14.625 6.5625 14.0625V8.4375H0.9375C0.375 8.4375 0 8.0625 0 7.5C0 6.9375 0.375 6.5625 0.9375 6.5625H6.5625V0.9375C6.5625 0.375 6.9375 0 7.5 0C8.0625 0 8.4375 0.375 8.4375 0.9375V6.5625H14.0625C14.625 6.5625 15 6.9375 15 7.5Z" fill="#28C76F"/></svg>
                </button>
            </div>
        </perfect-scrollbar>

        <perfect-scrollbar class="content__scrl">
            <div class="course__container" >
                <Lesson
                    ref="lesson"
                    v-for="lesson in $store.getters.course.lessons"
                    :key="lesson.index"
                    :id="lesson.id"
                    :lesson="lesson"
                    v-show='lesson.active'
                >
                </Lesson>
            </div>
        </perfect-scrollbar>

        <div class="course__buttons">
            <div class="course__buttons-wrapper">
                <button class="button button--success" @click="$root.$emit('autoSave')">
                    Сохранить
                </button>

                <button class="button button--primary" @click="courseSettings.show = true">
                    Продолжить
                </button>
            </div>

            <a
                :href="getPreviewLink"
                target="_blank"
                class="button-light uploads__upload-btn course__preview"
            >
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 1.875C12.1973 1.875 14.8278 7 14.953 7.25C15.0157 7.375 15.0157 7.625 14.953 7.75C14.8278 8 12.1973 13.125 7.5 13.125C3.07867 13.125 0.488432 8.58447 0.094898 7.89463C0.0703362 7.85157 0.0543315 7.82351 0.0469729 7.8125C-0.0156576 7.625 -0.0156576 7.4375 0.0469729 7.25C0.172234 7 2.80271 1.875 7.5 1.875ZM1.29958 7.5C1.92589 8.5 4.11795 11.875 7.5 11.875C10.882 11.875 13.0741 8.5 13.7004 7.5C13.0741 6.5 10.882 3.125 7.5 3.125C4.11795 3.125 1.86326 6.5 1.29958 7.5ZM7.5 5C6.12213 5 4.99478 6.125 4.99478 7.5C4.99478 8.875 6.12213 10 7.5 10C8.87787 10 10.0052 8.875 10.0052 7.5C10.0052 6.125 8.87787 5 7.5 5ZM6.24739 7.5C6.24739 8.1875 6.81107 8.75 7.5 8.75C8.18894 8.75 8.75261 8.1875 8.75261 7.5C8.75261 6.8125 8.18894 6.25 7.5 6.25C6.81107 6.25 6.24739 6.8125 6.24739 7.5Z" fill="#6E6B7B"/></svg>
                <span>Предпросмотр контента</span>
            </a>

            <div class="course__saving-wrapper">
                <span>
                    {{timeValue}}
                </span>
                <template v-if="isSavingTime">
                    <span class="course__saving-spinner">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.8208 7.5C12.8208 8.55236 12.5088 9.58108 11.9241 10.4561C11.3394 11.3311 10.5084 12.0131 9.53619 12.4158C8.56394 12.8185 7.4941 12.9239 6.46196 12.7186C5.42983 12.5133 4.48175 12.0065 3.73762 11.2624C2.99349 10.5183 2.48673 9.57018 2.28143 8.53804C2.07612 7.5059 2.18149 6.43606 2.58421 5.46381C2.98693 4.49156 3.66891 3.66056 4.54392 3.0759C5.41892 2.49125 6.44765 2.17919 7.5 2.17919V0C6.82047 0 6.14793 0.0923104 5.5 0.271584C4.73357 0.483647 4.00158 0.817396 3.33323 1.26398C2.09986 2.08809 1.13856 3.25943 0.570907 4.62987C0.00324965 6.00032 -0.145275 7.50832 0.144114 8.96318C0.433503 10.418 1.14781 11.7544 2.1967 12.8033C3.2456 13.8522 4.58197 14.5665 6.03683 14.8559C7.49168 15.1453 8.99968 14.9968 10.3701 14.4291C11.7406 13.8614 12.9119 12.9001 13.736 11.6668C14.1826 10.9984 14.5164 10.2664 14.7284 9.5C14.9077 8.85208 15 8.17953 15 7.5H12.8208Z" fill="url(#paint0_linear_344_6349)"/><defs><linearGradient id="paint0_linear_344_6349" x1="6.875" y1="1.57605e-08" x2="15" y2="3.43872e-08" gradientUnits="userSpaceOnUse"><stop stop-color="#28C76F"/><stop offset="0.46938" stop-color="#28C76F" stop-opacity="0.378504"/><stop offset="1" stop-color="#28C76F" stop-opacity="0"/></linearGradient></defs></svg>
                    </span>
                </template>
            </div>
        </div>

    </div>
</template>

<script>
    import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css';
    import {mapGetters} from 'vuex';
    import { PerfectScrollbar } from 'vue2-perfect-scrollbar'
    import Lesson from "./Lesson";
    export default {
        name: "Course",
        components: { Lesson, PerfectScrollbar },
        
        data () {
            return {
                selectedLesson: 0,
                timeValue: '',
                isSavingTime: false,
                paymentLink: '',
                previewLink: '',
            }
        },
        
        mounted() {
            this.$store.dispatch('loadCourse', this.id).then((resp) => {
                if (this.course.lessons.length == 0) {
                    this.newLesson();
                }
                this.showLesson(this.course.lessons[0].id);
                window.uploads.setFiles(this.course.course_meta.attachments);
            });

            window.course = this;

            this.$root.$on('autoSave', (data) => {
                this.isSavingTime = true;
                this.timeValue = '✌ Мы сохраняем вашу работу';
            });

            this.$root.$on('autoSaveIsDone', (data) => {
                this.isSavingTime = false;
                this.timeValue = `✌ Мы сохранили вашу работу автоматически в ${ new Date().toLocaleTimeString().toString() }`;
            });
        },

        computed: {
            ...mapGetters(['course']),
            courseSettings: () => {
                return window.courseSettings;
            },
            id: () => {
                let urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('id');
            },
            getPreviewLink() {
                return this.course.course_meta.preview_link;
            }
        },

        methods: {
            showLesson (id) {
                this.$store.commit('setActiveLesson', id);
            },
            newLesson(){
                axios({url: '/api/lesson/store', data: {
                        id:0,
                        course_id:this.course.id,
                        modules: []
                    }, method: 'POST' })
                    .then(resp => {
                        if(resp.data.status === 'ok'){
                            this.$store.commit('pushLesson', resp.data.lesson);
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
            }
        }
    }
</script>

<style scoped lang="scss">
</style>