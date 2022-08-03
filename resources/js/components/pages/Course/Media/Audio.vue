<template>
    <div class="audio">
        <div
            v-if="dragState"
            @drop="initUploader($event)"
            @dragover="dragOver($event)"
            @dragleave="dragLeave($event)"
            class="modules__module-active-drop"
        >
            <span v-if="file.id !== 0">Можно поместить здесь</span>
        </div>

        <div v-if="file.id === 0" class="no-image">
            <div class="modules__dropzone canDrop"
                ref="dropzone"
                @drop="initUploader($event)"
                @dragover="dragOver($event)"
                @dragleave="dragLeave($event)"
            >
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M57.6002 5.86668C57.0668 5.33334 56.2668 5.33334 55.4668 5.33334L23.4668 10.6667C22.4002 10.9333 21.3335 12 21.3335 13.3333V42.6667H13.3335C8.80016 42.6667 5.3335 46.1333 5.3335 50.6667C5.3335 55.2 8.80016 58.6667 13.3335 58.6667H18.6668C23.2002 58.6667 26.6668 55.2 26.6668 50.6667V45.3333V15.4667L53.3335 10.9333V37.3333H45.3335C40.8002 37.3333 37.3335 40.8 37.3335 45.3333C37.3335 49.8667 40.8002 53.3333 45.3335 53.3333H50.6668C55.2002 53.3333 58.6668 49.8667 58.6668 45.3333V40V8.00001C58.6668 7.20001 58.4002 6.40001 57.6002 5.86668ZM21.3335 50.6667C21.3335 52.2667 20.2668 53.3333 18.6668 53.3333H13.3335C11.7335 53.3333 10.6668 52.2667 10.6668 50.6667C10.6668 49.0667 11.7335 48 13.3335 48H21.3335V50.6667ZM50.6668 48C52.2668 48 53.3335 46.9333 53.3335 45.3333V42.6667H45.3335C43.7335 42.6667 42.6668 43.7333 42.6668 45.3333C42.6668 46.9333 43.7335 48 45.3335 48H50.6668Z" fill="#6E6B7B"/></svg>
                <p class="modules__text">Перетащить сюда</p>
                <button class="button button--dark modules__upload-btn" @click="initUploader($event, true)">
                    Загрузить
                </button>
            </div>
        </div>

        <div v-if="file.id > 0">
            <av-bars v-if="playerActive" ref="player" :canv-width="0" :canv-height="0" canv-class="audio__canv"
                :audio-src="file.url">
            </av-bars>
        </div>

        <div class="modules__label">Аудио</div>
    </div>
</template>

<script>
export default {
    name: "Audio",
    props: [ 'k', 'value' ],
    data() {
        return {
            audio: {
                value:'',
                key:''
            },
            file:{
                id: 0,
                url:'',
            },
            dragState:false,
            playerActive:true,
        }
    },

    mounted() {
        this.audio.value = this.$props.value;
        this.audio.key = this.$props.k;
        if(this.audio.value){
            this.getFileFromServer();
        }
        this.$root.$on('dragMode', (data) => {
            if(data.file.isAudio){
                this.dragState = data.state;
            }
        })
    },

    watch: {
        audio:{
            handler(newValue, oldValue) {
                this.$parent.$emit('propChanged', this.audio)
            },
            deep: true
        }
    },

    methods: {
        getFileFromServer(){
            axios({url: '/api/file/get', data: { id : this.audio.value}, method: 'POST' })
                .then(resp => {
                    if(resp.data.status === 'ok'){
                        this.file = resp.data.file;

                    }
                })
                .catch(err => {
                    console.log(err);
                })

        },

        initUploader(ev, clickRef = false){
            ev.preventDefault();

            // if(!this.dragState) return;

            let file = ev.dataTransfer && ev.dataTransfer.items.length ? ev.dataTransfer.items[0].getAsFile() : null;

            if(clickRef){
                window.uploader.upload(this);
            } else {
                if(file){
                    window.uploader.upload(this, file);
                } else {
                    if(uploads.canPlace){
                        if(!uploads.activeFile.isAudio){
                            alert('Неподходящий файл')
                            return
                        }
                        this.file = uploads.activeFile;
                        this.audio.value = this.file.id;
                        setTimeout(() => {this.$root.$emit('autoSave')}, 300)
                        this.playerActive = false;
                        setTimeout(() => {
                            this.playerActive = true;
                        }, 1)
                    }
                }
            }
            this.$refs.dropzone ? this.$refs.dropzone.classList.remove('over'): null
        },

        placeFile(file){
            this.file = file;
            this.audio.value = this.file.id;
            setTimeout(() => {this.$root.$emit('autoSave')}, 300)
        },

        dragOver(ev){
            ev.preventDefault();
            ev.target.classList.add('over');
        },

        dragLeave(ev){
            ev.preventDefault();
            ev.target.classList.remove('over');
        }
    }
}
</script>

<style >
    .audio__canv {
        display: none;
    }
</style>