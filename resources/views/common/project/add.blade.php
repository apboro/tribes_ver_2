@extends('layouts.app')

@section('content')

{{route('project.add')}}
<form
        action="{{ route('project.add') }}"
        method="post"
        enctype="multipart/form-data"
        id="tariff_add_form"
        class="community-settings"
>

    <!-- Название тарифа -->
    <div class="community-settings__change-tariff" id="community-settings__change-tariff">
        <div class="community-settings__form-item">
            <label
                    class="form-label-red"
                    for="tariff_name"
            >
                {{ __('base.title') }}
            </label>

            <input
                    type="text"
                    class="form-control-red @error('title') form-control-red--danger @enderror"
                    id="title"
                    name="title"
                    aria-describedby="title"
                    placeholder="{{ __('base.standart') }}"
            >

            @error('title')
            <span class="form-message form-message--danger">{{ $message }}</span>
            @enderror
        </div>

    </div>



    <div class="community-settings__buttons">
        <button
                type="submit"
                class="button-filled button-filled--primary">
            {{ __('base.save') }}
        </button>

    </div>
</form>
@endsection