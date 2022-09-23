@extends('layouts.auth')

@section('content')
    <div class="auth-inner my-2">
        <div class="card mb-0">
            <div class="card-body" data-plugin="ResetPage">
                <h4 class="card-title mb-1">Восстановление доступа</h4>
                <p class="card-text mb-2">Заполните форму для продолжения</p>

                <form class="auth-login-form mt-2" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-1">
                        <label for="login-email" class="form-label">Email</label>
                        <input type="text" class="form-control  @error('email') error @enderror" id="email" value="{{ $email ?? old('email') }}" name="email" placeholder="john@example.com" aria-describedby="email" tabindex="1" autofocus />

                        @error('email')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="login-password">Пароль</label>
                        </div>
                        <div
                            id="password_container"
                            class="input-group input-group-merge form-password-toggle  @error('password') is-invalid @enderror"
                        >
                            <input
                                type="password"
                                class="form-control form-control-merge @error('password') error @enderror"
                                id="password"
                                name="password"
                                tabindex="2"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password"
                                data-id="password"
                            >
                            <span
                                class="input-group-text cursor-pointer"
                                data-id="password_icon_container"
                                title="{{ __('base.show_password') }}"
                                onclick="ResetPage.passwordField.toggleVisibilityPassword()"
                            >
                                <i data-feather="eye"></i>
                            </span>
                        </div>
                        @error('password')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="login-password">Подтверждение</label>
                        </div>
                        <div
                            id="confirm_password_container"
                            class="input-group input-group-merge form-password-toggle"
                        >
                            <input
                                type="password"
                                class="form-control form-control-merge"
                                id="password_confirmation"
                                name="password_confirmation"
                                tabindex="2"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password_confirmation"
                                data-id="password"
                            >
                            <span
                                class="input-group-text cursor-pointer"
                                data-id="password_icon_container"
                                title="{{ __('base.show_password') }}"
                                onclick="ResetPage.confirmPasswordField.toggleVisibilityPassword()"
                            >
                                <i data-feather="eye"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2 mb-1" tabindex="4">Восстановить пароль</button>
                </form>
            </div>
        </div>
    </div>
@endsection
