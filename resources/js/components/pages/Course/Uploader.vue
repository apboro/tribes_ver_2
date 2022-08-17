<template>
    <div class="modal-container">
        <input
            ref="file"
            style="display: none"
            type="file"
            accept="image/*, audio/*, video/*"
            @change="uploadFile"
        >

        <transition name="modal" v-if="showModal" >
            <div class="modal" @click.self="closeModal">
                <div class="modal__wrapper">
                    <div class="modal__container">
                        <div class="modal__header">
                            <slot name="header">
                                <span>Загрузка изображения</span>
                                <button class="modal__close-btn" @click="closeModal">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.7 17.3C19.1 17.7 19.1 18.3 18.7 18.7C18.5 18.9 18.3 19 18 19C17.7 19 17.5 18.9 17.3 18.7L12 13.4L6.7 18.7C6.5 18.9 6.3 19 6 19C5.7 19 5.5 18.9 5.3 18.7C4.9 18.3 4.9 17.7 5.3 17.3L10.6 12L5.3 6.7C4.9 6.3 4.9 5.7 5.3 5.3C5.7 4.9 6.3 4.9 6.7 5.3L12 10.6L17.3 5.3C17.7 4.9 18.3 4.9 18.7 5.3C19.1 5.7 19.1 6.3 18.7 6.7L13.4 12L18.7 17.3Z" fill="#6E6B7B"/><rect x="0.5" y="0.5" width="23" height="23" stroke="black"/><rect x="0.5" y="0.5" width="23" height="23" stroke="black" stroke-opacity="0.2"/></svg>
                                </button>
                            </slot>
                        </div>
                        
                        <div class="modal__body modal__body--crop">
                            <cropper
                                ref="cropper"
                                :stencil-props="{
                                    aspectRatio: aspect,
                                    size:'100%',
                                    viewMode: 2,
                                    startSize : [100, 100, '%']
                                }"
                                :default-size="defaultSize"
                                :src="img"
                                @change="change"
                            />
                            <div class="edit-img-btns">
                                <div class="edit-img-btns__item" title="Flip Horizontal" @click="flip(true, false)">
                                    <img src="/images/icons/flip-horizontal.svg" />
                                </div>
                                <div class="edit-img-btns__item" title="Flip Vertical" @click="flip(false, true)">
                                    <img src="/images/icons/flip-vertical.svg" />
                                </div>
                                <div class="edit-img-btns__item" title="Rotate Clockwise" @click="rotate(45)">
                                    <img src="/images/icons/rotate-clockwise.svg" />
                                </div>
                                <div class="edit-img-btns__item" title="Rotate Counter-Clockwise" @click="rotate(-45)">
                                    <img src="/images/icons/rotate-counter-clockwise.svg" />
                                </div>
                            </div>
                        </div>

                        <div class="modal__footer">
                            <slot name="footer">
                                <button class="button button--success modal__save-btn" v-if="isLoadImage" @click="cropImage()">Сохранить</button>
                                <span class="course-settings__saving-spinner" v-else>
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.8208 7.5C12.8208 8.55236 12.5088 9.58108 11.9241 10.4561C11.3394 11.3311 10.5084 12.0131 9.53619 12.4158C8.56394 12.8185 7.4941 12.9239 6.46196 12.7186C5.42983 12.5133 4.48175 12.0065 3.73762 11.2624C2.99349 10.5183 2.48673 9.57018 2.28143 8.53804C2.07612 7.5059 2.18149 6.43606 2.58421 5.46381C2.98693 4.49156 3.66891 3.66056 4.54392 3.0759C5.41892 2.49125 6.44765 2.17919 7.5 2.17919V0C6.82047 0 6.14793 0.0923104 5.5 0.271584C4.73357 0.483647 4.00158 0.817396 3.33323 1.26398C2.09986 2.08809 1.13856 3.25943 0.570907 4.62987C0.00324965 6.00032 -0.145275 7.50832 0.144114 8.96318C0.433503 10.418 1.14781 11.7544 2.1967 12.8033C3.2456 13.8522 4.58197 14.5665 6.03683 14.8559C7.49168 15.1453 8.99968 14.9968 10.3701 14.4291C11.7406 13.8614 12.9119 12.9001 13.736 11.6668C14.1826 10.9984 14.5164 10.2664 14.7284 9.5C14.9077 8.85208 15 8.17953 15 7.5H12.8208Z" fill="url(#paint0_linear_344_6349)"/><defs><linearGradient id="paint0_linear_344_6349" x1="6.875" y1="1.57605e-08" x2="15" y2="3.43872e-08" gradientUnits="userSpaceOnUse"><stop stop-color="#28C76F"/><stop offset="0.46938" stop-color="#28C76F" stop-opacity="0.378504"/><stop offset="1" stop-color="#28C76F" stop-opacity="0"/></linearGradient></defs></svg>
                                </span>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
export default {
    name: "ImagesUpload",
    props: ['value'],

    data() {
        return {
            refComponent: null,
            showModal: false,
            file: null,
            images : [
                {
                    url : '/images/noimage.jpg',
                }
            ],
            img : null,
            coordinates : null,
            result: null,
            limit:5,
            thin:false,
            bg:false,
            // defaultAspect:26/9,
            // aspect: 26/9,
            instantUpload : null,
            demension : {width:100, height:100},
            isLoadImage: false,
        };
    },

    mounted() {
        this.limit = !!this.$attrs.limit ? this.$attrs.limit : this.limit;
        this.thin = !!this.$attrs.thin ? this.$attrs.thin : this.thin;
        this.bg = !!this.$attrs.bg ? this.$attrs.bg : this.bg;
        this.aspect = !!this.$attrs.aspect ? this.$attrs.aspect : this.aspect;
        this.demension = !!this.$attrs.demension ? this.$attrs.demension : this.demension;
        //this.images = this.value.images;
        // let getter = 'get_' + this.storage_model + '_images';
        //
        // this.pr = this.$store.getters.getProduct(this.id);
        // return this.$store.getters[getter](this.id);
        window.uploader = this;
        this.$root.$on('upload', (data) => {
            this.upload(...data)
        })
    },

    computed: {
        instant : () => {
            return !!this.instantUpload;
        }
    },

    methods: {
        upload(ref, file, aspect = null){
            this.aspect = aspect ?? this.defaultAspect;
            this.refComponent = ref;
            if(file){
                this.uploadFile(file);
            } else {
                this.$refs.file.click();
            }
        },

        defaultSize({ imageSize, visibleArea }) {
            return {
                width: (visibleArea || imageSize).width,
                height: (visibleArea || imageSize).height,
            };
        },

        freshImg(image){
            this.instantUpload = image.id;
            this.$refs.file.click();
        },

        uploadFile(f){
            f = (f instanceof File) ? f : null
            let input = this.$refs.file;

            if ((input.files && input.files[0]) || f) {
                let reader = new FileReader();
                let type = f ? f.type : input.files[0].type

                if (this.refComponent.$el.classList.contains('image')) {
                    if(type === 'image/jpeg' || type === 'image/png' || type === 'image/svg+xml'){
                        reader.readAsDataURL(f ? f : input.files[0]);
                        reader.onload = (e) => {
                            this.img = e.target.result;
                            this.showModal = true;
                            this.clearFileStorage();
                        };
                    }
                }

                if (this.refComponent.$el.classList.contains('audio')) {
                    if(type === 'audio/mpeg'){
                        this.uploadToserver(f);
                    }
                }

                if (this.refComponent.$el.classList.contains('video')) {
                    if(type === 'video/mp4'){
                        this.uploadToserver(f);
                    }
                }
            }
        },

        setImages(images){
            this.images = images;
        },

        cropImage(){
            this.result = this.$refs.cropper.getResult().canvas.toDataURL();
            axios({url: '/api/file/upload', data: {
                base_64_file : this.result,
                course_id : window.course.course.id,
                }, method: 'POST' })
                .then(resp => {
                    if(resp.data.status === 'ок'){
                        window.uploads.pushFile(resp.data.file)
                        if(this.refComponent){
                            if (typeof this.refComponent.placeFile === "function") {
                                this.refComponent.placeFile(resp.data.file);
                            }
                            this.closeModal();
                        }
                    }
                    // this.course = resp.data.course;
                    // window.uploads.setFiles(this.course.course_meta.attachments);
                    // window.upoloads.setFiles(this.course.course_meta.attachments);
                })
                .catch(err => {
                    console.log(err);
                })
        },

        uploadToserver(f){
            let formData = new FormData();
            formData.append("file", f ?? this.$refs.file.files[0]);
            formData.append("course_id", window.course.course.id);
            axios({
                url: '/api/file/upload',
                data: formData,
                method: 'POST',
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(resp => {
                    if(resp.data.status === 'ок'){
                        window.uploads.pushFile(resp.data.file)
                        if(this.refComponent){
                            if (typeof this.refComponent.placeFile === "function") {
                                this.refComponent.placeFile(resp.data.file);
                            }

                            this.closeModal();
                            this.clearFileStorage();
                        }
                    }
                })
                .catch(err => {
                    console.log(err);
                })
        },

        change({coordinates, canvas}) {
            if (!this.isLoadImage) {
                this.isLoadImage = true;
            }
        },

        flip(x,y) {
            this.$refs.cropper.flip(x,y);
        },

        rotate(angle) {
            this.$refs.cropper.rotate(angle);
        },
        
        clearFileStorage() {
            this.$refs.file.value = '';
        },

        closeModal() {
            this.isLoadImage = false;
            this.showModal = false;
        }
    }
}
</script>
<style>
</style>