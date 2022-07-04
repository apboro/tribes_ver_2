@extends('common.author.profile')
@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <div class="col-12" data-tab="ChangePasswordPage">
        <div class="card">
            <div class="card-header">
                <p class="mb-0">
                    {{ __('author.password_instruction') }}.
                </p>
            </div>

            <div class="card-body">
                <form
                    action="{{ route('profile.password.change') }}"
                    method="post"
                    id="password_change"
                >
                    <div  class="col-12 col-md-6 col-lg-5">
                        @csrf
                        <!-- Пароль -->
                        <div class="mb-1">
                            <label class="form-label" for="login-password">
                                {{ __('author.new_password') }}
                            </label>
                            
                            <div
                                id="password_container"
                                class="input-group input-group-merge form-password-toggle  @error('password') is-invalid @enderror"
                            >
                                <input
                                    type="password"
                                    class="form-control form-control-merge @error('password') error @enderror"
                                    id="password"
                                    name="password"
                                    aria-describedby="password"
                                    data-id="password"
                                />
                                <span
                                    class="input-group-text cursor-pointer"
                                    data-id="password_icon_container"
                                    title="{{ __('base.show_password') }}"
                                    onclick="AuthorPage.changePasswordPage.passwordField.toggleVisibilityPassword()"
                                >
                                    <i data-feather="eye"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Подтверждение -->
                        <div class="mb-1">
                            <label class="form-label" for="login-password">
                                {{ __('author.repeat_new_password') }}
                            </label>
                            
                            <div
                                id="confirmation_password_container"
                                class="input-group input-group-merge form-password-toggle"
                            >
                                <input
                                    type="password"
                                    class="form-control form-control-merge"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    tabindex="5"
                                    placeholder=""
                                    aria-describedby="password_confirmation"
                                    data-id="password"
                                />
                                <span
                                    class="input-group-text cursor-pointer"
                                    data-id="password_icon_container"
                                    title="{{ __('base.show_password') }}"
                                    onclick="AuthorPage.changePasswordPage.confirmPasswordField.toggleVisibilityPassword()"
                                >
                                    <i data-feather="eye"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Сохранить -->
                        <div class="col-sm-7 col-lg-4 col-xl-3 col-12">
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
                    
                        {!! Session::get("message", '') !!}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
