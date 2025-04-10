@extends('layouts.app')

@section('content')
<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1 mt-3">
                                <img src="https://internship4you.com/assets/img/webp/login-img.webp" class="img-fluid" alt="Login Image">
                            </div>
                            <div class="position-relative">
                                <div class="position-absolute top-0 start-0">
                                    <a href="{{ route('home') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                            <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 align-items-center order-1 order-lg-2">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="divider d-flex align-items-center my-4">
                                        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Iniciar sessió</p>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus />
                                        <label class="form-label" for="email">Email</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-3">
                                        <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="current-password" />
                                        <label class="form-label" for="password">Password</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('password.request') }}" class="text-body">Forgot password?</a>
                                    </div>

                                    @if(Session::get('login_attempts', 0) >= 3)
                                    <div class="g-recaptcha my-3" data-sitekey="6LeCepAqAAAAANEgWrjJvKiFpWjAL9KZXmYkyVFI"></div>
                                    @endif

                                    <div class="text-center text-lg-start mt-4 pt-2">
                                        <button type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                                        <label class="form-check-label ms-2">
                                            No tens compte? <a href="{{ route('register') }}">Registre't!</a>
                                        </label>
                                    </div>

                                    <!-- Botón de Google -->
                                    <div class="text-center text-lg-start mt-4 pt-2">
                                        <a href="{{ route('login.google') }}" class="btn btn-warning d-flex align-items-center justify-content-center">
                                            <img src="{{ asset('img/google.png') }}" alt="Google Icon" style="width:20px; height:20px; margin-right:8px;">
                                            Sign in with Google
                                        </a>
                                    </div>
                                    <div class="text-center text-lg-start mt-1 pt-2">
                                        <a href="{{ route('login.github') }}" class="btn btn-danger d-flex align-items-center justify-content-center">
                                            <img src="{{ asset('img/github.png') }}" alt="Github Icon" style="width:20px; height:20px; margin-right:8px;">
                                            Sign in with Github
                                        </a>
                                    </div>

                                    @if(session('error'))
                                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                                    @endif
                                    @if(session('passwd'))
                                        <div class="alert alert-danger mt-3">{{ session('passwd') }}</div>
                                    @endif
                                    @if(session('success'))
                                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection