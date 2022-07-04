@extends('common.lesson.edit')

@section('tab')
<div data-tab="CommonPage">
    
        <div class="card">
            <div class="card-header">
                <div class="col-7 col-lg-8">
                    <h4 class="card-title">
                        Редактирование урока
                    </h4>
                </div>
                    
                <div class="col-sm-4 col-lg-3 col-xl-2">
                    <!-- Submit -->
                    <button
                        class="btn w-100 btn-icon btn-success d-flex align-items-center justify-content-center"
                        type="submit"
                        data-repeater-create
                        value="true"
                        name="settingsUpdate"
                        onclick='LessonPage.commonPage.saveLesson()'
                    >
                        <i data-feather="save" class="font-medium-1"></i>
                        <span class="d-none d-sm-inline-block ms-1">
                            {{ __('base.save') }}
                        </span>
                    </button>
                </div>
            </div>    
        </div>

        <div id="content_container"></div>

        <div id="add_module_btn" class="card text-center" onclick="LessonPage.commonPage.templatesPanel.open()">
            <div class="text-reset p-2 pointer">
                <div class="fs-4 mb-1 text-muted opacity-50">
                    <i data-feather='package'></i>
                </div>
                <h3 class="mb-1">Добавить HTML модуль</h3>
                <div class="text-muted">Выберите подходящий html шаблон для заполнения</div>
            </div>
        </div>

        <div class="card">
            <div class="col-6" id="editor_container">
                <div id="editor" class=""></div>
            </div>

            <div style="display: none" id="content">
                <h1>title1</h1>
                <h3>title3</h3>
                <p><strong>With</strong> a very crude menu bar.</p>
                <ul>
                    <li>1</li>
                </ul>
                <code>code</code>
            </div>
        </div>
    
</div>
@endsection
