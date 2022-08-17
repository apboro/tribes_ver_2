<template>
    <div class="image">
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
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M50.6668 5.33334L13.3335 5.33334C8.80016 5.33334 5.3335 8.80001 5.3335 13.3333L5.3335 50.6667C5.3335 55.2 8.80016 58.6667 13.3335 58.6667H50.6668C55.2002 58.6667 58.6668 55.2 58.6668 50.6667V13.3333C58.6668 8.80001 55.2002 5.33334 50.6668 5.33334ZM10.6668 13.3333C10.6668 11.7333 11.7335 10.6667 13.3335 10.6667H50.6668C52.2668 10.6667 53.3335 11.7333 53.3335 13.3333V33.6L44.5335 24.8C43.4668 23.7333 41.8668 23.7333 40.8002 24.8L12.5335 53.0667C11.4668 52.8 10.6668 51.7333 10.6668 50.6667V13.3333ZM19.7335 53.3333H50.6668C52.2668 53.3333 53.3335 52.2667 53.3335 50.6667V41.0667L42.6668 30.4L19.7335 53.3333ZM22.6668 29.3333C26.4002 29.3333 29.3335 26.4 29.3335 22.6667C29.3335 18.9333 26.4002 16 22.6668 16C18.9335 16 16.0002 18.9333 16.0002 22.6667C16.0002 26.4 18.9335 29.3333 22.6668 29.3333ZM24.0002 22.6667C24.0002 21.8667 23.4668 21.3333 22.6668 21.3333C21.8668 21.3333 21.3335 21.8667 21.3335 22.6667C21.3335 23.4667 21.8668 24 22.6668 24C23.4668 24 24.0002 23.4667 24.0002 22.6667Z" fill="#6E6B7B"/></svg>
                <p class="modules__text">Перетащить сюда</p>
                <button class="button button--dark modules__upload-btn" @click="initUploader($event, true)">
                    Загрузить
                </button>
            </div>
        </div>

        <div v-if="file.id > 0">
            <!-- <div
                class="modules__img-preview"
                :class="dragState ? 'filter' : ''"
                :style="{ backgroundImage: `url(${ file.url })` }"
            ></div> -->
            <img :src="file.url" alt="картинка" :class="dragState ? 'filter' : ''">
        </div>

        <div class="modules__label">Изображение</div>
    </div>
</template>

<script>
export default {
    name: "Image",
    props: [ 'k', 'value' ],

    data () {
        return {
            image: {
                value:'',
                key:''
            },
            file:{
                id: 0,
                url:'',
            },
            dragState:false,
        }
    },

    components: {},

    mounted() {
        this.image.value = this.$props.value;
        this.image.key = this.$props.k;
        if(this.image.value){
            this.file = this.$store.getters.getFileById(this.image.value)[0];
        }

        this.$root.$on('dragMode', (data) => {
            if(data.file.isImage){
                this.dragState = data.state;
            }
        })
    },

    watch: {
        image:{
            handler(newValue, oldValue) {
                this.$parent.$emit('propChanged', this.image)
            },
            deep: true
        }
    },

    methods: {
        getFileFromServer(){
            axios({url: '/api/file/get', data: { id : this.image.value}, method: 'POST' })
                .then(resp => {
                    if(resp.data.status === 'ok'){
                        this.file = resp.data.file;

                    }
                    // this.image = resp.data.file
                })
                .catch(err => {
                    console.log(err);
                })

        },
        
        setImages(){},

        initUploader(ev, clickRef = false){
            ev.preventDefault();

            // if(!this.dragState) return;
            let file = ev.dataTransfer && ev.dataTransfer.items.length ? ev.dataTransfer.items[0].getAsFile() : null;
            if(clickRef){
                window.uploader.upload(this);
            } else {
                if(file){
                    if (file.isImage) {
                        window.uploader.upload(this, file);
                        
                    }
                } else {
                    if(uploads.canPlace){
                        if(!uploads.activeFile.isImage){
                            alert('Неподходящий файл')
                            return
                        }
                        this.file = uploads.activeFile;
                        this.image.value = this.file.id;
                        setTimeout(() => {this.$root.$emit('autoSave')}, 300)
                    }
                }
            }
            this.$refs.dropzone ? this.$refs.dropzone.classList.remove('over'): null
        },

        dragOver(ev){
            ev.preventDefault();
            ev.target.classList.add('over');
        },

        dragLeave(ev){
            ev.preventDefault();
            ev.target.classList.remove('over');
        },

        placeFile(file){
            this.file = file;
            this.image.value = this.file.id;
            setTimeout(() => {this.$root.$emit('autoSave')}, 300)
        }
    }
}
</script>

<style scoped>
</style>