<!-- Search -->
<div class="row">
    <!-- Search -->
    <div class="col-sm-8 col-lg-9">
        <div class="input-group input-group-merge">
            <input
                type="text"
                class="form-control search-product"
                id="shop-search"
                placeholder="{{ __('base.search') }}"
                aria-label="Search..."
                aria-describedby="shop-search"
                name="search"
                value=""
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
<div class="row mt-1">
    <div class="col-md-4">
        <label class="form-label">
            ?
        </label>
        <input
            type="text"
            class="form-control dt-input dt-full-name"
            placeholder="?"
            name=""
            value=""
        />
    </div>

    <div class="col-md-4">
        <label class="form-label">
            {{ __('base.status') }}
        </label>
        <div class="input-group input-group-merge">
            <select name="type" class="form-select pointer" id="basicSelect">
                <option value="" selected>
                    {{ __('base.all') }}
                </option>
                <option value="">
                    {{ __('base.withdraw') }}
                </option>
                
                <option value="">
                    {{ __('base.unavailable') }}
                </option>
                
                <option value="">
                    {{ __('base.withdrawn') }}   
                </option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">
            ?
        </label>
        <input
            type="text"
            class="form-control dt-input"
            placeholder="?"
            name=""
            value=""
        />
    </div>
</div>
