<div class="card mb-0 h-100">
    <p class="card-text px-1 mt-1 mb-0">
        {{ __('statistic.subscribers') }}
    </p>

    <div class="card-header flex-nowrap justify-content-start pb-0 mt-auto">
        <div class="avatar bg-light-success p-50 m-0 me-1">
            <div class="avatar-content">
                <i data-feather="user-plus" class="font-medium-5"></i>
            </div>
        </div>
        
        <div class="d-flex align-items-center">
            <p class="card-text mb-0 me-1">
                {{ __('base.total') }}:
            </p>

            <h2 class="fw-bolder m-0">
                {{ $community->statistic->repository()->getPaidSubscribers() }}
            </h2>
        </div>
    </div>

    <p class="card-text px-1 mt-1 mb-0">
        {{ __('statistic.for_last_week') }}
    </p>
    
    <div id="paid-tariffs-count-chart" class="">
        <canvas class="line-chart-ex chartjs" data-height="100"></canvas>
    </div>
</div>
