@extends('admin::layouts.login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h1>{{ __('Reset Password') }}</h1></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->token }}">
                            @error('common')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right font-weight-semibold">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $request->email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right font-weight-semibold">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <div class="strength">
                                    <span class="bar bar-1"></span>
                                    <span class="bar bar-2"></span>
                                    <span class="bar bar-3"></span>
                                    <span class="bar bar-4"></span>
                                </div>
                                <ul class="list list-unstyled mb-0">
                                    <li class="valid-1"><i class="icon-cross3 mr-2 text-danger"></i> Legalább 12 karakter hosszú legyen</li>
                                    <li class="valid-2"><i class="icon-cross3 mr-2 text-danger"></i> Legalább 1 nagybetűt tartalmazzon</li>
                                    <li class="valid-3"><i class="icon-cross3 mr-2 text-danger"></i> Legalább 1 számot tartalmazzon</li>
                                    <li class="valid-4"><i class="icon-cross3 mr-2 text-danger"></i> Legalább 1 speciális karaktert ($&+,:;=,@#!%_~^) tartalmazzon</li>
                                </ul>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right font-weight-semibold">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        let strength = 0;
        let validations = [];

        function validatePassword(e) {
            const password = e.target.value;

            validations = [
                (password.length > 11),
                (password.search(/[A-Z]/) > -1),
                (password.search(/[0-9]/) > -1),
                (password.search(/[$&+,:;=,@#!]/) > -1),
            ];

            return validations;
        }

        $('#password').keyup(function(e){
            let validations = validatePassword(e);
            strength = validations.reduce((acc, cur) => acc + cur)

            if (strength > 0) {
                $('.bar-1').addClass('bar-show');
            } else {
                $('.bar-1').removeClass('bar-show');
            }

            if (strength > 1) {
                $('.bar-2').addClass('bar-show');
            } else {
                $('.bar-2').removeClass('bar-show');
            }

            if (strength > 2) {
                $('.bar-3').addClass('bar-show');
            } else {
                $('.bar-3').removeClass('bar-show');
            }

            if (strength > 3) {
                $('.bar-4').addClass('bar-show');
            } else {
                $('.bar-4').removeClass('bar-show');
            }

            if (validations[0]){
                $('.valid-1 > i').removeClass('icon-cross3').removeClass('text-danger');
                $('.valid-1 > i').addClass('icon-checkmark2').addClass('text-success');
            } else {
                $('.valid-1 > i').addClass('icon-cross3').addClass('text-danger');
                $('.valid-1 > i').removeClass('icon-checkmark2').removeClass('text-success');
            }
            if (validations[1]){
                $('.valid-2 > i').removeClass('icon-cross3').removeClass('text-danger');
                $('.valid-2 > i').addClass('icon-checkmark2').addClass('text-success');
            } else {
                $('.valid-2 > i').addClass('icon-cross3').addClass('text-danger');
                $('.valid-2 > i').removeClass('icon-checkmark2').removeClass('text-success');
            }
            if (validations[2]){
                $('.valid-3 > i').removeClass('icon-cross3').removeClass('text-danger');
                $('.valid-3 > i').addClass('icon-checkmark2').addClass('text-success');
            } else {
                $('.valid-3 > i').addClass('icon-cross3').addClass('text-danger');
                $('.valid-3 > i').removeClass('icon-checkmark2').removeClass('text-success');
            }
            if (validations[3]){
                $('.valid-4 > i').removeClass('icon-cross3').removeClass('text-danger');
                $('.valid-4 > i').addClass('icon-checkmark2').addClass('text-success');
            } else {
                $('.valid-4 > i').addClass('icon-cross3').addClass('text-danger');
                $('.valid-4 > i').removeClass('icon-checkmark2').removeClass('text-success');
            }
        })
    </script>
@endsection