@extends('common.community.profile')

@section('tab')
    <section data-tab="statisticPage" data-community-id="{{ $community->id }}">
        <div class="row">
            <!-- Tariff Amounts chart -->
            <div class="col-12">
                @include('common.statistic.assets.tariff_amount')
            </div>

            <!-- Unique Visitors chart -->
            <div class="col-xl-4 col-sm-6 col-12 mb-2">
                @include('common.statistic.assets.unique_visitors')
            </div>

            <!-- Paid Tariffs Count chart -->
            <div class="col-xl-4 col-sm-6 mb-2">
                @include('common.statistic.assets.paid_tariffs_count')
            </div>

            <!-- Paid Donations Count chart -->
            <div class="col-xl-4 col-sm-6 mb-2">
                @include('common.statistic.assets.paid_donations_count')
            </div>
            

            <!--Bar Chart Start -->
            <!--<div class="col-xl-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-sm-center align-items-start">
                        <div class="header-left">
                            <h4 class="card-title">Подписчики</h4>
                        </div>

                        <div style="flex: 0 1 165px">
                            <select
                                class="form-select form-select-sm pointer"
                                onchange="CommunityPage.statisticPage.chartsSwitcher.onChangeSubscribersSelect(event)"
                            >
                                <option value="week">За неделю</option>
                                <option value="year">За год</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="bar-chart-container">
                            <canvas class="chartjs" ></canvas>
                        </div>
                    </div>
                </div>
            </div>-->
            <!-- Bar Chart End -->

            <!-- Donut Chart Starts -->
            <!--<div class="col-xl-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-sm-center align-items-start">
                        <div class="header-left">
                            <h4 class="card-title">Просмотры</h4>
                        </div>

                        <div style="flex: 0 1 165px">
                            <select
                                class="form-select form-select-sm pointer"
                                onchange="CommunityPage.statisticPage.chartsSwitcher.onChangeDoughnutSelect(event)"
                            >
                                <option value="week">За неделю</option>
                                <option value="year">За год</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="doughnut-chart-container">
                            <canvas class="chartjs" data-height="300" data-start-category="week"></canvas>
                        </div>
                        
                        <div id="doughnut_chart_description"></div>
                    </div>
                </div>
            </div>-->
            <!-- Donut Chart Starts -->
        </div>
    </section>
@endsection
