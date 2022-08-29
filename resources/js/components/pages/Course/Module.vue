<template>
    <div class="modules__module" ref="module" :class="isModuleActive ? 'active' : ''" @click="toggleActivityModule">
        <div class="" ref="cont">
            <div v-html="html"></div>
        </div>
        
        <div class="modules__auxiliary-btns">
            <button class="small-btn modules__auxiliary-btn" @click="removeModule()">
                <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19 4H15V3C15 1.3 13.7 0 12 0H8C6.3 0 5 1.3 5 3V4H1C0.4 4 0 4.4 0 5C0 5.6 0.4 6 1 6H2V19C2 20.7 3.3 22 5 22H15C16.7 22 18 20.7 18 19V6H19C19.6 6 20 5.6 20 5C20 4.4 19.6 4 19 4ZM7 3C7 2.4 7.4 2 8 2H12C12.6 2 13 2.4 13 3V4H7V3ZM15 20C15.6 20 16 19.6 16 19V6H4V19C4 19.6 4.4 20 5 20H15ZM9 10V16C9 16.6 8.6 17 8 17C7.4 17 7 16.6 7 16V10C7 9.4 7.4 9 8 9C8.6 9 9 9.4 9 10ZM13 16V10C13 9.4 12.6 9 12 9C11.4 9 11 9.4 11 10V16C11 16.6 11.4 17 12 17C12.6 17 13 16.6 13 16Z" fill="white"/></svg>
            </button>
        </div>
    </div>

</template>

<script>
    import {CreateNode} from "../../../plugins/Helper/CreateNode";
    import Vue from 'vue';
    export default {
        name: "Module",
        data() {
            return {
                module:{
                    id:0,
                    index:0,
                    template_id:0,
                },
                moduleNodes:[],
                html:'',
                isModuleActive: false,
            }
        },

        mounted() {
            window.modules = [];
            this.module = this.$attrs.module;
            this.renderModule();
            this.$on('fresh', () => {
                this.renderModule();
            });
            this.$on('propChanged', (data) => {
                this.module[data.key] = data.value;
            })
        },
        
        methods:{
            renderModule(){
                let html = window.templates.getRenderedHtml(this.module.template_id);
                this.parseTags(html);
            },

            parseTags(html) {
                let record = false;
                let tag = '';
                let moduleHTML = html;
                if(moduleHTML){
                    for (let i = 0; i < html.length; i++) {
                        if (html.charAt(i) === '[' && html.charAt(i + 1) === '[' ) {
                            record = true;
                        }
                        if (html.charAt(i) === ']' && html.charAt(i + 1) === ']' ) {
                            record = false;
                            let moduleData = tag.split('_');
                            let d = null;
                            d = this.module[tag];
                            const cont = new CreateNode({
                                id: 'module_' + this.module.index + '_' + tag
                            }).init();
                            this.moduleNodes.push(cont);
                            moduleHTML = moduleHTML.replace('[[' + tag + ']]', cont.outerHTML);
                            tag = '';
                        }
                        if (record) {
                            tag += html.charAt(i + 1).replace('[', '').replace(']', '');
                        }
                    }
                    this.$refs.cont.innerHTML = moduleHTML;
                    // this.html = moduleHTML;
                    this.mountMedia();
                }
            },

            mountMedia(){
                setTimeout(() => {
                    this.moduleNodes.forEach((item) => {
                        let chunk = item.id.split('_');
                        if(chunk[2] !== 'settings' && chunk.length > 1){


                            if(!this.module[chunk[2] + '_' + chunk[3]]){
                                this.module[chunk[2] + '_' + chunk[3]] = 0;
                            }
                            let ch = this.module[chunk[2] + '_' + chunk[3]];
                            let instance = new window[chunk[2]]({
                                parent: this,
                                propsData: {k : chunk[2] + '_' + chunk[3], value: ch}
                            });
                            this.$children.push(instance);
                            let el = this.$el.querySelector('#module_' + this.module.index + '_' + chunk[2] + '_' + chunk[3]);
                            instance.$mount(el);
                           window.modules.push(instance);
                        }
                    });
                }, 1);
            },
            removeModule() {
                let Vuelesson = this.$parent.$parent.$parent;
                let index = Vuelesson.lesson.modules.indexOf(this.module);

                this.$store.commit('removeModule', {
                    'lesson_id': Vuelesson.lesson.id,
                    'module_index': index
                });
            },

            toggleActivityModule() {
                this.isModuleActive = !this.isModuleActive;

                window.eListner = document.addEventListener('click', (e) => {
                    let container = this;
                    if (container.$refs.module && !container.$refs.module.contains(e.target)){
                        container.isModuleActive = false;
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>