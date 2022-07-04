<template>
    <div class="modules">

        <div
            class="modules__target"
            v-bind:class="{ 'full' : lesson.modules.length < 1 }"
            @drop="dropModule($event, - 1)"
            @dragenter="onDragenter($event)"
            @dragleave="ondragleave($event)"
            @dragover.prevent
        >
            <span class="place-here">Поместить сюда</span>
            <div class="helper">
                Начните переносить содержимое на страницу
            </div>
        </div>

        <draggable
            v-model="lesson.modules"
            group="modules"
            handle=".handle"
            @change="wasDragged()"
        >
            <transition-group name="fade" tag="div" class="modules__group">
                <div
                    class="modules__module-container"
                    v-for="(module, index) in lesson.modules"
                    :key="module.index"
                >
                    <div class="modules__placeholder" >
                        <Module v-bind:module="module"></Module>
                        <button class="handle">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.7244 11.6883L18.7175 14.6917C18.5171 14.8919 18.3166 14.992 18.0159 14.992C17.7153 14.992 17.5148 14.8919 17.3144 14.6917C16.9134 14.2912 16.9134 13.6906 17.3144 13.2901L18.6173 11.9886H12.0023V18.5961L13.3052 17.2947C13.7062 16.8942 14.3075 16.8942 14.7084 17.2947C15.1093 17.6951 15.1093 18.2958 14.7084 18.6962L11.7016 21.6997C11.6014 21.7998 11.5011 21.8999 11.4009 21.8999C11.3007 22 11.1002 22 11 22C10.8998 22 10.6993 22 10.5991 21.8999C10.4989 21.7998 10.3986 21.7998 10.2984 21.6997L7.29157 18.6962C6.89066 18.2958 6.89066 17.6951 7.29157 17.2947C7.69248 16.8942 8.29385 16.8942 8.69476 17.2947L9.99772 18.5961V11.9886H3.38269L4.68565 13.2901C5.08656 13.6906 5.08656 14.2912 4.68565 14.6917C4.48519 14.8919 4.28474 14.992 3.98405 14.992C3.68337 14.992 3.48292 14.8919 3.28246 14.6917L0.275626 11.6883C0.175399 11.5882 0.0751708 11.4881 0.0751708 11.3879C-0.0250569 11.1877 -0.0250569 10.8874 0.0751708 10.587C0.175399 10.4869 0.175399 10.3868 0.275626 10.2867L3.28246 7.28328C3.68337 6.88282 4.28474 6.88282 4.68565 7.28328C5.08656 7.68373 5.08656 8.28441 4.68565 8.68487L3.38269 9.98635H9.99772V3.37884L8.69476 4.68032C8.29385 5.08077 7.69248 5.08077 7.29157 4.68032C6.89066 4.27986 6.89066 3.67918 7.29157 3.27873L10.2984 0.275313C10.3986 0.175199 10.4989 0.0750853 10.5991 0.0750853C10.7995 -0.0250284 11.1002 -0.0250284 11.4009 0.0750853C11.5011 0.175199 11.6014 0.175199 11.7016 0.275313L14.7084 3.27873C15.1093 3.67918 15.1093 4.27986 14.7084 4.68032C14.508 4.88055 14.3075 4.98066 14.0068 4.98066C13.7062 4.98066 13.5057 4.88055 13.3052 4.68032L12.0023 3.37884V9.98635H18.6173L17.3144 8.68487C16.9134 8.28441 16.9134 7.68373 17.3144 7.28328C17.7153 6.88282 18.3166 6.88282 18.7175 7.28328L21.7244 10.2867C21.8246 10.3868 21.9248 10.4869 21.9248 10.587C22.0251 10.7873 22.0251 11.0876 21.9248 11.3879C21.9248 11.4881 21.8246 11.5882 21.7244 11.6883Z" fill="white"/></svg>
                        </button>
                    </div>

                    <div
                        class="modules__target"
                        @drop="dropModule($event, index)"
                        @dragenter="onDragenter($event)"
                        @dragleave="ondragleave($event)"
                        @dragover.prevent
                    >
                        <span class="place-here">Поместить сюда</span>
                    </div>
                </div>
            </transition-group>
        </draggable>
    </div>
</template>

<script>
    import Module from "./Module";
    import draggable from 'vuedraggable'
    import {mapGetters} from "vuex";
    export default {
        name: "Lesson",
        components: {Module, draggable},
        data () {
            return {
                lesson: {
                    id: 0,
                    course_id: 0,
                    index: 0,
                    lesson_meta:{
                        title: 'Часть'
                    },
                    modules: []
                },
                needUpdate: false,
                droppedIndex:0,
            }
        },
        computed:{
            currentLesson(){
                return this.$store.getters.getLessonById(this.$attrs.id)[0];
            }
        },

        mounted() {
            this.lesson = this.$attrs.lesson;
            // this.lesson = this.$attrs.lesson;
            // setInterval(() => {
            //     this.lesson = this.$store.getters.getLessonById(this.$attrs.id)[0];
            // }, 500)

            // this.$root.$on('freshLessons', (data) => {
            //     this.lesson = this.$attrs.lesson;
            // })

            // this.lesson.id = this.$attrs.id ?? 0;
            // this.lesson.course_id = this.$parent.course.id ?? 0;

            // if(this.lesson.id){
            //     this.getFromServer();
            // } else {
            //     this.needUpdate = true;
            //     this.store();
            // }

            // this.$root.$on('autoSave', (data) => {
            //     console.log(this.lesson)
            //     this.$store.dispatch('storeLesson', this.lesson).then((resp) => {})
            // })
            this.$root.$on('lessonShowed', (id) => {
                if(this.lesson.id === id){
                    // console.log(this.lesson)
                    window.lsettings.setLesson(this.lesson)
                }
            })
        },

        methods: {
            getFromServer(){
                axios({url: '/api/lesson/edit', data: { id : this.lesson.id}, method: 'POST' })
                    .then(resp => {
                        this.lesson = resp.data.lesson;
                        this.needUpdate = false;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },

            dropModule(event, index){
                event.target.classList.remove('dragged')
                this.droppedIndex = index + 1;
                this.$root.$emit('needsTemplate', this);
            },

            temaplateRecieved(template){
                if(this.droppedIndex === -1){
                    this.lesson.modules.unshift({
                        id:0,
                        index:0,
                        template_id:template.template_id,
                    })
                } else {
                    let arr = this.lesson.modules.map(m => m.index);

                    let freeArr = [...Array(200).keys()].filter(x => !arr.includes(x));

                    let i = Math.floor(Math.random() * freeArr.length);

                    this.lesson.modules.splice(this.droppedIndex, 0, {
                        id:0,
                        index:i,
                        template_id:template.template_id,
                    })
                }

                // setTimeout(() => {this.store()}, 300)
            },

            getModulesIndex(state){
                return state ?
                    Math.max(...this.lesson.modules.map(o => o.index)) :
                    Math.min(...this.lesson.modules.map(o => o.index))
            },

            onDragenter(event){
                if(templates.canPlace)
                event.target.classList.add('dragged')
                event.preventDefault();
            },

            ondragleave(event){
                if(templates.canPlace)   
                event.target.classList.remove('dragged')
                event.preventDefault();
            },
            
            wasDragged(){
                this.$root.$emit('autoSave');
            },
        }
    }
</script>

<style scoped>
</style>