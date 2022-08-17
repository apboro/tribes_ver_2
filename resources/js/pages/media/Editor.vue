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
import Sidebar from "../../components/pages/course/Sidebar";
import Header from "../../components/pages/course/Header";
import Course from "../../components/pages/course/Course";
import Uploader from "../../components/pages/course/Uploader";
import CourseSettings from "../../components/pages/course/CourseSettings";
import {BlockElements} from "../../plugins/Abstract/BlockElements";

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
        new BlockElements();
        //console.log(document.querySelectorAll('[type="submit"]'));
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