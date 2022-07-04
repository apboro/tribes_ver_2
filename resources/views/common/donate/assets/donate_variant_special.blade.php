<div
    class="{{ ($donate && $donate->getVariantByIndex($index)->isActive) || old('donate.' . $index . '.status') ? null : 'inactive-form-items' }}"
    data-donate-item-id="{{ $index }}"
>
    <div class="row d-flex align-items-center">
        <div class="col-md-2 col-xl-1 col-2">   
            <div class="form-check form-check-primary form-switch">
                <input
                    type="checkbox"
                    class="form-check-input pointer"
                    id="donate_item_check_{{ $index }}"
                    value="false"
                    name="donate[{{ $index }}][status]"
                    data-donate-check-id="{{ $index }}"
                    onchange="CommunityPage.donatePage.donateSwitcher.onChangeDonateItemCheck(this)"
                    {{ ($donate && $donate->getVariantByIndex($index)->isActive) || old('donate.' . $index . '.status') ? 'checked' : null }}
                />
            </div>
        </div>

        <div class="col-md-10 col-xl-5 col-10">
            <div class="mb-1 mb-xl-0">
                <label class="form-label">
                    {{ __('base.arbitrary_amount') }}
                </label>

                <input
                    type="text"
                    class="form-control @error('donate[' . $index . '][description]') error @enderror"
                    id="donate_description_{{ $index }}"
                    name="donate[{{ $index }}][description]"
                    aria-describedby="donate_description_{{ $index }}"
                    placeholder="{{ __('donate.donate_special_variant_description') }}"
                    value="{{ $donate && $donate->getVariantByIndex($index)->description ? $donate->getVariantByIndex($index)->description : old('donate.' . $index . '.description') }}"
                />

                <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                    <i data-feather='save' class="font-medium-1" ></i>
                </span>
            </div>
        </div>

        <div class="col-sm-5 col-md-5 col-xl-2 col-12">
            <div class="mb-1 mb-sm-0">
                <label class="form-label">
                    {{ __('base.amount_min') }}
                </label>

                <input
                    type="number"
                    class="form-control @error('donate.' . $index . '.min_price') error @enderror"
                    id="donatemin_min_cost_2"
                    aria-describedby="donate_max_cost_2"
                    name="donate[{{ $index }}][min_price]"
                    placeholder="100"
                    value="{{ $donate && $donate->getVariantByIndex($index)->min_price ? $donate->getVariantByIndex($index)->min_price : old('donate.' . $index . '.min_price') }}"
                />

                <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                    <i data-feather='save' class="font-medium-1" ></i>
                </span>
            </div>
        </div>

        <div class="col-sm-5 col-md-4 col-xl-2 col-12">
            <div class="mb-1 mb-sm-0">
                <label class="form-label">
                    {{ __('base.amount_max') }}
                </label>

                <input
                    type="number"
                    class="form-control @error('donate.' . $index . '.max_price') error @enderror"
                    id="donatemin_max_cost_2"
                    aria-describedby="donate_max_cost_2"
                    name="donate[{{ $index }}][max_price]"
                    placeholder="1000"
                    value="{{ $donate && $donate->getVariantByIndex($index)->max_price ? $donate->getVariantByIndex($index)->max_price : old('donate.' . $index . '.max_price') }}"
                />

                <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                    <i data-feather='save' class="font-medium-1" ></i>
                </span>
            </div>
        </div>

        <div class="col-sm-2 col-md-3 col-xl-2 col-12">
            <div class="mb-1 mb-sm-0">
                <label class="form-label">
                    {{ __('base.currency') }}
                </label>

                @include('common.donate.assets.currency_selector', [
                   'id' => 'donate_currency_' . $index,
                   'name' => 'donate[' . $index . '][currency]',
                   'currencies' => App\Models\Donate::getCurrencyData($donate, $index),
                   'active' => $donate && $donate->getVariantByIndex($index)->isActive])
            </div>
        </div>
    </div>
    <hr />
</div>
