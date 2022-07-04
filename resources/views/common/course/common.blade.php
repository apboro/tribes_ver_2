@extends('common.course.edit')

@section('tab')
<div class="tab-pane active" id="common" aria-labelledby="common_tab" role="tabpanel">
    <form action="">
        <div class="card">
            <div class="card-header">
                <div class="col-7 col-lg-8">
                    <h4 class="card-title">
                        Основное
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
                <div class="row">
                    <!-- Заголовок курса -->
                    <div class="col-lg-6 mb-1">
                        <label for="course_title" class="form-label">
                            Заголовок курса
                        </label>

                        <input
                            type="text"
                            class="form-control @error('course_title') error @enderror"
                            id="course_title"
                            value=""
                            name="course_title"
                            placeholder="Заголовок курса"
                        />

                        {{--@error('course_title')
                            <span class="error">{{ $message }}</span>
                        @enderror--}}
                    </div>
                
                    <!-- Аннотация -->
                    <div class="col-lg-6 mb-1">
                        <label for="course_annotation" class="form-label">
                            Аннотация
                        </label>

                        <input
                            type="text"
                            class="form-control @error('course_annotation') error @enderror"
                            id="course_annotation"
                            value=""
                            name="course_annotation"
                            placeholder="Краткое изложение содержания"
                        />

                        {{--@error('course_annotation')
                            <span class="error">{{ $message }}</span>
                        @enderror--}}
                    </div>
                </div>
                <!-- Описание и превью -->
                @include('common.course.assets.course_description')
            </div>
        </div>
    </form>
</div>
@endsection
