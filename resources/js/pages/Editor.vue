<template>

  <div class="layout">
    <div class="container">
      <Sidebar></Sidebar>
      <Course></Course>
    </div>
    <Uploader
        v-bind:demension="{ 'width':500, 'height':500 }"
        v-bind:style="{ width : 500, height : 500 }"
        limit="1"
        thin="true"
        ref="imgLoader"
        @imagesChanged="setImages"
    ></Uploader>
    <CourseSettings></CourseSettings>
  </div>
</template>

<script>
import Sidebar from "../components/pages/Course/Sidebar";
import Header from "../components/pages/Course/Header";
import Course from "../components/pages/Course/Course";
import Uploader from "../components/pages/Course/Uploader";
import CourseSettings from "../components/pages/Course/CourseSettings";

export default {
    name: "Editor.vue",
    
    components:{
        Sidebar,
        Header,
        Course,
        Uploader,
        CourseSettings
    },
    
    data() {
        return {
            dragMode: false,
        }
    },
    
    mounted() {
        setInterval(() => {
            this.$root.$emit('autoSave');
            this.recentlySaved = true;
            this.$store.dispatch('storeCourse', this.course).then((resp) => {
                this.$root.$emit('autoSaveIsDone');
            })
        }, 5000);
    },
    methods: {
        setImages(){}
    }
}
</script>

<style scoped>
</style>