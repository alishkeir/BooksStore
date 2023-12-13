@extends('admin::layouts.login')

@section('content')

<form method="POST" class="login-form" action="{{ route('login') }}">
@csrf
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center mb-3">
                <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h5 class="mb-0">Belépés</h5>
                <span class="d-block text-muted">Add meg a belépési adataidat alább</span>
            </div>


            <div class="form-group form-group-feedback form-group-feedback-left">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="email" required autocomplete="email" autofocus>
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Jelszó" required autocomplete="current-password">

                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

            </div>

            <div class="form-group">

                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Login') }} <i class="icon-circle-right2 ml-2"></i>
                    </button>
                    <div class="text-center">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                    @endif
                    </div>

            </div>
        </div>
    </div>
</form>



@endsection