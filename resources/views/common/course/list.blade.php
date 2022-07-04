@extends('layouts.app')

@section('content')

    <div class="content-wrapper container-xxl p-0">
        <!-- Breadcrumbs block -->
        <div class="content-header row align-items-center" id="bredacrumbs">
            <div class="col-8 col-sm-6 col-md-8 col-lg-8">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0 border-0">
                            {{ __('base.mediaProducts') }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-4 col-sm-6 col-md-4 col-lg-4">
                <div class="text-end mb-0">
                    <a
                        class="btn btn-success text-white"
                        href="{{ route('course.new') }}"
                    >
                        <i data-feather='plus' class="font-medium-1"></i>
                        <span class="d-none d-sm-inline-block ms-1">Новый медиатовар</span>
                    </a>
                </div>
            </div>
            
        </div>
        <div> Сортировать по </div>
        <a href="{{ request()->fullUrlWithQuery(
            ['sort' => 'date', 'dir' => request()->get('dir') === 'asc' ? 'desc' : 'asc']
        ) }}">дате</a> |
        <a href="{{ request()->fullUrlWithQuery(
            ['sort' => 'access', 'dir' => request()->get('dir') === 'asc' ? 'desc' : 'asc']
            ) }}">сроку доступа</a> |
        <a href="{{ request()->fullUrlWithQuery(
            ['sort' => 'cost', 'dir' => request()->get('dir') === 'asc' ? 'desc' : 'asc']
            ) }}">стоимости</a> |
        <a href="{{ request()->fullUrlWithQuery(['sort' => '', 'dir' => '']) }}">очистить фильтр</a>
        <div class="mt-2" data-plugin="CommunitiesPage">
            
            <div class="row">
               
                @forelse($courses as $course)
                    <!-- Cards -->
                    <div class="col-sm-4 col-md-3 col-lg-2">
                        <div class="card community-item">
                            <!-- <a href="{{ route('course.edit', ['id' => $course->id]) }}"> -->
                                <img
                                    class="card-img-top"
                                    src="{{ $course->preview()->first()->url ?? "/images/no-image.svg" }}"
                                    alt="Card image cap"
                                />

                                <div class="card-body product__body">
                                    <h4 class="card-title community-item__title" title="{{ $course->title }}">
                                        {{ $course->title }}
                                    </h4>
                                    <div class="">
                                        Просмотрели: {{ $course->views }}
                                    </div>
                                    <div class="">
                                        Нажали "Купить": {{ $course->clicks }}
                                    </div>
                                    <div class="mb-1">
                                        Купили: {{ $course->shipped_count }}
                                    </div>

                                    <div class="product__btns">
                                        <a
                                            href="{{ $course->paymentLink() }}"
                                            class="product__icon product__icon--first"
                                            title="Страница покупки товара"
                                            target="_blank"
                                        >
                                            <i data-feather='link'></i>
                                        </a>

                                        <a
                                            href="{{ route('course.edit', ['id' => $course->id]) }}"
                                            class="product__icon"
                                            title="Редактировать"
                                        >
                                            <i data-feather='edit-2'></i>
                                        </a>

                                        <a
                                            href="{{ $course->getProductWithLesson($course->getOrderedLessons()->first()->id ?? 0) }}"
                                            class="product__icon"
                                            title="Страница товара"
                                            target="_blank"
                                        >
                                            <i data-feather='eye'></i>
                                        </a>
                                    </div>

                                    <div class="badge-list badge badge-up community-badge">
                                        @if($course->isPublished)
                                            <span class="badge-glow bg-success">
                                                Опубликовано
                                            </span>
                                        @else
                                            <span class="badge-glow bg-danger">
                                                Не опубликовано
                                            </span>
                                        @endif

                                        @if($course->isEthernal)
                                            <span class="badge-glow bg-info">
                                                Бессрочный
                                            </span>
                                        @else
                                            <span class="badge-glow bg-info">
                                                Доступно: {{ $course->access_days }} дн.
                                            </span>
                                        @endif
                                            <span class="badge-glow bg-info">
                                                Стоимость: {{ $course->cost }} руб.
                                            </span>
                                    </div>
                                </div>
                            <!-- </a> -->
                        </div>
                    </div>

                @empty
                    <!-- Empty list -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-12">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="text-center">
                                                Медиатоваров пока нет, вы можете
                                                <a
                                                        class="btn btn-success text-white"
                                                        href="{{ route('course.new') }}"
                                                >
                                                    <i data-feather='plus' class="font-medium-1"></i>
                                                    <span class="d-none d-sm-inline-block ms-1">Создать новый медиатовар</span>
                                                </a>
                                            </h5>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Tabs end -->
    </div>
@endsection
