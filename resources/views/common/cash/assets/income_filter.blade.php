<div class="row">
    <!-- Search -->
    <div class="col-sm-8 col-lg-9">
        <div class="input-group input-group-merge">
            <input
                name="search"
                type="search"
                class="form-control"
                placeholder=""
                aria-controls="DataTables_Table_2"
                value="{{ request('search') }}"
            />

            <span class="input-group-text">
                <i data-feather="search" class="font-medium-1 text-muted"></i>
            </span>
        </div>
    </div>

    <div class="col-sm-4 col-lg-3 mt-1 mt-sm-0">
        <button
            type="submit"
            class="btn btn-outline-primary waves-effect w-100"
        >
            {{ __('base.show') }}
        </button>
    </div>
</div>

<!-- FILTERS -->
<div class="row">
    <div class="col-md-3 col-xl-2 mt-1">
        <label class="form-label">
            {{ __('payment.pay_id') }}
        </label>

        <input
            type="text"
            class="form-control dt-input dt-full-name"
            data-column="1"
            placeholder="123"
            data-column-index="0"
            name="id"
            value="{{ request('id') }}"
        />
    </div>

    <div class="col-md-5 col-xl-3 mt-1">
        <label class="form-label">
            {{ __('base.user') }}
        </label>

        <input
            type="text"
            class="form-control dt-input"
            data-column="3"
            placeholder="{{ __('base.user_name') }}"
            data-column-index="2"
            name="from"
            value="{{ request('from') }}"
        />
    </div>

    <div class="col-md-4 col-xl-2  mt-1">
        <label class="form-label">
            {{ __('base.payment_period') }}
        </label>
        
        <input
            type="date"
            name="date"
            value="{{ request('date') }}"
            class="form-control form-control-merge dt-input"
            data-column="2"
            data-column-index="1"
            tabindex="4"
            placeholder=""
            aria-describedby="password"
        />
    </div>

    <div class="col-md-3 col-xl-2 mt-1">
        <label class="form-label">
            {{ __('payment.payment_type') }}
        </label>

        <select name="type" class="form-select pointer" id="basicSelect">
            <option value="" @if(request('type') == null) selected @endif>{{ __('base.all') }}</option>
            <option value="payout" @if(request('type') == 'payout') selected @endif >{{ __('base.payout') }}</option>
            <option value="tariff" @if(request('type') == 'tariff') selected @endif >{{ __('base.tariff') }}</option>
            <option value="donate" @if(request('type') == 'donate') selected @endif>{{ __('base.donation') }}</option>
            <option value="course" @if(request('type') == 'course') selected @endif>{{ __('base.media_content') }}</option>
        </select>
    </div>

    <div class="col-md-5 col-xl-3 mt-1">
        <label class="form-label">
            {{ __('base.community') }}
        </label>
        
        <select name="community" class="form-select pointer" id="basicSelect">
            <option value=""  @if(request('community') == null) selected @endif>{{ __('base.all') }}</option>
            @foreach(Auth::user()->getOwnCommunities() as $community)
            <option value="{{ $community->id }}" @if(request('community') == $community->id) selected @endif >{{ $community->title }}</option>
            @endforeach
        </select>
    </div>
</div>
