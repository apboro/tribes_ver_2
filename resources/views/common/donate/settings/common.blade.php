@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <section class="form-control-repeater" data-tab="donatePageSettings">
        <div class="row">
            <!-- Invoice repeater -->
            <div class="col-12">
                <!-- Form -->
                <form
                    action="{{ route('community.donate.settings.update', ['community' => $community->id, 'id' => $donate ? $donate->id : null]) }}"
                    method="post"
                    class=""
                    id="donate_settings_form_{{ $community->id }}"
                    enctype="multipart/form-data"
                    data-switcher-cotainer
                >
                    <div class="card">
                        <div class="card-header">
                            <div class="col-8 col-xl-10">
                                <h4 class="card-title">
                                    {{ __('donate.settings_title') }}
                                </h4>
                            </div>
                            
                            <div class="">
                                <a
                                    href="{{ route('community.donate.list', $community) }}"
                                    class="btn btn-outline-primary custom waves-effect"
                                >
                                    <i data-feather="arrow-left" class="font-medium-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">
                                        {{ __('base.back') }}
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @csrf

                            <!-- Авто-публикация сообщения о донатах -->
                            <div
                                class="@if($donate) {{ !$donate->isAutoPrompt ? 'inactive-form-items' : null }} @else inactive-form-items @endif"
                                data-donate-item-id="0"
                            >
                                <div class="row align-items-end">
                                    <div class="col-md-6 col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-check-primary form-switch">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input pointer"
                                                    id="donate_auto_prompt"
                                                    value="false"
                                                    name="donate_auto_prompt"
                                                    data-donate-check-id="0"
                                                    onchange="CommunityPage.donatePageSettings.donateSwitcher.onChangeDonateItemCheck(this)"
                                                    {{ $donate && $donate->isAutoPrompt ? 'checked' : null }}
                                                />     
                                            </div>

                                            <label class="" for="donate_auto_prompt">
                                                {{ __('donate.auto_publish') }}
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4 col-12">
                                        <!-- Время -->
                                        <div class="mt-1 mt-md-0">
                                            <label class="form-label" for="auto_prompt_time">
                                                {{ __('base.time') }}
                                            </label>
                                            
                                            <div class="">
                                                <input
                                                    type="time"
                                                    class="form-control pointer"
                                                    id="auto_prompt_time"
                                                    aria-describedby="auto_prompt_time"
                                                    name="auto_prompt_time"
                                                    value="{{ $donate ? $donate->getPromptTime() : null }}"
                                                />

                                                <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                                    <i data-feather='save' class="font-medium-1" ></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                            <!-- Сообщение после отправки доната THANKS -->
                            @include('common.donate.assets.donate_success')
                        </div>
                        
                        <div class="card-footer">
                            <div class="col-sm-5 col-lg-4 col-xl-3">
                                <!-- Submit -->
                                <button
                                    class="btn w-100 btn-icon btn-success d-flex align-items-center justify-content-center"
                                    type="submit"
                                    data-repeater-create
                                    value="true"
                                    name="settingsUpdate"
                                >
                                    <i data-feather="save" class="font-medium-1"></i>
                                    <span class="ms-1">
                                        {{ __('base.save') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
