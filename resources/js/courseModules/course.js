
let state = {
    activeLesson: 0,
    course:{
        course_meta:{
            attachments:[],
            payment_description: "",
            preview: 0,
            access_days: 0,
        },
        lessons:[
            {
                id: 0,
                modules: [
                    {
                        id:0
                    }
                ]
            }
        ]
    }
    // product_errors : [],
};

let getters = {
    course : state => {
        return state.course
    },
    activeLesson : state => {
        return state.activeLesson
    },
    getLessonById : (state) => (id) => {
        return state.course.lessons.filter(item => item.id === parseInt(id))
    },
    getFileById : (state) => (id) => {
        return state.course.course_meta.attachments.filter(item => item.id === parseInt(id))
    },
};

let mutations = {

    setCourse(state, course){
        state.course = course;
    },

    setCourseAccessDays(state, value) {
        state.course.course_meta.access_days = value;
    },

    pushLesson(state, lesson){
        state.course.lessons.push(lesson);
        this.commit('setActiveLesson',lesson.id)
    },

    removeLesson(state, id){
        state.course.lessons.forEach((lesson, index) => {
            if(lesson.id === id){
                state.course.lessons.splice(index, 1);
            }
        })
    },
    removeFile(state, id){
        this.dispatch('removeFile', {id:id}).then((resp) => {
            state.course.course_meta.attachments.forEach((file, index) => {
                if(file.id === id){
                    state.course.course_meta.attachments.splice(index, 1);
                }});

            window.modules.forEach((module, index) => {
                if(typeof module.file !== "undefined" && module.file !== null && module.file.id === id){

                    module.file = {
                        id: 0,
                        url:'',
                    };

                    if(typeof module.image !== "undefined"){
                        module.image = { value:'', key:'image_1'};
                        // module.$parent.$emit('propChanged', module.image)
                    }
                    if(typeof module.video !== "undefined"){
                        module.video = { value:'', key:'video_1'};
                        // module.$parent.$emit('propChanged', module.video)
                    }
                    if(typeof module.audio !== "undefined"){
                        module.audio = { value:'', key:'audio_1'};
                        // module.$parent.$emit('propChanged', module.audio)
                    }

                }
            })
        });

    },
    removeModule(state, data){
        state.course.lessons.forEach((lesson) => {
            if(lesson.id === data.lesson_id){
                lesson.modules.splice(data.module_index, 1);
            }
            // lesson.modules.forEach((module, index) => {
            //     if(module.id === id){
            //
            //     }
            // })
        })
    },
    setActiveLesson(state, id){
        state.activeLesson = id;
        state.course.lessons.forEach((lesson) => {
            lesson.active = false;
        })

        let l = state.course.lessons.filter(item => item.id === parseInt(id));
        l[0].active = true
    },
};

let actions = {
    loadCourse({commit}, id){
        return new Promise((resolve, reject) => {
            axios({url: '/api/course/edit', data: { id : id}, method: 'POST' })
                .then(resp => {
                    if(resp.data.status === 'error'){
                        window.location.href = '/courses'
                    }
                    let course = resp.data.course;
                    course.lessons.forEach((lesson, index) => {
                        lesson.index = index;
                        lesson.active = !index
                    })
                    commit('setCourse', course);
                    resolve(resp);
                })
                .catch(err => {
                    reject(err);
                })
        })
    },
    storeCourse({commit}, data){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/course/store', data: state.course, method: 'POST' })
                .then(resp => {
                    // commit('setCourse', resp.data.course);
                    resolve(resp);
                })
                .catch(err => {
                    //commit('push_page_errors', !!err.response.data.errors ? err.response.data.errors : null);
                    reject(err);
                })
        })
    },
    removeFile({commit}, file){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/file/delete', data: { id: file.id}, method: 'POST' })
                .then(resp => {
                    // commit('setCourse', resp.data.course);
                    resolve(resp);
                })
                .catch(err => {
                    //commit('push_page_errors', !!err.response.data.errors ? err.response.data.errors : null);
                    reject(err);
                })
        })
    },
    // storeLesson({commit}, data){
    //     return new Promise((resolve, reject) => {
    //         //commit('auth_request');
    //         axios({url: '/api/lesson/store', data: data, method: 'POST' })
    //             .then(resp => {
    //                 // commit('setCourse', resp.data.course);
    //                 resolve(resp);
    //             })
    //             .catch(err => {
    //                 //commit('push_page_errors', !!err.response.data.errors ? err.response.data.errors : null);
    //                 reject(err);
    //             })
    //     })
    // },
    // createLesson({commit}, data){
    //     return new Promise((resolve, reject) => {
    //         //commit('auth_request');
    //         axios({url: '/api/lesson/store', data: {id: 0, course_id: state.course.id}, method: 'POST' })
    //             .then(resp => {
    //                 commit('pushLesson', resp.data.lesson);
    //                 resolve(resp);
    //             })
    //             .catch(err => {
    //                 //commit('push_page_errors', !!err.response.data.errors ? err.response.data.errors : null);
    //                 reject(err);
    //             })
    //     })
    // },
};

export default {
    state,
    getters,
    mutations,
    actions,
};