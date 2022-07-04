<div class="card">
    <div class="card-header row">
        <div class="col-sm-6">
            <h4 class="card-title">
                {{ __('statistic.amounts_payment_tariffs') }}
            </h4>
        </div>

        <div class="col-sm-6 mt-1 mt-sm-0">
            <div class="row">
                <div class="col-6">
                    <select
                        class="form-select form-select-sm pointer"
                        onchange="CommunityPage.statisticPage.chartsSwitcher.onChangeTariffAmountsRank(event)"
                    >
                        <option value="d">
                            {{ __('base.days_2') }}
                        </option>

                        <option value="m">
                            {{ __('base.months') }}
                        </option>

                        <option value="y">
                            {{ __('base.years') }}
                        </option>
                    </select>
                </div>

                <div class="col-6">
                    <select
                        class="form-select form-select-sm pointer"
                        data-tariff-amount-select="d"
                        onchange="CommunityPage.statisticPage.chartsSwitcher.onChangeTariffAmountsCount(event)"
                    >
                        <option value="7">
                            7 {{ __('base.days_low') }}
                        </option>
                        
                        <option value="14">
                            14 {{ __('base.days_low') }}
                        </option>
                        
                        <option value="21">
                            21 {{ __('base.day_many_low') }}
                        </option>
                        
                        <option value="28">
                            28 {{ __('base.days_low') }}
                        </option>
                    </select>

                    <select
                        class="form-select form-select-sm pointer hide"
                        data-tariff-amount-select="m"
                        onchange="CommunityPage.statisticPage.chartsSwitcher.onChangeTariffAmountsCount(event)"
                    >
                        <option value="6">
                            6 {{ __('base.months_2_low') }} 
                        </option>

                        <option value="12">
                            12 {{ __('base.months_2_low') }}
                        </option>

                        <option value="18">
                            18 {{ __('base.months_2_low') }}
                        </option>

                        <option value="24">
                            24 {{ __('base.months_3_low') }}
                        </option>

                    </select>

                    <select
                        class="form-select form-select-sm pointer hide"
                        data-tariff-amount-select="y"
                        onchange="CommunityPage.statisticPage.chartsSwitcher.onChangeTariffAmountsCount(event)"
                    >
                        <option value="5">
                            5 {{ __('base.years_2_low') }}
                        </option>

                        <option value="10">
                            10 {{ __('base.years_2_low') }}
                        </option>

                        <option value="15">
                            15 {{ __('base.years_2_low') }}
                        </option>

                        <option value="20">
                            20 {{ __('base.years_2_low') }}
                        </option>

                    </select>
                </div>
            </div>
        </div>
    </div>


    <div id="tariff-amounts-chart" class="card-body">
        <canvas class="line-chart-ex chartjs" data-height="450"></canvas>
    </div>
</div>
