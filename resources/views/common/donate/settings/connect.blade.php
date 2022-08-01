@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])
    
    <section class="form-control-repeater" data-tab="donatePage">
        <div class="row">
            <!-- Invoice repeater -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-9">
                            <h4 class="card-title">
                                {{ __('donate.title') }}
                            </h4>
                        </div>

                        <a
                            href="{{ route('community.donate.settings', ['community' => $community->id, 'id' => $donate ? $donate->id : NULL]) }}"
                            class="btn btn-outline-success"
                        >
                            <i data-feather='settings' class="font-medium-1"></i>
                            <span class="d-none d-md-inline-block">
                                {{ __('base.settings') }}
                            </span>
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Form -->
                        <form
                            action="{{ route('community.donate.update', ['community' => $community->id, 'id' => $donate ? $donate->id : NULL]) }}"
                            method="post"
                            class=""
                            id="donate_form_{{ $community->id }}"
                            enctype="multipart/form-data"
                            data-switcher-cotainer
                        >
                            @csrf
                            <div data-repeater-list="invoice">
                                <div class="col-md-10 col-xl-5 col-10">
                                    <div class="mb-1 mb-xl-1">
                                        <label class="form-label">
                                            Наименование доната *
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control @error('description') error @enderror"
                                            id="title"
                                            name="title"
                                            placeholder="Наименование доната"
                                            value="{{ $donate && $donate->title ? $donate->title : old('title') }}"
                                        />
                        
                                        <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                            <i data-feather='save' class="font-medium-1" ></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- Описание доната DESCRIPTION -->
                                @include('common.donate.assets.donate_description')

                                <!-- 1st donate -->
                                @include('common.donate.assets.donate_variant',['index' => 0])

                                <!-- 2nd donate -->
                                @include('common.donate.assets.donate_variant',['index' => 1])

                                <!-- 3rd donate -->
                                @include('common.donate.assets.donate_variant',['index' => 2])

                                <!-- 4th CUSTOM donate -->
                                @include('common.donate.assets.donate_variant_special',['index' => 3])

                                <!-- Отправить в сообщество -->
                                <div data-donate-item-id="6">                                   
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-check-primary form-switch">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input pointer"
                                                        id="donate_item_check_6"
                                                        value="true"
                                                        name="send_to_community"
                                                    />
                                                </div>

                                                <label class="ms-1 pointer" for="donate_item_check_6">
                                                    {{ __('form.send_to_community') }} 
                                                </label>
                                            </div>
                                        </div>      
                                    </div>
                                </div>
                            </div>


                        <div class="card-footer">
                            <div class="row align-items-center">
                                <!-- Submit -->
                                <div class="col-sm-5 col-lg-4 col-xl-3">
                                    <button
                                        class="btn w-100 btn-icon btn-success d-flex align-items-center justify-content-center"
                                        type="submit"
                                        data-repeater-create
                                    >
                                        <i data-feather="save" class="font-medium-1"></i>
                                        <span class="ms-1">
                                            {{ __('base.save') }}
                                        </span>
                                    </button>
                                </div>
                                
                                <!-- Inline command -->
                                <span class="col-xl-9">
                                    <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-success mb-0 mt-1 mt-xl-0">
                                        <div class="alert-body">
                                            <i data-feather="alert-circle" class="font-medium-1"></i>
                                            {{ __('donate.inline_command') }} 
                                            <strong>
                                                {{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate ? $donate->inline_link : __('donate.created_on_save') }}
                                            </strong> — 
                                            <span
                                                class="text-primary pointer"
                                                onclick="copyText('{{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate ? $donate->inline_link : 'Создастся при сохранении' }}')"
                                            >
                                                {{ __('base.copy') }}
                                            </span>
                                        </div>
                                    </div>
                                </span> 
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Invoice repeater -->
        </div>
    </section>
@endsection
