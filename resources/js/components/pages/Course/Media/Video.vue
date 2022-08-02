<template>
    <div class="video">
        <div v-if="!isVideoReady" class="video__not-ready">
            <span class="video__load-spinner">
                <svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M38.4624 22.5C38.4624 25.6571 37.5263 28.7432 35.7723 31.3683C34.0183 33.9933 31.5253 36.0392 28.6086 37.2474C25.6918 38.4555 22.4823 38.7716 19.3859 38.1557C16.2895 37.5398 13.4452 36.0195 11.2129 33.7871C8.98047 31.5548 7.46019 28.7105 6.84428 25.6141C6.22837 22.5177 6.54448 19.3082 7.75263 16.3914C8.96079 13.4747 11.0067 10.9817 13.6318 9.22771C16.2568 7.47374 19.3429 6.53756 22.5 6.53756V0C20.4614 0 18.4438 0.276931 16.5 0.814751C14.2007 1.45094 12.0047 2.45219 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.00974894 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44343 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C42.5478 32.9953 43.5491 30.7993 44.1852 28.5C44.7231 26.5562 45 24.5386 45 22.5H38.4624Z" fill="url(#paint0_linear_365_5881)"/><defs><linearGradient id="paint0_linear_365_5881" x1="20.625" y1="4.72816e-08" x2="45" y2="1.03162e-07" gradientUnits="userSpaceOnUse"><stop stop-color="white"/><stop offset="0.46938" stop-color="white" stop-opacity="0.378504"/><stop offset="1" stop-color="white" stop-opacity="0"/></linearGradient></defs></svg>
            </span>
            <span>Видео загружается</span>
        </div>

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
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M52.7998 2.66669H11.1998C6.39984 2.66669 2.6665 6.40002 2.6665 11.2V52.8C2.6665 57.6 6.39984 61.3334 11.1998 61.3334H52.7998C57.5998 61.3334 61.3332 57.6 61.3332 52.8V11.2C61.3332 6.40002 57.5998 2.66669 52.7998 2.66669ZM47.9998 21.3334H55.9998V29.3334H47.9998V21.3334ZM21.3332 29.3334H42.6665V8.00002H21.3332V29.3334ZM15.9998 29.3334H7.99984V21.3334H15.9998V29.3334ZM15.9998 34.6667H7.99984V42.6667H15.9998V34.6667ZM21.3332 34.6667H42.6665V56H21.3332V34.6667ZM55.9998 34.6667H47.9998V42.6667H55.9998V34.6667ZM55.9998 11.2V16H47.9998V8.00002H52.7998C54.6665 8.00002 55.9998 9.33335 55.9998 11.2ZM15.9998 8.00002H11.1998C9.33317 8.00002 7.99984 9.33335 7.99984 11.2V16H15.9998V8.00002ZM7.99984 52.8V48H15.9998V56H11.1998C9.33317 56 7.99984 54.6667 7.99984 52.8ZM47.9998 56H52.7998C54.6665 56 55.9998 54.6667 55.9998 52.8V48H47.9998V56Z" fill="#6E6B7B"/></svg>
                <p class="modules__text">Перетащить сюда</p>
                <button class="button button--dark modules__upload-btn" @click="initUploader($event, true)">
                    Загрузить
                </button>
            </div>
        </div>

        <video-player 
            v-if="video.value > 0"
            ref="videoPlayer"
            class="vjs-custom-skin"
            :class="dragState ? 'filter' : ''"
            :options="playerOptions"
            @ready="onPlayerReady($event)"
            @canplay="onPlayerCanplay($event)"
        ></video-player>

        <div class="modules__label">Видео</div>
<!--        <video-player v-if="file.id > 0"-->
<!--                        class="video-player-box"-->
<!--                       ref="videoPlayer"-->
<!--                       :options="playerOptions"-->
<!--                       :playsinline="true"-->
<!--                       customEventName="customstatechangedeventname">-->
<!--        </video-player>-->
    </div>
</template>

<script>

export default {
    name: "Video",
    props: [ 'k', 'value' ],
    data () {
        return {
            video: {
                value:'',
                key:''
            },
            file:{
                id: 0,
                url:'',
            },
            dragState:false,
            playerReady: false,
            isVideoReady: false,
            playerOptions: {
                // videojs options
                muted: false,
                autoplay: false,
                width: 'auto',
                language: 'ru',
                playbackRates: [0.7, 1.0, 1.5, 2.0],
                // sources: [{
                //     withCredentials: false,
                //     type: 'application/x-mpegurl',
                //     src: '#'
                // }],
                poster: "",
            }
            // playerOptions: {
            //     autoplay: true,
            //     controls: true,
            //     controlBar: {
            //         timeDivider: false,
            //         durationDisplay: false
            //     }
            //     // poster: 'https://surmon-china.github.io/vue-quill-editor/static/images/surmon-5.jpg'
            // }
        }
    },
    components:{},
    mounted() {
        this.video.value = this.$props.value;
        this.video.key = this.$props.k;
        if(!this.video.value){
            this.isVideoReady = true;
        } else {
            this.getFileFromServer();
        }

        this.$root.$on('dragMode', (data) => {
            if(data.file.isVideo){
                this.dragState = data.state;
            }
        })
    },

    computed: {
        player () {
            return this.$refs.videoPlayer ? this.$refs.videoPlayer.player : null
        }
    },

    watch: {
        video:{
            handler(newValue, oldValue) {
                this.$parent.$emit('propChanged', this.video)
            },
            deep: true
        }
    },

    methods: {
        getFileFromServer(){
            this.file = this.$store.getters.getFileById(this.video.value)[0];

            setTimeout(() => {this.$root.$emit('autoSave')}, 300)
            let checker = setInterval(() => {
                if(this.playerReady){
                    let checker2 = setInterval(() => {
                        if(this.videoReady(this.file.url)){
                            this.player.src(video)
                            this.playVideo(this.file.url)
                            clearInterval(checker2);
                        }
                    }, 5000)
                    clearInterval(checker);
                }
            }, 100)

        },

        setvideos(){},

        placeFile(file){
            this.isVideoReady = false
            this.file = file;
            this.video.value = this.file.id;
            setTimeout(() => {
                this.$root.$emit('autoSave')
            }, 300)
            let checker = setInterval(() => {
                if(this.playerReady){
                    let checker2 = setInterval(() => {
                        if(this.videoReady(this.file.url)){
                            this.player.src(video)
                            this.playVideo(this.file.url)
                            clearInterval(checker2);
                        }
                    }, 5000)
                    clearInterval(checker);
                }
            }, 100)
        },

        playVideo: function (source) {
            const video = {
                withCredentials: false,
                type: 'application/x-mpegurl',
                src: source
            }
            // this.player.reset() // in IE11 (mode IE10) direct usage of src() when <src> is already set, generated errors,
            this.player.src(video);

            // this.player.load()
            // this.player.play()
        },

        onPlayerReady(player){
            this.playerReady = true;
        },

        videoReady(url){
            let http = new XMLHttpRequest();
            http.open('HEAD', url, false);
            http.send();
            return http.status !== 404;
        },

        initUploader(ev, clickRef = false){
            ev.preventDefault();
            this.isVideoReady = false
            // if(!this.dragState) return;
            let file = ev.dataTransfer && ev.dataTransfer.items.length ? ev.dataTransfer.items[0].getAsFile() : null;
            
            if(clickRef){
                window.uploader.upload(this);
            } else {
                if(file){
                    window.uploader.upload(this, file);
                } else {
                    if(uploads.canPlace) {
                        if(!uploads.activeFile.isVideo){
                            alert('Неподходящий файл')
                            this.isVideoReady = true
                            return
                        }


                        // this.playVideo(this.file.url)
                        // this.isVideoReady = true

                        this.file = uploads.activeFile;
                        this.video.value = this.file.id;

                        let checker = setInterval(() => {
                            if(this.playerReady){
                                let checker2 = setInterval(() => {
                                    if(this.videoReady(this.file.url)){
                                        this.playVideo(this.file.url)
                                        this.player.src(video)
                                        clearInterval(checker2);
                                    }
                                }, 1000)
                                clearInterval(checker);
                            }
                        }, 100)

                        // let checker = setInterval(() => {
                        //     if(this.videoReady(this.file.url)){
                        //
                        //         this.video.value = this.file.id;
                        //
                        //         // this.playerOptions.poster = JSON.parse(this.file.description)[0]
                        //         // console.log(this.playerOptions.poster)
                        //
                        //         let checker = setInterval(() => {
                        //             if(this.playerReady){
                        //
                        //                 this.player.src(this.file.url)
                        //                 this.playVideo(this.file.url)
                        //                 this.isVideoReady = true
                        //
                        //                 clearInterval(checker);
                        //             }
                        //         }, 100)
                        //
                        //         clearInterval(checker);
                        //     }
                        // }, 2000)

                    } else {
                        this.isVideoReady = true
                        return
                    }
                }
            }
            this.$refs.dropzone ? this.$refs.dropzone.classList.remove('over'): null
        },

        dragOver(ev){
            ev.preventDefault();
            ev.target.classList.add('over');
        },

        onPlayerCanplay(ev){
            this.isVideoReady = true
        },

        dragLeave(ev){
            ev.preventDefault();
            ev.target.classList.remove('over');
        },
    }
}
</script>

<style scoped>
</style>