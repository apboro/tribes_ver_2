@extends('common.author.profile')
@section('tab')
@include('common.template.alert.form_info', ['message' => \Session::get('success'), 'errors' => $errors])

    <div class="col-12" data-tab="MobileConfirmedPage">
        <div class="card">
            <div class="card-body">
                @if ($user->isConfirmed())
                    <div class="col-sm-7 col-lg-4 col-xl-3 col-12"> 
                        <h4 class="card-title">
                            {{ __('author.number_confirmed') }}
                        </h4>
                        <p>
                            {{ __('author.your_phone_number') }}:
                            <span
                                id="phone_field"
                                data-phone-code="{{ $user->code }}"
                                data-phone-number="{{ $user->phone }}"
                            >
                                {{ ($user->phone && $user->code) ? $user->phone : __('author.no_phone') }}
                            </span>
                        </p>

                        <p id="date_field">Дата подтверждения: {{ $user->confirmationUserDate('date') }} </p>
                        <p>Время подтверждения: {{ $user->confirmationUserDate() }}</p>
                        <span>
                            <a
                                type="submit"
                                class="btn btn-primary text-white"
                                href="{{ route('author.mobile.reset') }}"
                            >
                                {{ __('author.reset_phone') }}
                            </a>
                        </span>
                    </div>
                @else
                <div class="col-sm-7 col-lg-4 col-xl-3 col-12">
                    <h4 class="card-title">
                        {{ __('author.welcome') }}! 👋
                    </h4>
                    <p>
                        {{ __('author.instruction') }}.
                    </p>
                </div>

                <form
                    action="{{ route('author.mobile.code') }}"
                    method="post"
                    id="mobile_confirmed"
                    onsubmit="event.preventDefault();"
                    data-code-action="{{ route('author.mobile.code') }}"
                >
                    <div class="col-12 col-md-3">
                        @csrf
                        <div id="phone_input_container" class="mb-1">
                            <label for="phone" class="form-label">
                                <code
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    data-bs-original-title="{{ __('auth.sms_instruction') }}"
                                >
                                    {{ __('base.phone') }}*
                                </code>
                            </label>
    
                            <div class="input-group">
                                <select class="form-select pointer" name="code" id="country_code">
                                    <option value="+7" @if ($user->code && $user->code == 7) selected @endif>+7</option>
                                    <option value="+380" @if ($user->code && $user->code == 380) selected @endif>+380</option>
                                    <option value="+998" @if ($user->code && $user->code == 998) selected @endif>+998</option>
                                </select>
    
                                <input
                                    type="text"
                                    class="form-control @error('phone') error @enderror"
                                    style="flex: 1 60%"
                                    id="phone"
                                    value="{{ ($user->phone) ? $user->phone : old('phone') }}"
                                    name="phone"
                                    placeholder="9876543210"
                                    aria-describedby="phone"
                                    required
                                />
                                
                                
                                <span id="phone_error" class="error hidden">
                                    Введите правильный номер
                                </span>
                                
                            </div>
                        </div>
                        <div class="col-12">
                            <button
                                class="btn w-100 btn-icon btn-primary d-flex align-items-center justify-content-center"
                                type="submit"
                                onclick = "AuthorPage.mobileConfirmedPage.onSubmit()"
                                data-repeater-create
                                
                            >
                                <span class="ms-1">
                                    {{ __('auth.sms') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
                @endif 
            </div>

            <!--<div class="d-flex flex-column justify-content-center">
                <div  class="col-12 col-md-3">
                @if($user->confirmation->first() && $user->confirmation()->first()->isBlocked)
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                            <h4 class="card-title">Ошибка</h4>
                            <p class="card-text">Превышен лимит неверных попыток</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($user->confirmation->first() && $user->confirmation()->first()->isConfirmed)
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                            <h4 class="card-title">Ошибка</h4>
                            <p class="card-text">Учетная запись уже была активировна</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="col-10">
                        <div class="alert alert-success">
                            <div class="card-body">
                            <h4 class="card-title">Успех</h4>
                            <p class="card-text">{{session('success')}}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('blocked'))
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                            <h4 class="card-title">Ошибка</h4>
                            <p class="card-text">{{session('blocked')}}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('wrong_code'))
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                            <h4 class="card-title">Ошибка</h4>
                            <p class="card-text">{{session('wrong_code')}}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('not_found'))
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                            <h4 class="card-title">Ошибка</h4>
                            <p class="card-text">{{session('not_found')}}</p>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
            </div>-->
            
            
        </div>
    </div>
@endsection