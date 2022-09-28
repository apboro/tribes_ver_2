<div class="community-settings__form-item w-100">
    <div id="editor_container" class="">

        <div id="snow-container">
            <div id="toolbar">
                <span class="ql-formats">
                    <select class="ql-header">
                        <option value="1">Heading</option>
                        <option value="2">Subheading</option>
                        <option selected>Normal</option>
                    </select>
                </span>

                <span class="ql-formats">
                    <button class="ql-bold">b</button>
                    <button class="ql-italic">i</button>
                    <button class="ql-underline">u</button>
                </span>

                <span class="ql-formats">
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                </span>

                <span class="ql-formats">
                    <button class="ql-link"></button>
                </span>
                
                <span class="ql-formats">
                    <button class="ql-clean"></button>
                </span>
            </div>
            
            
            <div class="ql-editor-tariff" data-editor>@if($community->tariff && $community->tariff->welcome_description){{$community->tariff->welcome_description}}@endif</div>

            <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                <i data-feather='save' class="font-medium-1" ></i>
            </span>
            
        </div>
        
        <input type="hidden" id="editor_data" name="welcome_editor_data" value="123">
    </div>
    @error('editor_data')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>
