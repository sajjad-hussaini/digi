<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | {{config('settings.system_title')}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">

    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== Main Card ===== */
        .login-card {
            display: flex;
            width: 900px;
            min-height: 560px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* ===== Left Panel ===== */
        .left-panel {
            width: 45%;
            background: #0C1628;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(55, 138, 221, 0.12);
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(29, 158, 117, 0.10);
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: #378ADD;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 19px;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .hero-text {
            position: relative;
            z-index: 1;
        }

        .hero-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #ffffff;
            font-weight: 600;
            line-height: 1.35;
            margin-bottom: 14px;
        }

        .hero-text p {
            font-size: 14px;
            color: #85B7EB;
            line-height: 1.7;
        }

        .stats-row {
            display: flex;
            gap: 1.8rem;
            position: relative;
            z-index: 1;
        }

        .stat-item .stat-num {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
        }

        .stat-item .stat-label {
            font-size: 11px;
            color: #85B7EB;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 2px;
        }

        /* ===== Right Panel ===== */
        .right-panel {
            width: 55%;
            background: #ffffff;
            padding: 3rem 2.8rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header .greeting {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .form-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #0C1628;
            font-weight: 600;
        }

        /* ===== Form Controls ===== */
        .field-label {
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-bottom: 6px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .input-group-custom .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 14px;
            z-index: 5;
        }

        .input-group-custom .form-control {
            height: 46px;
            padding-left: 40px;
            border: 1px solid #dee2e6;
            border-radius: 8px !important;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #212529;
            background-color: #f8f9fa;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-group-custom .form-control:focus {
            border-color: #378ADD;
            box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.15);
            background-color: #fff;
            outline: none;
        }

        .input-group-custom .form-control.is-invalid {
            border-color: #e24b4a;
            box-shadow: none;
        }

        .invalid-feedback {
            font-size: 12px;
            color: #e24b4a;
            margin-top: 4px;
        }

        /* ===== Options Row ===== */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .custom-checkbox .custom-control-label {
            font-size: 13px;
            color: #6c757d;
            cursor: pointer;
        }

        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #378ADD;
            border-color: #378ADD;
        }

        .forgot-link {
            font-size: 13px;
            color: #378ADD;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #185FA5;
        }

        /* ===== Submit Button ===== */
        .btn-signin {
            width: 100%;
            height: 48px;
            background: #0C1628;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-signin:hover {
            background: #185FA5;
        }

        .btn-signin:active {
            transform: scale(0.99);
        }

        /* ===== Footer ===== */
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 13px;
            color: #6c757d;
        }

        .form-footer a {
            color: #378ADD;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                width: 95%;
                min-height: auto;
            }

            .left-panel {
                width: 100%;
                padding: 2rem;
                min-height: 180px;
            }

            .left-panel .hero-text {
                display: none;
            }

            .right-panel {
                width: 100%;
                padding: 2rem;
            }

            .stats-row {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="login-card">

    {{-- ===== LEFT PANEL ===== --}}
    <div class="left-panel">
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <span class="brand-name">{{config('settings.system_title')}}</span>
        </div>

        <div class="hero-text">
            <h1>Welcome back to creation of document</h1>
            <p>Securely access your dashboard, manage your data, and pick up right where you left off.</p>
        </div>

        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num">99.9%</div>
                <div class="stat-label">Uptime</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">256-bit</div>
                <div class="stat-label">Encrypted</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT PANEL (FORM) ===== --}}
    <div class="right-panel">

        <div class="form-header">
            <p class="greeting">Good to see you again</p>
            <h2>Sign in to continue</h2>
        </div>

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            {{-- Username Field --}}
            <div class="form-group">
                <div class="field-label">Username</div>
                <div class="input-group-custom">
                    <i class="fas fa-user field-icon"></i>
                    <input
                        type="text"
                        class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Enter your username"
                        autofocus
                    >
                </div>
                @if ($errors->has('username'))
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $errors->first('username') }}
                    </div>
                @endif
            </div>

            {{-- Password Field --}}
            <div class="form-group">
                <div class="field-label">Password</div>
                <div class="input-group-custom">
                    <i class="fas fa-lock field-icon"></i>
                    <input
                        type="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        name="password"
                        placeholder="Enter your password"
                    >
                </div>
                @if ($errors->has('password'))
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            {{-- Remember Me + Forgot Password --}}
            <div class="options-row">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                    <label class="custom-control-label" for="remember">Remember me</label>
                </div>
                <a href="{{ url('/password/reset') }}" class="forgot-link">Forgot password?</a>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn-signin">
                Sign In &nbsp;<i class="fas fa-arrow-right"></i>
            </button>

        </form>

        <div class="form-footer">
            Having trouble? <a href="#">Contact support</a>
        </div>

    </div>
</div>

<!-- Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>