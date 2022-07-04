@extends('common.course.edit')

@section('tab')
<div class="tab-pane" id="education_materials" aria-labelledby="education_materials_tab" role="tabpanel">
    <div class="row">
        @if(1===1)
            <!-- Cards -->
            <div class="col-sm-4 col-md-3 col-lg-2">
                <div class="card community-item">
                    <a href="{{ route('lesson.edit.common') }}">
                        <img
                            class="card-img-top"
                            src="/images/no-image.svg"
                            alt="Card image cap"
                        />

                        <div class="card-body">
                            <h4 class="card-title community-item__title" title="">
                                Lesson 1
                            </h4>

                            <span class="card-text text-muted d-flex align-items-center justify-content-center">
                                <span>{{ __('base.follow') }}</span>
                                <i data-feather="chevrons-right" class="font-medium-3 ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

        @else
            <!-- Empty list -->
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-12">
                            <div class="d-flex flex-column align-items-center">
                                <div class="col-md-8">
                                    <h5 class="text-center">
                                        Пусто
                                    </h5>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
