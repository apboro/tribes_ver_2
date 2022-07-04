<!-- Form error -->
@if($errors->any())
    <div class="col-12">
        <div role="alert" class="alert alert-danger">
            <div class="alert-body">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li><p>{{ $error }}</p></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Form success -->
@if(isset($message) && $message != null)
    @if(is_array($message))
        @foreach($message as $m)
            @if($m !== null)
            <div class="col-12">
                <div class="alert alert-success">
                    <div class="alert-body">
                        <i data-feather="check-circle" class="font-medium-1"></i>
                        {!! $m !!}
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    @else
        <div class="col-12">
            <div class="alert alert-success">
                <div class="alert-body">
                    <i data-feather="check-circle" class="font-medium-1"></i>
                    {!! $message !!}
                </div>
            </div>
        </div>
    @endif
@endif

<!-- Messenger info -->
@if(!Auth::user()->hasTelegramAccount() && !request()->is('*profile/mobile*'))
    <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-info mb-1">
        <div class="alert-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            {{ __('author.no_messengers_label') }}
        </div>
    </div>
@endif

<!-- Mobile info -->
@if(!Auth::user()->isConfirmed() && request()->is('*profile/mobile*'))
    <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-info mb-1">
        <div class="alert-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            {{ __('author.no_confirmed_mobile') }}
        </div>
    </div>
@endif

<!-- Author mobile confirmed block Start -->

@if(session('call'))
    <div class="col-12">
        <div class="alert alert-success">
            <div class="alert-body">
                <i data-feather="check-circle" class="font-medium-1"></i>
                {{session('call')}}
            </div>
        </div>
    </div>
@endif
<!-- Errors -->
@if(Auth::user()->confirmation->first() && Auth::user()->confirmation()->first()->isBlocked)
    <div class="col-12">
        <div class="alert alert-danger">
            <div class="alert-body">
                <ul class="mb-0">
                    <li>
                        <p>
                            {{ __('author.failed_attempts_limit_exceeded') }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>    
@endif

@if(Auth::user()->confirmation->first() && Auth::user()->confirmation()->first()->isConfirmed)
    <div class="col-12">
        <div class="alert alert-danger">
            <div class="alert-body">
                <ul class="mb-0">
                    <li>
                        <p>
                            {{ __('author.account_has_already_been_activated') }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif

@if(session('blocked'))
    <div class="col-12">
        <div class="alert alert-danger">
            <div class="alert-body">
                <ul class="mb-0">
                    <li>
                        <p>
                            {{session('blocked')}}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif

@if(session('wrong_code'))
    <div class="col-12">
        <div class="alert alert-danger">
            <div class="alert-body">
                <ul class="mb-0">
                    <li>
                        <p>
                            {{session('wrong_code')}}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif

@if(session('not_found'))
    <div class="col-12">
        <div class="alert alert-danger">
            <div class="alert-body">
                <ul class="mb-0">
                    <li>
                        <p>
                            {{session('not_found')}}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif
<!-- Author mobile confirmed block End -->
