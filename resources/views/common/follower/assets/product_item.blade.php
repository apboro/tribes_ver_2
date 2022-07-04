<div class="col-sm-4 col-md-3 col-lg-2">
    <div class="card mb-3">
        <a href="{{ $course->getProductWithLesson($course->getOrderedLessons()->first()->id ?? 0) }}">
        <div class="row g-0">
            <div class="col-4 col-sm-12">
                <img src="{{ $course->preview()->first()->url ?? "/images/no-image.svg" }}" class="card-img-top" alt="...">
            </div>
            <div class="col-8 col-sm-12">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <span class="card-text text-muted d-flex align-items-center justify-content-center">
                        <span>{{ __('base.follow') }}</span>
                        <i data-feather="chevrons-right" class="font-medium-3 ms-1"></i>
                    </span>
                    
                </div>
            </div>
            
        </div>
        </a>
        <span class="badge badge-glow bg-info badge-up product-badge">
            До {{ date('d.m.Y', strtotime($course->pivot->expired_at)) }}
        </span>
    </div>
</div>
