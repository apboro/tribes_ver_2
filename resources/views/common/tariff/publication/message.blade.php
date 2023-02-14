@extends('common.tariff.publication.index')

@section('subtab')
    <form
        action="{{ route('tariff.settings.update', $community) }}"
        method="post"
        id="general_form_{{ $community->id }}"
        class="community-settings"
        enctype="multipart/form-data"
        data-tab="tariffPagePublicationsMessage"
    >
        @csrf
         <div class="inactive-form-items">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-primary form-switch">
                            <input type="checkbox"
                                class="form-check-input pointer"
                                id="tariff_notification"
                                value="true"
                                name="tariff_notification"
                                {{ ($community->tariff->tariff_notification == true) ? 'checked' : null }}
                            />
                        </div>

                        <label class="ms-1 pointer" for="tariff_notification">
                            {{ __('form.receive_notifications') }}
                        </label>
                    </div>
                </div>
            </div>
            <hr />
        </div>

        <!-- Описание к публикации тарифов -->
        @include('common.tariff.assets.tariff_publication')

        <!-- Отправить в сообщество -->
        <div class="toggle-switch @if ($community->hasNotActiveTariffVariants()) toggle-switch--disabled @endif community-settings__item">
            <label class="toggle-switch__switcher">
                <input
                    type="checkbox"
                    id="tariff_item_check_6"
                    class="toggle-switch__input"
                    value="true"
                    name="send_to_community"
                >
                <span class="toggle-switch__slider"></span>
            </label>

            <label
                for="tariff_item_check_6"
                class="toggle-switch__label"
            >
                {{ __('form.send_to_community') }}
            </label>
        </div>
        
        <!-- Submit -->
        <div class="community-settings__buttons">
            <button
                class="button-filled button-filled--primary"
                type="submit"
                data-repeater-create
            >
                {{ __('base.save') }}
            </button>

            <a
                href="{{ route('project.tariffs', ['project' => $community->project_id ?? 'c', 'community' => $community->id]) }}"
                class="button-filled button-filled--primary-15"
            >
                Отменить
            </a>
        </div>
    </form>
@endsection
