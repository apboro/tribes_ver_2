@extends('common.course.edit')

@section('tab')
<div class="tab-pane" id="settings" aria-labelledby="settings_tab" role="tabpanel">
    <form action="">
        <div class="card">
            <div class="card-header">
                <div class="col-7 col-lg-8">
                    <h4 class="card-title">
                        Настройки
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
                    >
                        <i data-feather="save" class="font-medium-1"></i>
                        <span class="d-none d-sm-inline-block ms-1">
                            {{ __('base.save') }}
                        </span>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex align-items-center mb-1">
                    <label class="form-check-label" for="published">Опубликовано</label>
                    
                    <div class="form-check form-check-primary form-switch ms-1">
                        <input type="checkbox" name="" checked="" class="form-check-input" id="published">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
