@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        body {
            min-height: 100vh;
            color: #0f172a;
            background-color: #0b1f41;
            background-image: linear-gradient(135deg, rgba(11,31,65,0.92), rgba(9,22,46,0.96)),
                url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .login-page {
            min-height: 100vh;
        }
        .login-page::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(59,130,246,0.16), transparent 30%),
                        radial-gradient(circle at bottom right, rgba(126,34,206,0.12), transparent 25%);
            pointer-events: none;
        }
        .login-box {
            max-width: min(380px, 92vw);
            margin: 8vh auto;
            border-radius: 30px;
            overflow: hidden;
            background: rgba(255,255,255,0.82);
            border: 1px solid rgba(255,255,255,0.24);
            box-shadow: 0 24px 80px rgba(15,23,42,0.24);
            backdrop-filter: blur(18px);
        }
        .login-page .login-logo {
            display: none !important;
        }
        .card {
            background: transparent;
            border: none;
            box-shadow: none;
        }
        .card-body {
            padding: 2rem 2rem 1.75rem;
        }
        .login-card-title {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-card-title .login-icon {
            display: inline-flex;
            width: 72px;
            height: 72px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #7c3aed);
            color: #ffffff;
            font-size: 1.8rem;
            margin-bottom: 0.9rem;
        }
        .login-card-title h2 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #0f172a;
        }
        .input-group {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(15,23,42,0.08);
            background: rgba(255,255,255,0.88);
        }
        .form-control {
            border: none;
            background: transparent;
            box-shadow: none;
            min-height: 54px;
        }
        .form-control::placeholder {
            color: #6b7280;
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #475569;
            min-width: 54px;
            justify-content: center;
        }
        .form-control:focus {
            border-color: transparent;
            box-shadow: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #7c3aed);
            border: none;
            border-radius: 14px;
            padding: 0.95rem 1rem;
            font-weight: 600;
            box-shadow: 0 14px 30px rgba(59,130,246,0.24);
            width: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 35px rgba(59,130,246,0.28);
        }
        .icheck-primary input[type="checkbox"]:checked + label::after {
            background-color: #3b82f6;
        }
        .text-muted {
            color: #475569 !important;
        }
        .login-box .card-footer {
            background: transparent;
            border-top: none;
            padding-top: 0.75rem;
        }

        @media (max-width: 640px) {
            .login-box {
                margin: 4vh auto;
                min-height: auto;
            }
            .card-body {
                padding: 1.5rem 1.25rem 1.25rem;
            }
            .login-card-title .login-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            .login-card-title h2 {
                font-size: 1.2rem;
            }
            .input-group {
                border-radius: 14px;
            }
            .input-group-text {
                min-width: 46px;
            }
            .form-control {
                min-height: 50px;
            }
            .row > .col-7,
            .row > .col-5 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .row > .col-5 {
                margin-top: 0.8rem;
            }
            .btn-primary {
                padding: 0.9rem 1rem;
                font-size: 0.98rem;
            }
        }
    </style>
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_header')
    <div class="login-card-title">
        <div class="login-icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <h2>Acceso Seguro</h2>
    </div>
@stop

@section('auth_body')
    <form action="{{ $loginUrl }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-4">
            <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-4">
            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type="submit" class="btn btn-block btn-lg {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
    </form>
@stop


