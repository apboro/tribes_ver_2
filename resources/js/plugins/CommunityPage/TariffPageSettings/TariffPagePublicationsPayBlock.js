import { debounce } from "../../../functions";
import { ContentEditor } from "../../Helper/ContentEditor";
import { ImagePreview } from "../../Helper/ImagePreview";
import { CropImageOnMoveCroppController } from "./CropImageOnMoveCroppController";

export class TariffPagePublicationsPayBlock {
    constructor(options) {
        this.container = options.parent;
        this.previewContainer = this.container.querySelector('#pay_block_preview_container');
        this.previewTitle = this.previewContainer.querySelector('#pay_block_preview_title');
        this.previewEditor = this.previewContainer.querySelector('#pay_block_preview_editor_container');

        this.editorDataEl = this.container.querySelector('#editor_data');

        this.tariffPayEditorEvent = 'ContentEditor:text';

        this.init();
    }

    init() {
        this.initCroppImageController();
        this.initListeners();
        this.initEditor();
        this.initImagePreview();
    }

    initCroppImageController() {
        this.croppImageControllerPay = new CropImageOnMoveCroppController({
            container: this.container,
            id: 'pay',
        });
    }

    initListeners() {
        // Подписываемся на событие изменения
        Emitter.subscribe(this.tariffPayEditorEvent, (data) => {
            this.setEditorData = data.html;
            this.previewEditor.innerHTML = data.html;
        });
    }

    initEditor() {
        this.editor = new ContentEditor(this.container, '#editor_container', this.tariffPayEditorEvent);
    }

    initImagePreview() {
        this.imagePreview = new ImagePreview({
            parent: this.container,
            selector: '#preview_img',
            id: 'pay',
        });
    }

    onInputTitle(event) {
        debounce(() => {
            this.previewTitle.textContent = event.target.value;
        }, 300)()
    }

    set setEditorData(value) {
        this.editorDataEl.value = value;
    }
}
