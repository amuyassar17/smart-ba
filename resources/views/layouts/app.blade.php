<!DOCTYPE html>
<html lang="id" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMART-BA')</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1 0 auto;
        }
        
        .site-footer {
            flex-shrink: 0;
            margin-top: auto;
        }
    </style>
    
    <style>
        :root {
            --green-primary: #10b981;
            --green-dark: #059669;
            --purple-primary: #8b5cf6;
            --blue-primary: #3b82f6;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Lato', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
        }
        
        h1, h2, h3, h4, h5, h6, .navbar-brand { 
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }
        
        .navbar { 
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-primary) 100%);
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, var(--blue-primary) 0%, var(--purple-primary) 100%);
            border: none;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover { 
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            background: white;
        }
        
        .card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-4px);
        }
        
        .card-header {
            border-radius: 16px 16px 0 0 !important;
            border-bottom: 1px solid var(--gray-200);
            padding: 1.25rem;
        }
        
        .badge {
            border-radius: 12px;
            padding: 0.35rem 0.8rem;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .modal-content {
            border-radius: 20px;
            border: none;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid var(--gray-200);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--blue-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .progress {
            border-radius: 12px;
            background-color: var(--gray-200);
        }
        
        .progress-bar {
            border-radius: 12px;
        }
        
        /* Modern Gradient Cards */
        .card-gradient-blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .card-gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .card-gradient-purple {
            background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
            color: white;
        }
        
        .card-gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        /* Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    @if(!Request::is('login'))
        @include('layouts.navbar')
    @endif
    
    <main>
        @yield('content')
    </main>
    
    @if(!Request::is('/') && !Request::is('login'))
        @include('layouts.footer')
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
