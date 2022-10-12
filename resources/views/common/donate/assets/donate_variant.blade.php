<div
    class="donate-variant community-settings__item {{ ($donate && $donate->getVariantByIndex($index)->isActive) || old('donate.' . $index . '.status') ? null : 'inactive-form-items' }}"
    data-donate-item-id="{{ $index }}"
>
    <div class="toggle-switch donate-variant__switcher">        
        <label class="toggle-switch__switcher">
            <input
                type="checkbox"
                id="donate_item_check_{{ $index }}"
                class="toggle-switch__input"
                value="true"
                name="donate[{{ $index }}][status]"
                data-donate-check-id="{{ $index }}"
                onchange="CommunityPage.donatePage.donateSwitcher.onChangeDonateItemCheck(this)"
                {{ ($donate && $donate->getVariantByIndex($index)->isActive) || old('donate.' . $index . '.status') ? 'checked' : null }}
            >
            <span class="toggle-switch__slider"></span>
        </label>
    </div>

    <div>
        <label class="form-label-red">
            {{ __('base.button') }} №{{ $index + 1 }}
        </label>
        
        <input
            type="text"
            id="donate_description_{{ $index }}"
            class="form-control-red @error('donate[' . $index . '][description]') form-control-red--danger @enderror"
            name="donate[{{ $index }}][description]"
            aria-describedby="donate_description_{{ $index }}"
            placeholder="{{ __('donate.donate_variant_description') }}"
            value="{{ $donate && $donate->getVariantByIndex($index)->description ? $donate->getVariantByIndex($index)->description : old('donate.' . $index . '.description') }}"
        >
        
        @error('donate[' . $index . '][description]')
            <span class="form-message form-message--danger">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="form-label-red">
            {{ __('base.amount') }}
        </label>
        
        <input
            type="number"
            id="donate_cost_{{ $index }}"
            class="form-control-red @error('donate.' . $index . '.cost') form-control-red--danger @enderror"
            name="donate[{{ $index }}][cost]"
            aria-describedby="donate_cost_{{ $index }}"
            placeholder="100"
            value="{{ $donate && $donate->getVariantByIndex($index)->price ? $donate->getVariantByIndex($index)->price : old('donate.' . $index . '.price') }}"
        >
        
        @error('donate.' . $index . '.cost')
            <span class="form-message form-message--danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="donate-add__curency">
        <label class="form-label-red">
            {{ __('base.currency') }}
        </label>
        @include('common.donate.assets.currency_selector', [
            'id' => 'donate_currency_' . $index,
            'name' => 'donate[' . $index . '][currency]',
            'currencies' => App\Models\Donate::getCurrencyData($donate, $index),
            'active' => $donate && $donate->getVariantByIndex($index)->isActive
        ])
    </div>
</div>
