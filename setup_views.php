<?php

// Script to create all Blade views

$views = [
    'resources/views/layouts/app.blade.php' => <<<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMART-BA')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --green-primary: #00A86B;
            --green-dark: #008F5A;
            --green-light: #2ddb90;
        }
        body {
            font-family: 'Lato', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Montserrat', sans-serif;
        }
        .navbar {
            background-color: var(--green-dark);
        }
        .btn-primary {
            background-color: var(--green-primary);
            border-color: var(--green-primary);
        }
        .btn-primary:hover {
            background-color: var(--green-dark);
            border-color: var(--green-dark);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    @include('layouts.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('layouts.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
BLADE,

    'resources/views/layouts/navbar.blade.php' => <<<'BLADE'
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div>
                <span class="brand-title d-block">SMART-BA Fakultas Syariah dan Hukum</span>
                <small class="brand-subtitle d-block">Universitas Islam Negeri Kota Palopo</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @guest('mahasiswa')
                @guest('dosen')
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                @endguest
                @endguest
                
                @auth('mahasiswa')
                    <li class="nav-item"><a class="nav-link" href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Keluar</button>
                        </form>
                    </li>
                @endauth
                
                @auth('dosen')
                    <li class="nav-item"><a class="nav-link" href="{{ route('dosen.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Keluar</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
BLADE,

    'resources/views/layouts/footer.blade.php' => <<<'BLADE'
<footer class="footer bg-dark text-white text-center py-3 mt-5">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} SMART-BA | Inisiatif Smart & Green Campus UIN Palopo.</p>
    </div>
</footer>
BLADE,

    'resources/views/home.blade.php' => <<<'BLADE'
@extends('layouts.app')

@section('title', 'Beranda - SMART-BA')

@section('styles')
<style>
    .hero-section {
        height: 90vh;
        display: flex;
        align-items: center;
        color: white;
        background: linear-gradient(-45deg, var(--green-primary), var(--green-dark), #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .btn-gabung {
        background-color: #fff;
        color: var(--green-primary);
        border: none;
        padding: 14px 35px;
        font-weight: 700;
        border-radius: 50px;
    }
    .btn-gabung:hover {
        background-color: #f0f0f0;
        transform: translateY(-3px);
    }
</style>
@endsection

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <div class="hero-text">
            <p class="mb-2"><strong>Smart & Green Campus Initiative</strong></p>
            <h1>SMART Bimbingan Akademik Terpadu</h1>
            <p class="lead">Platform bimbingan akademik digital untuk mendukung efisiensi dan keberlanjutan proses studi di Universitas Islam Negeri Palopo.</p>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg btn-gabung">Masuk ke Sistem</a>
        </div>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Tentang SMART-BA</h2>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3>Inovasi Digital untuk Kampus Cerdas & Hijau</h3>
                <p class="text-muted">SMART-BA (Smart Bimbingan Akademik) adalah wujud komitmen UIN Palopo menuju Smart & Green Campus. Dengan mendigitalkan proses bimbingan, kami mengurangi penggunaan kertas dan meningkatkan efisiensi interaksi antara dosen dan mahasiswa.</p>
                <p class="text-muted">Platform ini menyediakan data terpusat, memudahkan monitoring, dan memastikan setiap mahasiswa mendapatkan bimbingan yang terarah dan terdokumentasi dengan baik.</p>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Fitur Unggulan</h5>
                        <ul>
                            <li>Logbook Digital Bimbingan</li>
                            <li>Tracking Milestone Akademik</li>
                            <li>Upload & Share Dokumen</li>
                            <li>Evaluasi Dua Arah</li>
                            <li>Laporan & Analitik</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
BLADE,

    'resources/views/auth/login.blade.php' => <<<'BLADE'
@extends('layouts.app')

@section('title', 'Login - SMART-BA')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Masuk ke SMART-BA</h3>
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Login Sebagai</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="NIM/NIDN" value="{{ old('username') }}" required autofocus>
                            <small class="text-muted">Masukkan NIM (untuk mahasiswa) atau NIDN (untuk dosen)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">Lupa password? Hubungi admin fakultas.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE,

];

// Write all views
foreach ($views as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    $dir = dirname($fullPath);
    
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($fullPath, $content);
    echo "Created: $path\n";
}

echo "\n✅ Basic views created! Creating dashboard views...\n\n";

// Continue with dashboard views in next part
require __DIR__ . '/setup_views_part2.php';
