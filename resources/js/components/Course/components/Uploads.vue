<template>
    <div class="uploads">
        <div class="uploads__btn-wrapper">
            <button class="button-light uploads__upload-btn" @click="upload()">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.9389 5.33636C12.9083 5.30454 12.8931 5.27273 12.8778 5.24091C12.8625 5.20909 12.8472 5.17727 12.8167 5.14545L8.53889 0.690909C8.47778 0.627273 8.41667 0.563636 8.35556 0.563636C8.29444 0.5 8.17222 0.5 8.11111 0.5H3.83333C2.79444 0.5 2 1.32727 2 2.40909V12.5909C2 13.6727 2.79444 14.5 3.83333 14.5H11.1667C12.2056 14.5 13 13.6727 13 12.5909V5.59091C13 5.52727 13 5.4 12.9389 5.33636ZM8.72217 2.66364L10.9222 4.95455H8.72217V2.66364ZM3.83328 13.2273H11.1666C11.5333 13.2273 11.7777 12.9727 11.7777 12.5909V6.22727H8.11106C7.74439 6.22727 7.49995 5.97273 7.49995 5.59091V1.77273H3.83328C3.46661 1.77273 3.22217 2.02727 3.22217 2.40909V12.5909C3.22217 12.9727 3.46661 13.2273 3.83328 13.2273Z" fill="#6E6B7B"/></svg>
                <span>Загрузить файл</span>
            </button>
        </div>

        <perfect-scrollbar>
            <ul class="uploads__list">
                <li class="uploads__item"
                    draggable="true"
                    @dragstart="ondragStart(file)"
                    @dragend="ondragEnd()"
                    v-for="file in files"
                    :key="file.id"
                >   
                    <div class="uploads__preview-container">
                        <div v-if="file.isImage" class="uploads__preview">
                            <img :src="file.url" alt="">
                        </div>

                        <div v-if="file.isVideo" class="uploads__preview">
                            <img :src="getVideoPreview(file)" alt="">
                        </div>

                        <div v-if="file.isAudio" class="uploads__preview">
                            <svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M39.3 5.35C38.95 5 38.425 5 37.9 5L16.9 8.5C16.2 8.675 15.5 9.375 15.5 10.25V29.5H10.25C7.275 29.5 5 31.775 5 34.75C5 37.725 7.275 40 10.25 40H13.75C16.725 40 19 37.725 19 34.75V31.25V11.65L36.5 8.675V26H31.25C28.275 26 26 28.275 26 31.25C26 34.225 28.275 36.5 31.25 36.5H34.75C37.725 36.5 40 34.225 40 31.25V27.75V6.75C40 6.225 39.825 5.7 39.3 5.35ZM15.4999 34.75C15.4999 35.8 14.7999 36.5 13.7499 36.5H10.2499C9.19991 36.5 8.49991 35.8 8.49991 34.75C8.49991 33.7 9.19991 33 10.2499 33H15.4999V34.75ZM34.75 33C35.8 33 36.5 32.3 36.5 31.25V29.5H31.25C30.2 29.5 29.5 30.2 29.5 31.25C29.5 32.3 30.2 33 31.25 33H34.75Z" fill="#6E6B7B"/><mask id="mask0_205_4108" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="5" width="35" height="35"><path fill-rule="evenodd" clip-rule="evenodd" d="M39.3 5.35C38.95 5 38.425 5 37.9 5L16.9 8.5C16.2 8.675 15.5 9.375 15.5 10.25V29.5H10.25C7.275 29.5 5 31.775 5 34.75C5 37.725 7.275 40 10.25 40H13.75C16.725 40 19 37.725 19 34.75V31.25V11.65L36.5 8.675V26H31.25C28.275 26 26 28.275 26 31.25C26 34.225 28.275 36.5 31.25 36.5H34.75C37.725 36.5 40 34.225 40 31.25V27.75V6.75C40 6.225 39.825 5.7 39.3 5.35ZM15.4999 34.75C15.4999 35.8 14.7999 36.5 13.7499 36.5H10.2499C9.19991 36.5 8.49991 35.8 8.49991 34.75C8.49991 33.7 9.19991 33 10.2499 33H15.4999V34.75ZM34.75 33C35.8 33 36.5 32.3 36.5 31.25V29.5H31.25C30.2 29.5 29.5 30.2 29.5 31.25C29.5 32.3 30.2 33 31.25 33H34.75Z" fill="white"/></mask><g mask="url(#mask0_205_4108)"></g></svg>
                        </div>


                        <button class="uploads__remove-btn small-btn" @click="deleteFile(file)">
                            <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19 4H15V3C15 1.3 13.7 0 12 0H8C6.3 0 5 1.3 5 3V4H1C0.4 4 0 4.4 0 5C0 5.6 0.4 6 1 6H2V19C2 20.7 3.3 22 5 22H15C16.7 22 18 20.7 18 19V6H19C19.6 6 20 5.6 20 5C20 4.4 19.6 4 19 4ZM7 3C7 2.4 7.4 2 8 2H12C12.6 2 13 2.4 13 3V4H7V3ZM15 20C15.6 20 16 19.6 16 19V6H4V19C4 19.6 4.4 20 5 20H15ZM9 10V16C9 16.6 8.6 17 8 17C7.4 17 7 16.6 7 16V10C7 9.4 7.4 9 8 9C8.6 9 9 9.4 9 10ZM13 16V10C13 9.4 12.6 9 12 9C11.4 9 11 9.4 11 10V16C11 16.6 11.4 17 12 17C12.6 17 13 16.6 13 16Z" fill="white"/></svg>
                        </button>
                    </div>

                    <div class="uploads__name">{{ file.mime }}</div>
                </li>
            </ul>
        </perfect-scrollbar>
    </div>
</template>

<script>
import { PerfectScrollbar } from 'vue2-perfect-scrollbar'
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css';

export default {
    name: "Uploads",
    data() {
        return {
            files: [],
            canPlace: false,
            activeFile: null,
            dragMode:false,
        }
    },

    components: { PerfectScrollbar },

    computed: {},

    mounted() {
        window.uploads = this;
        this.dragMode = false;
    },

    methods: {
        setFiles(files){
            this.files = files;
        },
        
        pushFile(file){
            this.files.push(file);
        },

        getVideoPreview(file){
            if(!file.isVideo) return false;
            let preview = file.description
            return JSON.parse(preview)[0]
        },

        ondragStart(file){
            this.canPlace = true;
            this.activeFile = file;
            this.dragMode = true;
            this.$root.$emit('dragMode', {
                state:this.dragMode,
                file: this.activeFile,
            }, );
        },

        ondragEnd(file){
            this.dragMode = false;
            this.$root.$emit('dragMode', {
                state:this.dragMode,
                file: this.activeFile,
            });
        },

        upload(){
            window.uploader.upload(this);
            // this.$root.$emit('upload', {ref:this})
        },
        
        deleteFile(file){
            this.$store.commit('removeFile', file.id);
        }
    }
}
</script>

<style scoped> 
</style>