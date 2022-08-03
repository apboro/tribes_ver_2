<template>
    <div class="text">
        <div class="media-content-text-formatting">
            <div ref="editor" @drop.prevent></div>
        </div>

        <div class="modules__label">Текст</div>
    </div>
</template>

<script>
import Quill from "quill";

export default {
    name: "Text",
    props: [ 'k', 'value' ],
    
    data () {
        return {
            data: {
                value: '',
                key: ''
            },
            quill: null,
        }
    },

    mounted() {
        this.saveData();
        this.initQuill();
    },
   
    methods: {
        saveData() {
            this.data.value = this.$props.value;
            this.data.key = this.$props.k;
        },

        initQuill() {
            this.quill = new Quill(this.$refs.editor, {
                theme: 'bubble',
                bounds: this.$refs.editor,
                scrollingContainer: '.ql-editor-text',
                modules: {
                    toolbar: {
                        container: [
                            [{ 'header': [2, 3, 4, false] }], // заголовки
                            ['bold', 'italic', 'underline', 'strike'], // жирный, курсив, подчеркнутый, зачеркнутый
                            ['link'], // ссылка
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }], // списки нумерованный, точечный
                            [{ 'align': [] }], // положение текста                    
                            [{ 'script': 'sub'}, { 'script': 'super' }],      // выше/ниже строки
                            ['clean'], // очистить форматирование
                        ],
                    }
                },
            });

            if (this.data.value) {
                this.quill.root.innerHTML = this.data.value;
            }

            this.quill.on('text-change', (eventName, ...args) => {
                this.data.value = this.quill.root.innerHTML;
                this.$parent.$emit('propChanged', this.data)
            });
        }
    },
   
}
</script>

<style scoped lang="scss">
</style>