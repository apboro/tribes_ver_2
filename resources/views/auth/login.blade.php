@extends('layouts.auth')

@section('content')
    <div class="auth-inner my-2">
        <div class="card mb-0">
            <div class="card-body" data-plugin="LoginPage">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <h4 class="card-title mb-1">
                    {{ __('base.welcome') }} 👋
                </h4>
                <p class="card-text mb-2">
                    {{ __('auth.log_in_to_continue') }}
                </p>
                @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif
                <form
                    class="auth-login-form mt-2"
                    method="POST"
                    action="{{ route('login') }}"
                    id="login"
                >
                    @csrf
                    <div class="mb-1">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="text"
                            class="form-control @error('email') error @enderror"
                            id="email"
                            value="{{ request()->get('email') ?? old('email') }}"
                            name="email"
                            placeholder="john@example.com"
                            aria-describedby="email"
                            @if(!request()->has('email')) autofocus @endif
                        />
                        @error('email')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Телефон -->
                    {{-- <div class="mb-1">
                        <label for="phone" class="form-label">
                            {{ __('base.phone') }}
                        </label>

                        <div class="input-group">
                            <select class="form-select pointer" name="code" id="country_code">
                                <option value="+7" checked>+7</option>
                                <option value="+380">+380</option>
                                <option value="+998">+998</option>
                            </select>

                            <input
                                type="text"
                                class="form-control @error('password') error @enderror"
                                id="phone"
                                value="{{ old('phone') }}"
                                name="phone"
                                placeholder="(987)654-32-10"
                                aria-describedby="phone"
                                style="flex: 1 60%"
                            />
                        </div>

                        @error('phone')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <!-- Пароль -->
                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">
                                {{ __('base.password') }}
                            </label>
                            <a href="{{ route('password.request') }}">
                                <small>
                                    {{ __('auth.forgot_password') }}
                                </small>
                            </a>
                        </div>
                        <div
                            id="password_container" 
                            class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
                            <input
                                type="password"
                                class="form-control form-control-merge @error('password') error @enderror"
                                id="password"
                                name="password"
                                placeholder=""
                                aria-describedby="password"
                                data-id="password"
                                @if(request()->has('email')) autofocus @endif
                            />
                            <span
                                class="input-group-text cursor-pointer"
                                data-id="password_icon_container"
                                title="{{ __('base.show_password') }}"
                                onclick="LoginPage.passwordField.toggleVisibilityPassword()"
                            >
                                <i data-feather="eye"></i>
                            </span>
                        </div>
                        @error('password')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2 mb-1">
                        {{ __('base.login') }}
                    </button>
                </form>

                <p class="text-center mt-2">
                    <span>
                        {{ __('auth.no_account') }}
                    </span>
                    <a href="{{ route('register') }}">
                        <span>
                            {{ __('auth.create_account') }}
                        </span>
                    </a>
                </p>
                
            </div>
        </div>
    </div>
@endsection
