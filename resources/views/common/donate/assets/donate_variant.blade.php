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
                    value="true"
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
                    {{ __('base.button') }} №{{ $index + 1 }}
                </label>
                <input
                    type="text"
                    class="form-control @error('donate[' . $index . '][description]') error @enderror"
                    id="donate_description_{{ $index }}"
                    name="donate[{{ $index }}][description]"
                    aria-describedby="donate_description_{{ $index }}"
                    placeholder="{{ __('donate.donate_variant_description') }}"
                    value="{{ $donate && $donate->getVariantByIndex($index)->description ? $donate->getVariantByIndex($index)->description : old('donate.' . $index . '.description') }}"
                />

                <input type="hidden" name="donate[{{ $index }}][variant_id]" value="{{$donate && $donate->getVariantByIndex($index)->id ? $donate->getVariantByIndex($index)->id : ""}}">
                <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                    <i data-feather='save' class="font-medium-1" ></i>
                </span>
            </div>
        </div>

        <div class="col-md-9 col-lg-4 col-8">
            <label class="form-label">
                {{ __('base.amount') }}
            </label>

            <input
                type="number"
                class="form-control @error('donate.' . $index . '.cost') error @enderror"
                id="donate_cost_{{ $index }}"
                aria-describedby="donate_cost_{{ $index }}"
                name="donate[{{ $index }}][cost]"
                placeholder="100"
                value="{{ $donate && $donate->getVariantByIndex($index)->price ? $donate->getVariantByIndex($index)->price : old('donate.' . $index . '.price') }}"
            />

            <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                <i data-feather='save' class="font-medium-1" ></i>
            </span>
        </div>

        <div class="col-md-3 col-lg-2 col-4">
            <label class="form-label">
                {{ __('base.currency') }}
            </label>
            @include('common.donate.assets.currency_selector', [
                'id' => 'donate_currency_' . $index,
                'name' => 'donate[' . $index . '][currency]',
                'currencies' => App\Models\Donate::getCurrencyData($donate, $index),
                'active' => $donate && $donate->getVariantByIndex($index)->isActive
            ])
        </div>
        @if(\Illuminate\Support\Facades\Session::get('testing'))<a href="{{ $community->getDonatePaymentLink() . '?' . http_build_query(
    ['amount' => $donate->getVariantByIndex($index)->price,
     'currency' => $donate->getVariantByIndex($index)->currency ]) }}"> тестовая оплата</a>@endif
    </div>
    <hr />
</div>
