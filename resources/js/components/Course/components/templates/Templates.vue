<template>
    <div class="templates">
        <div class="templates__item" draggable="true" @dragstart="ondragStart($event, template)" @dragend="onDragEnd()"
            v-for="(template, index) in templates"
            :key="template.template_id"
            @click="selectTemplate(template.template_id)"
        >   
            <i v-html="getTemplateIconByTemplate(template)"></i>
            <span>{{ template.title }}</span>
        </div>
    </div>
</template>

<script>

export default {
    name: "Templates",
    data() {
        return {
            templates: [],
            activeTemplate: null,
            canPlace: false,
            dragElement: null,
        }
    },

    mounted(){
        window.templates = this;
        axios({url: '/api/lesson/templates', data: {}, method: 'GET' })
            .then(resp => {
                this.templates = resp.data.templates;
            })
            .catch(err => {
                console.log(err);
            });
        this.$root.$on('needsTemplate', (needle) => {
            if(this.canPlace){
                needle.temaplateRecieved(this.activeTemplate);
            }
        })
    },

    methods: {
        getTemplateIconByTemplate(template) {
            if (template.html.indexOf('video_module') > 0) {
                return '<svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M34.9091 5H10.0909C7.22727 5 5 7.22727 5 10.0909V34.9091C5 37.7727 7.22727 40 10.0909 40H34.9091C37.7727 40 40 37.7727 40 34.9091V10.0909C40 7.22727 37.7727 5 34.9091 5ZM32.0455 16.1364H36.8182V20.9091H32.0455V16.1364ZM16.1364 20.9091H28.8636V8.18182H16.1364V20.9091ZM12.9545 20.9091H8.18182V16.1364H12.9545V20.9091ZM12.9545 24.0909H8.18182V28.8636H12.9545V24.0909ZM16.1364 24.0909H28.8636V36.8182H16.1364V24.0909ZM36.8182 24.0909H32.0455V28.8636H36.8182V24.0909ZM36.8182 10.0909V12.9545H32.0455V8.18182H34.9091C36.0227 8.18182 36.8182 8.97727 36.8182 10.0909ZM12.9545 8.18182H10.0909C8.97727 8.18182 8.18182 8.97727 8.18182 10.0909V12.9545H12.9545V8.18182ZM8.18182 34.9091V32.0455H12.9545V36.8182H10.0909C8.97727 36.8182 8.18182 36.0227 8.18182 34.9091ZM32.0455 36.8182H34.9091C36.0227 36.8182 36.8182 36.0227 36.8182 34.9091V32.0455H32.0455V36.8182Z" fill="#6E6B7B"/><mask id="mask0_205_4063" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="5" width="35" height="35"><path fill-rule="evenodd" clip-rule="evenodd" d="M34.9091 5H10.0909C7.22727 5 5 7.22727 5 10.0909V34.9091C5 37.7727 7.22727 40 10.0909 40H34.9091C37.7727 40 40 37.7727 40 34.9091V10.0909C40 7.22727 37.7727 5 34.9091 5ZM32.0455 16.1364H36.8182V20.9091H32.0455V16.1364ZM16.1364 20.9091H28.8636V8.18182H16.1364V20.9091ZM12.9545 20.9091H8.18182V16.1364H12.9545V20.9091ZM12.9545 24.0909H8.18182V28.8636H12.9545V24.0909ZM16.1364 24.0909H28.8636V36.8182H16.1364V24.0909ZM36.8182 24.0909H32.0455V28.8636H36.8182V24.0909ZM36.8182 10.0909V12.9545H32.0455V8.18182H34.9091C36.0227 8.18182 36.8182 8.97727 36.8182 10.0909ZM12.9545 8.18182H10.0909C8.97727 8.18182 8.18182 8.97727 8.18182 10.0909V12.9545H12.9545V8.18182ZM8.18182 34.9091V32.0455H12.9545V36.8182H10.0909C8.97727 36.8182 8.18182 36.0227 8.18182 34.9091ZM32.0455 36.8182H34.9091C36.0227 36.8182 36.8182 36.0227 36.8182 34.9091V32.0455H32.0455V36.8182Z" fill="white"/></mask><g mask="url(#mask0_205_4063)"></g></svg>';
            } else if (template.html.indexOf('text_module') > 0) {
                return '<svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M39.13 21H25.87C24.748 21 24 20.2 24 19C24 17.8 24.748 17 25.87 17H39.13C40.252 17 41 17.8 41 19C41 20.2 40.252 21 39.13 21Z" fill="#6E6B7B"/><path d="M41 11C41 9.8 40.252 9 39.13 9H25.87C24.748 9 24 9.8 24 11C24 12.2 24.748 13 25.87 13H39.13C40.252 13 41 12.2 41 11Z" fill="#6E6B7B"/><path d="M41 26C41 24.8 40.24 24 39.1 24H4.9C3.76 24 3 24.8 3 26C3 27.2 3.76 28 4.9 28H39.1C40.24 28 41 27.2 41 26Z" fill="#6E6B7B"/><path d="M41 34C41 32.8 40.24 32 39.1 32H4.9C3.76 32 3 32.8 3 34C3 35.2 3.76 36 4.9 36H39.1C40.24 36 41 35.2 41 34Z" fill="#6E6B7B"/><path d="M18 10.32C18 11.049 17.409 11.64 16.68 11.64H13.5717V20.4184C13.5717 21.2919 12.8636 22 11.9901 22V22C11.1166 22 10.4086 21.2919 10.4086 20.4185V11.64H7.32C6.59098 11.64 6 11.049 6 10.32V10.32C6 9.59098 6.59098 9 7.32 9H16.68C17.409 9 18 9.59098 18 10.32V10.32Z" fill="#6E6B7B"/></svg>';
            } else if (template.html.indexOf('audio_module') > 0) {
                return '<svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M39.3 5.35C38.95 5 38.425 5 37.9 5L16.9 8.5C16.2 8.675 15.5 9.375 15.5 10.25V29.5H10.25C7.275 29.5 5 31.775 5 34.75C5 37.725 7.275 40 10.25 40H13.75C16.725 40 19 37.725 19 34.75V31.25V11.65L36.5 8.675V26H31.25C28.275 26 26 28.275 26 31.25C26 34.225 28.275 36.5 31.25 36.5H34.75C37.725 36.5 40 34.225 40 31.25V27.75V6.75C40 6.225 39.825 5.7 39.3 5.35ZM15.4999 34.75C15.4999 35.8 14.7999 36.5 13.7499 36.5H10.2499C9.19991 36.5 8.49991 35.8 8.49991 34.75C8.49991 33.7 9.19991 33 10.2499 33H15.4999V34.75ZM34.75 33C35.8 33 36.5 32.3 36.5 31.25V29.5H31.25C30.2 29.5 29.5 30.2 29.5 31.25C29.5 32.3 30.2 33 31.25 33H34.75Z" fill="#6E6B7B"/><mask id="mask0_205_4108" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="5" width="35" height="35"><path fill-rule="evenodd" clip-rule="evenodd" d="M39.3 5.35C38.95 5 38.425 5 37.9 5L16.9 8.5C16.2 8.675 15.5 9.375 15.5 10.25V29.5H10.25C7.275 29.5 5 31.775 5 34.75C5 37.725 7.275 40 10.25 40H13.75C16.725 40 19 37.725 19 34.75V31.25V11.65L36.5 8.675V26H31.25C28.275 26 26 28.275 26 31.25C26 34.225 28.275 36.5 31.25 36.5H34.75C37.725 36.5 40 34.225 40 31.25V27.75V6.75C40 6.225 39.825 5.7 39.3 5.35ZM15.4999 34.75C15.4999 35.8 14.7999 36.5 13.7499 36.5H10.2499C9.19991 36.5 8.49991 35.8 8.49991 34.75C8.49991 33.7 9.19991 33 10.2499 33H15.4999V34.75ZM34.75 33C35.8 33 36.5 32.3 36.5 31.25V29.5H31.25C30.2 29.5 29.5 30.2 29.5 31.25C29.5 32.3 30.2 33 31.25 33H34.75Z" fill="white"/></mask><g mask="url(#mask0_205_4108)"></g></svg>';
            } else if (template.html.indexOf('img_module') > 0) {
                return '<svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M34.75 5H10.25C7.275 5 5 7.275 5 10.25V34.75C5 37.725 7.275 40 10.25 40H34.75C37.725 40 40 37.725 40 34.75V10.25C40 7.275 37.725 5 34.75 5ZM8.49998 10.25C8.49998 9.20001 9.19998 8.50001 10.25 8.50001H34.75C35.8 8.50001 36.5 9.20001 36.5 10.25V23.55L30.725 17.775C30.025 17.075 28.975 17.075 28.275 17.775L9.72498 36.325C9.02498 36.15 8.49998 35.45 8.49998 34.75V10.25ZM14.45 36.5H34.75C35.8 36.5 36.5 35.8 36.5 34.75V28.45L29.5 21.45L14.45 36.5ZM16.375 20.75C18.825 20.75 20.75 18.825 20.75 16.375C20.75 13.925 18.825 12 16.375 12C13.925 12 12 13.925 12 16.375C12 18.825 13.925 20.75 16.375 20.75ZM17.25 16.375C17.25 15.85 16.9 15.5 16.375 15.5C15.85 15.5 15.5 15.85 15.5 16.375C15.5 16.9 15.85 17.25 16.375 17.25C16.9 17.25 17.25 16.9 17.25 16.375Z" fill="#6E6B7B"/><mask id="mask0_205_4090" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="5" width="35" height="35"><path fill-rule="evenodd" clip-rule="evenodd" d="M34.75 5H10.25C7.275 5 5 7.275 5 10.25V34.75C5 37.725 7.275 40 10.25 40H34.75C37.725 40 40 37.725 40 34.75V10.25C40 7.275 37.725 5 34.75 5ZM8.49998 10.25C8.49998 9.20001 9.19998 8.50001 10.25 8.50001H34.75C35.8 8.50001 36.5 9.20001 36.5 10.25V23.55L30.725 17.775C30.025 17.075 28.975 17.075 28.275 17.775L9.72498 36.325C9.02498 36.15 8.49998 35.45 8.49998 34.75V10.25ZM14.45 36.5H34.75C35.8 36.5 36.5 35.8 36.5 34.75V28.45L29.5 21.45L14.45 36.5ZM16.375 20.75C18.825 20.75 20.75 18.825 20.75 16.375C20.75 13.925 18.825 12 16.375 12C13.925 12 12 13.925 12 16.375C12 18.825 13.925 20.75 16.375 20.75ZM17.25 16.375C17.25 15.85 16.9 15.5 16.375 15.5C15.85 15.5 15.5 15.85 15.5 16.375C15.5 16.9 15.85 17.25 16.375 17.25C16.9 17.25 17.25 16.9 17.25 16.375Z" fill="white"/></mask><g mask="url(#mask0_205_4090)"></g></svg>';
            }   
        },

        selectTemplate(id) {
            console.log(id);
        },

        ondragStart(event, template) {
            this.dragElement = event.target;
            this.dragElement.classList.add('active');
            this.canPlace = true;
            this.activeTemplate = template;
        },

        onDragEnd() {
            this.dragElement.classList.remove('active');
            this.dragElement = null;
            this.dragElement = null;
            this.canPlace = false;
        },

        getRenderedHtml(template_id) {
            let template = this.templates.find((element, index, array) => {
                return element.template_id === template_id;
            })
            return template ? template.html : null;
        }
    }
}
</script>

<style scoped>
</style>