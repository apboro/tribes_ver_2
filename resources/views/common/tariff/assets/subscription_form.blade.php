<section id="column-search-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="get">
                        <!-- Search -->
                        <div class="row">
                            <!-- Search -->
                            <div class="col-sm-8 col-lg-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control search-product" id="shop-search"
                                        placeholder="{{ __('base.search') }}" aria-label="Search..."
                                        aria-describedby="shop-search" name="search" value="" />

                                    <span class="input-group-text">
                                        <i data-feather="search" class="font-medium-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-4 col-lg-3 mt-1 mt-sm-0">
                                <button type="submit" class="btn btn-outline-primary waves-effect w-100">
                                    {{ __('base.show') }}
                                </button>
                            </div>
                        </div>

                        <!-- FILTERS -->
                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label class="form-label">
                                    {{ __('base.user') }}
                                </label>

                                <input type="text" class="form-control dt-input" data-column="3"
                                    placeholder="{{ __('base.user_name') }}" data-column-index="2" name="from"
                                    value="{{ request('from') }}" />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    {{ __('base.tariff') }}
                                </label>
                                <select name="tariff" class="form-select pointer" id="basicSelect">
                                    <option value="" @if (request('tariff') == null) selected @endif>
                                        {{ __('base.all') }}</option>
                                    @foreach ($community->tariff->variants as $variant)
                                        <option value="{{ $variant->id }}"
                                            @if (request('tariff') == $variant->id) selected @endif>
                                            {{ $variant->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    {{ __('base.payment_period') }}
                                </label>
                                <div class="input-group input-group-merge">
                                    <input type="date" name="date" value="{{ request('date') }}"
                                        class="form-control form-control-merge dt-input" data-column="2"
                                        data-column-index="1" tabindex="4" placeholder="" aria-describedby="password" />
                                    {{-- <span --}}
                                    {{-- class="input-group-text cursor-pointer" --}}
                                    {{-- ><i data-feather="calendar" class="font-medium-1"></i></span> --}}
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- TABLE -->
                    <form action="{{ route('community.tariff.subscriptionsChange', $community) }}" method="POST">
                        <div class="row align-items-center mt-1">
                            <div class="card-datatable table-responsive">
                                <table class="dt-column-search table table-striped" id="DataTables_Table_1" role="grid"
                                    aria-describedby="DataTables_Table_1_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_1"
                                                rowspan="1" colspan="1"
                                                aria-label="Post: activate to sort column ascending">
                                                Логин
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_1"
                                                rowspan="1" colspan="1"
                                                aria-label="Post: activate to sort column ascending">
                                                {{ __('base.user') }}
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_1"
                                                rowspan="1" colspan="1"
                                                aria-label="Date: activate to sort column ascending">
                                                {{ __('base.tariff') }}
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_1"
                                                rowspan="1" colspan="1"
                                                aria-label="Email: activate to sort column ascending">
                                                {{ __('payment.payment_date') }}
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_1"
                                                rowspan="1" colspan="1"
                                                aria-label="Date: activate to sort column ascending">
                                                {{ __('audience.days_left') }}
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_1"
                                                rowspan="1" colspan="1"
                                                aria-label="Date: activate to sort column ascending">
                                                {{ __('payment.exclude') }}
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (!count($followers))
                                            <tr c`lass="odd">
                                                <td valign="top" colspan="5" class="dataTables_empty">
                                                    Записи отсутствуют
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($followers as $follower)
                                                <tr class="odd">

                                                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                                                        <a href="{{ $follower->user_name ? 'https://t.me/' . $follower->user_name : '' }}"
                                                            class="btn-link">
                                                            {{ $follower->user_name ? $follower->user_name : '' }}
                                                        </a>
                                                    </td>

                                                    <td valign="top" colspan="1" class="dataTables_empty">
                                                        {{ $follower->first_name ? $follower->first_name . ' ' : '' }}
                                                        {{ $follower->last_name ? $follower->last_name : '' }}
                                                    </td>

                                                    <td valign="top" colspan="1" class="dataTables_empty">
                                                        
                                                        <select
                                                        class="form-select pointer "
                                                        id="tariff"
                                                        name="tariff[{{ $follower->id }}]"
                                                        >
                                                            @if ($community->tariff !== null)
                                                                <option value="{{ false }}"
                                                                @if (!empty($variant) && !$follower->getVariantById($variant->id))
                                                                selected
                                                                @endif
                                                                >{{ __('base.none') }}</option>
                                                                
                                                                @foreach ($community->tariffvariants as $variant)
                                                                    <option 
                                                                    value="{{ $variant->id }}"
                                                                    @if ($follower->getVariantById($variant->id)) 
                                                                    selected 
                                                                    @endif
                                                                    >
                                                                        {{ $variant->title }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        

                                                    </td>

                                                    <td valign="top" colspan="1" class="dataTables_empty">
                                                        @if ($follower->payment->last())
                                                            {{$follower->payment->last()->created_at->format('d.m.Y G:i:s')}}
                                                        @else
                                                            <input 
                                                                type="date" 
                                                                id="date_payment" 
                                                                class="form-control pointer" 
                                                                name="date_payment[{{ $follower->id }}]" 
                                                                value="{{false}}"
                                                            />
                                                            <input 
                                                                type="time" 
                                                                id="time_payment" 
                                                                class="form-control pointer" 
                                                                name="time_payment[{{ $follower->id }}]" 
                                                                value="{{false}}"
                                                            />
                                                        @endif
                                                        
                                                        {{-- {{ $follower->payment->last() ? $follower->payment->last()->created_at->format('d.m.Y G:i:s') : '—' }} --}}
                                                    </td>

                                                    <td valign="top" colspan="1" class="dataTables_empty">
                                                        <div class="col-sm-12 col-lg-12">
                                                            {{ $follower->getTariffById($community->tariff->id) ? $follower->getTariffById($community->tariff->id)->pivot->days : '—' }} 
                                                            {{-- <input type="text" class="form-control dt-input"
                                                                data-column="3"
                                                                value=""
                                                                data-column-index="2"
                                                                name="days[{{ $follower->id }}]" /> --}}
                                                        </div>
                                                    </td>

                                                    <td valign="top" colspan="1" class="dataTables_empty">
                                                        <div class="inactive-form-items">
                                                            <div class="row d-flex align-items-center">
                                                                <div class="col-md-12 col-12">
                                                                    <div class="d-flex align-items-center">
                                                                        <div
                                                                            class="form-check form-check-primary form-switch">
                                                                            <input type="hidden" value="false"
                                                                                name="excluded[{{ $follower->id }}]">
                                                                            <input type="checkbox"
                                                                                class="form-check-input pointer"
                                                                                value="true"
                                                                                name="excluded[{{ $follower->id }}]"
                                                                                @if ($follower->getCommunityById($community->id)) {{ $follower->getCommunityById($community->id)->pivot->excluded == true ? 'checked' : null }} @endif />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th rowspan="1" colspan="1">Логин</th>
                                            <th rowspan="1" colspan="1">{{ __('base.user') }}</th>
                                            <th rowspan="1" colspan="1">{{ __('base.tariff') }}</th>
                                            <th rowspan="1" colspan="1">{{ __('payment.payment_date') }}</th>
                                            <th rowspan="1" colspan="1">{{ __('audience.days_left') }}</th>
                                            <th rowspan="1" colspan="1">{{ __('payment.exclude') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-1 row">
                            <div class="col-sm-12 col-md-4">
                                <button type="submit" class="btn btn-outline-primary waves-effect w-100">
                                    {{ __('base.save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($followers->total() > 0)
                    <div class="card-footer">
                        <!-- Pagination -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap mx-0">
                            <div class="dataTables_info" id="DataTables_Table_2_info" role="status" aria-live="polite">
                                {{ __('base.shown_from') }}
                                {{ $followers->perPage() * $followers->currentPage() - $followers->perPage() + 1 }}
                                {{ __('base.to') }} {{ $followers->lastItem() }} {{ __('base.from') }}
                                {{ $followers->total() }} {{ __('base.entries_rus_low') }}
                            </div>

                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate">
                                {{ $followers->onEachSide(1)->appends(request()->input())->links(true ? 'vendor.pagination.bootstrap-4' : 'vendor.pagination.table-links') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
