<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VoteSecure') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Scripts -->
        @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                --primary-color: #2563eb;
                --secondary-color: #1e40af;
                --accent-color: #3b82f6;
            }

            * {
                font-family: 'Inter', sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                background: #f8fafc;
            }

            .auth-container {
                width: 100%;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: relative;
            }

            .auth-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
                opacity: 0.3;
            }

            .auth-card {
                background: white;
                border-radius: 24px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                max-width: 950px;
                width: 100%;
                display: flex;
                position: relative;
                z-index: 1;
            }

            .auth-sidebar {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 50px 40px;
                color: white;
                width: 45%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            .auth-sidebar::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
                opacity: 0.5;
            }

            .auth-sidebar h2 {
                font-size: 2rem;
                font-weight: 800;
                margin-bottom: 1rem;
                position: relative;
                z-index: 1;
                text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .auth-sidebar p {
                font-size: 1rem;
                opacity: 0.95;
                margin-bottom: 2rem;
                position: relative;
                z-index: 1;
                line-height: 1.6;
            }

            .auth-sidebar .feature-list {
                list-style: none;
                padding: 0;
                position: relative;
                z-index: 1;
            }

            .auth-sidebar .feature-list li {
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                font-size: 0.95rem;
            }

            .auth-sidebar .feature-list li i {
                margin-right: 12px;
                font-size: 1.25rem;
                background: rgba(255, 255, 255, 0.2);
                padding: 8px;
                border-radius: 8px;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .auth-content {
                padding: 50px 45px;
                width: 55%;
                background: white;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .auth-logo {
                display: flex;
                align-items: center;
                margin-bottom: 2.5rem;
                color: var(--primary-color);
            }

            .auth-logo i {
                font-size: 2.5rem;
                margin-right: 12px;
            }

            .auth-logo span {
                font-size: 1.75rem;
                font-weight: 800;
            }

            .auth-title {
                font-size: 2rem;
                font-weight: 800;
                color: #1e293b;
                margin-bottom: 0.5rem;
            }

            .auth-subtitle {
                color: #64748b;
                margin-bottom: 2rem;
                font-size: 0.95rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                display: block;
                font-weight: 600;
                color: #334155;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
            }

            .form-control {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e2e8f0;
                border-radius: 10px;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                background: #f8fafc;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
                background: white;
            }

            .btn-primary {
                width: 100%;
                padding: 14px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                border: none;
                border-radius: 10px;
                color: white;
                font-weight: 700;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
                margin-top: 0.5rem;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            }

            .form-check {
                display: flex;
                align-items: center;
                margin: 1rem 0;
            }

            .form-check input {
                margin-right: 8px;
                width: 18px;
                height: 18px;
                cursor: pointer;
            }

            .form-check label {
                font-size: 0.9rem;
                color: #64748b;
                cursor: pointer;
            }

            .auth-link {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 600;
                transition: color 0.3s ease;
            }

            .auth-link:hover {
                color: var(--secondary-color);
            }

            .text-center {
                text-align: center;
            }

            .text-muted {
                color: #64748b;
                font-size: 0.9rem;
            }

            .mt-3 {
                margin-top: 1rem;
            }

            .error-message {
                color: #ef4444;
                font-size: 0.85rem;
                margin-top: 0.5rem;
            }

            .input-with-icon {
                position: relative;
            }

            .input-with-icon i {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                font-size: 1.1rem;
            }

            .input-with-icon .form-control {
                padding-left: 46px;
            }

            @media (max-width: 768px) {
                .auth-card {
                    flex-direction: column;
                    margin: 20px;
                }

                .auth-sidebar,
                .auth-content {
                    width: 100%;
                }

                .auth-sidebar {
                    padding: 40px 30px;
                }

                .auth-content {
                    padding: 40px 30px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
