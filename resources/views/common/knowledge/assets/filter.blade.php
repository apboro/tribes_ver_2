<div>
    <div class="row">
        <!-- Search -->
        <div class="col-sm-8 col-lg-9">
            <div class="input-group input-group-merge">
                <input
                    type="text"
                    class="form-control search-product"
                    id="shop-search"
                    placeholder="{{ __('knowledge.knowledge_search') }}"
                    aria-label="Search..."
                    aria-describedby="shop-search"
                    name="search"
                    value="{{ request('search') }}"
                />

                <span class="input-group-text">
                    <i data-feather="search" class="font-medium-1 text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-sm-4 col-lg-3 d-flex align-items-end mt-1 mt-sm-0">
            <button
                type="submit"
                class="btn btn-outline-primary waves-effect w-100"
            >
                {{ __('base.show') }}
            </button>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12">
            <div class="mb-1 form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="answer" checked>
                <label class="form-check-label text-nowrap" for="answer">
                    {{ __('knowledge.with_answers') }}
                </label>
            </div>
        
            <div class="mb-1 form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="no_answer">
                <label class="form-check-label text-nowrap" for="no_answer">
                    {{ __('knowledge.no_answers') }}
                </label>
            </div>
        
            <div class="mb-1 form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="archive">
                <label class="form-check-label text-nowrap" for="archive">
                    {{ __('knowledge.archive') }}
                </label>
            </div>

            <div class="mb-1 form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="from_community">
                <label class="form-check-label text-nowrap" for="from_community">
                    {{ __('knowledge.from_community') }}
                </label>
            </div>
        </div>
    </div>
</div>
