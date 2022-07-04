@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0" id="coursePage" data-plugin="CoursePage">
        <!-- Breadcrumbs block -->
        @include('common.course.assets.edit_breadcrumbs')

        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <div class="row">
                    <div class="col-9">
                        <ul class="tabs d-flex nav nav-pills mb-1 gap-1">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">
                                    <span class="fw-bold">
                                        Медиаконтент
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#">
                                    <span class="fw-bold">
                                        Медиаконтент
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item ml-auto">
                                <a
                                    class="nav-link active"
                                    href="#"
                                    onclick="CoursePage.saveCourse()"
                                >
                                    <span class="fw-bold">
                                        Сохранить
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-3">
                        <div>
                            <h2>Прикреплённые файлы</h2>
                        </div>
                    </div>
                    <div class="col-9">
                        <div>
                            <h2>Прикреплённые файлы</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9 d-flex">
                        <div class="sideNav">
                            <ul id="tabNavs" class="courses-nav nav"></ul>

                            <div class="newTabContainer">
                                <button
                                    id="newTab"
                                    class="btn btn-outline-success waves-effect w-100"
                                    onclick="CoursePage.newLesson()"
                                >
                                    <i data-feather="plus" class="font-medium-1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card course-container w-100" >
                            <div id="lessonContainer" class="card-body">

                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card files-container">
                            <div class="card-body">
                                <label for="files" class="btn btn-icon btn-warning waves-effect waves-float waves-light pointer">
                                    <i data-feather='upload'></i>
                                </label>

                                <input type="file" id="files" class="hide" onchange="CoursePage.fileController.load(this)">

                                <div id="files_list" class="files"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>










{{--        <div class="content-wrapper container-xxl p-0">--}}
{{--            <div class="content-body">--}}
{{--                <section class="app-user-view-account">--}}
{{--                    <div class="row">--}}
{{--                        <!-- Profile Content -->--}}
{{--                        <div class="col-12">--}}
{{--                            <!-- Profile nav -->--}}
{{--                            @include('common.course.assets.edit_nav')--}}
{{--                            --}}
{{--                            <!-- Tabs -->--}}
{{--                            @yield('tab')--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </section>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection
