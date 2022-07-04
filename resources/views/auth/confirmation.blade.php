@extends('layouts.auth')

@section('content')  
    <div class="auth-inner my-2" data-plugin="ConfirmationPage">
        <div class="card mb-0">
            <div class="card-body" data-plugin="LoginPage">
                <h4 class="card-title mb-1">Введите код из sms</h4>
                <p class="card-text mb-2">На номер +{{ $user->getPhone() }} отправлено сообщение</p>

                <form class="auth-login-form mt-2 " method="POST" action="{{ route('sms.confirmation.confirm') }}">
                    @csrf
                    <div class="mb-1">
                        <label for="email" class="form-label">Код</label>
                        <input
                            type="number"
                            class="form-control"
                            id="sms_code"
                            value=""
                            name="code"
                            placeholder="1234"
                            aria-describedby="sms_code"
                            oninput="ConfirmationPage.onInputConfirmCodeInput(event)"
                            autofocus
                        />
                        
                        @error('email')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <input type="hidden" name="hash" value="{{ request()->get('hash') }}">
                    <button type="submit" class="btn btn-primary w-100 mt-2 mb-1">Подтвердить</button>
                </form>

                <form method="post" action="{{ route('sms.confirmation.repeat') }}">
                    @csrf
                    <input type="hidden" name="hash" value="{{ request()->get('hash') }}">
                    <button
                        type="submit"
                        id="resend_code_btn"
                        class="btn btn-outline-info w-100 waves-effect"
                        onclick="ConfirmationPage.onClickResendCodeBtn(event)"
                        disabled
                    >Отправить sms повторно</button>
                </form>
                
            </div>
            
            <div class="d-flex flex-column align-items-center justify-content-center">
                @if(!$user)
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                                <h4 class="card-title">Ошибка</h4>
                                <p class="card-text">Срок действия вашего сеанса истек</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($user->confirmation() && $user->confirmation()->first()->isBlocked)
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                            <h4 class="card-title">Ошибка</h4>
                            <p class="card-text">Превышен лимит неверных попыток</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($user->confirmation() && $user->confirmation()->first()->isConfirmed)
                    <div class="col-10">
                        <div class="card shadow-none bg-transparent border-danger">
                            <div class="card-body">
                            <h4 class="card-title">Ошибка</h4>
                            <p class="card-text">Учетная запись уже была активировна</p>
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
        </div>
    </div>    
@endsection
