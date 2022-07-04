@extends('layouts.auth')

@section('content')
    <div class="auth-inner my-2">
        <div class="card mb-0">
            <div class="card-body" data-plugin="RegisterPage">
                <h4 class="card-title mb-1">
                   {{ __('base.welcome') }} 👋
                </h4>
                <p class="card-text mb-2">
                    {{ __('auth.fill_registration_form') }}
                </p>
                
                <form
                    class="auth-login-form mt-2"
                    method="POST"
                    action="{{ route('register') }}"
                    id="register"
                >
                    @csrf
                    <!-- Email -->
                    <div class="mb-1">
                        <label for="email" class="form-label">
                            Email*
                        </label>
                        <input
                            type="text"
                            class="form-control @error('email') error @enderror"
                            id="email"
                            value="{{ old('email') }}"
                            name="email"
                            placeholder="john@example.com"
                            aria-describedby="email"
                            autofocus
                            required
                        />

                        @error('email')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Имя -->
                    <div class="mb-1">
                        <label for="name" class="form-label">
                            {{ __('base.name') }}
                        </label>
                        <input
                            type="text"
                            class="form-control  @error('name') error @enderror"
                            id="name"
                            value="{{ old('name') }}"
                            name="name"
                            placeholder="{{ __('base.popular_name') }}"
                            aria-describedby="name"
                        />

                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button
                        id="register_submit_btn"
                        type="submit"
                        class="btn btn-primary w-100 mt-1"
                    >
                        {{ __('auth.register') }}
                    </button>
                </form>

                <p class="mt-2">
                    {{ __('auth.rights_message_1') }}
                    <a href="{{ route('privacy.index') }}" target="_blank">{{ __('auth.rights_link_privacy') }}</a>
                    {{ __('auth.rights_message_2') }}
                    <a href="{{ route('privacy_accept.index') }}" target="_blank">{{ __('auth.rights_link_privacy_accept') }}</a>.
                </p>

                <p class="text-center mt-2">
                    <span>
                        {{ __('auth.already_registered') }}
                    </span>
                    
                    <a href="{{ route('login') }}">
                        <span>
                            {{ __('base.login') }}
                        </span>
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
