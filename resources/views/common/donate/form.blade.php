@extends('layouts.auth')

@section('content')
    <div class="auth-inner my-2">
        <div class="card mb-0">
            <div class="col-12">
                <img src="{{ $donate->getDonateMainImage() }}" alt="" class="w-100">
            </div>

            <div class="card-body d-flex flex-column">
                <p class="card-text mb-2">
                    {{ $donate->getDonateMainDescription() }}
                </p>

                <form
                    class="auth-login-form mb-2"
                    method="POST"
                    action="{{ route('payment.donate.range') }}"
                >
                    @csrf
                    <input type="hidden" name="community_id" value="{{ $community->id }}">

                    <!-- Сумма -->
                    <div class="mb-1">
                        <label for="amount" class="form-label">
                            {{ __('donate.amount_your_donation') }} {{ __('base.from_2') }} {{ $min }} {{ __('base.to') }} {{ $max }}
                        </label>

                        <input
                            type="number"
                            class="form-control @error('amount') error @enderror"
                            id="amount"
                            value=""
                            name="amount"
                            aria-describedby="amount"
                            autofocus
                            min="{{ $min }}"
                            max="{{ $max }}"
                        />

                        {{--@error('amount')
                        <span class="error">{{ $message }}</span>
                        @enderror--}}
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        {{ __('base.send') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
