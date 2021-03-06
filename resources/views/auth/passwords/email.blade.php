@extends('layouts.app')

@section('head')

    <title>Сброс пароля</title>
    <meta name="robots" content="noindex">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Сброс пароля</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            Письмо со ссылкой на сброс пароля успешно отправлено
                        </div>
                    @else
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Ваш e-mail:</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">
                                    @error('g-recaptcha-response')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </label>
                                <div class="g-recaptcha col-md-6" data-sitekey="{{env('CAPTCHA_CLIENT_KEY')}}"></div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Отправить ссылку сброса пароля
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
