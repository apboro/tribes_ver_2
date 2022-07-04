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
            {{ __('base.community') }}
        </label>
        <div class="input-group input-group-merge">
            <select name="community" class="form-select pointer" id="basicSelect">
                <option value="" @if (request('community') == NULL) selected @endif>
                    {{ __('base.all') }}
                </option> 
                @if ($allCommunityes)
                    @foreach ($allCommunityes as $com)
                    <option value="{{ $com->id }}" @if (request('community') == $com->id) selected @endif>
                        {{$com->title}}
                    </option>
                    @endforeach
                @endif
                   
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">
            {{ __('base.status') }}
        </label>
        <div class="input-group input-group-merge">
            <select name="type" class="form-select pointer" id="basicSelect">
                <option value="" @if (request('type') == NULL) selected @endif>
                    {{ __('base.all') }}
                </option>    

                <option value="bought" @if (request('type') == 'bought') selected @endif>
                    {{ __('audience.purchased') }}
                </option>
                
                <option value="trial" @if (request('type') == 'trial') selected @endif>
                    {{ __('base.trial_period') }}
                </option>
                
                
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">
            {{ __('audience.role') }}
        </label>
        <div class="input-group input-group-merge">
            <select name="role" class="form-select pointer" id="basicSelect">
                <option value="" @if (request('role') == NULL) selected @endif>
                    {{ __('base.all') }}
                </option>    

                <option value="admin" @if (request('role') == 'admin') selected @endif>
                    Администраторы
                </option>
                
                <option value="member" @if (request('role') == 'member') selected @endif>
                    Участники
                </option>

                <option value="excluded" @if (request('role') == 'excluded') selected @endif>
                    {{ __('audience.ban') }}
                </option>
            </select>
        </div>
    </div>
</div>
