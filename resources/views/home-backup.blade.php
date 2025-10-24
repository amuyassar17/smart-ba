@extends('layouts.app')

@section('title', 'SMART-BA - Sistem Bimbingan Akademik')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        padding: 80px 0;
        color: white;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.95;
        margin-bottom: 2rem;
    }
    
    .feature-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        height: 100%;
        background: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
    }
    
    .info-section {
        background: #f9fafb;
        padding: 60px 0;
    }
    
    .stat-card {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #10b981;
    }
    
    .stat-label {
        color: #6b7280;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    .login-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        overflow: hidden;
        background: white;
    }
    
    .login-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .btn-login-role {
        padding: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .btn-login-role:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .site-footer {
        background: #1f2937;
        color: white;
        margin-top: 60px;
    }
</style>
@endsection

@section('content')
{{-- Hero Section --}}
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">SMART-BA</h1>
                <p class="hero-subtitle">
                    Sistem Manajemen Akademik dan Bimbingan Terpadu<br>
                    <strong>Fakultas Syariah dan Hukum</strong><br>
                    UIN Alauddin Makassar
                </p>
                <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem;">
                    Platform digital untuk memfasilitasi proses bimbingan akademik antara mahasiswa dan dosen pembimbing akademik (PA) 
                    secara sistematis, terstruktur, dan efektif dalam upaya meningkatkan kualitas pendampingan akademik mahasiswa.
                </p>
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3" style="border-radius: 50px; font-weight: 600; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login Sistem
                </a>
            </div>
            <div class="col-lg-5 text-center mt-5 mt-lg-0">
                <div class="login-card">
                    <div class="login-header">
                        <div class="mb-3">
                            <i class="bi bi-mortarboard-fill" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="mb-0">Akses Sistem</h4>
                        <p class="mb-0 mt-2 opacity-75">Pilih role untuk masuk</p>
                    </div>
                    <div class="p-4">
                        <a href="{{ route('login') }}" class="btn btn-success btn-login-role w-100 mb-3">
                            <i class="bi bi-person-circle me-2"></i>Login Mahasiswa
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-login-role w-100">
                            <i class="bi bi-person-badge me-2"></i>Login Dosen PA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Info Section --}}
<div class="info-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Tentang SMART-BA</h2>
            <p class="lead text-muted">Sistem Bimbingan Akademik Digital yang Modern dan Efektif</p>
        </div>

        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <i class="bi bi-people-fill text-success" style="font-size: 3rem;"></i>
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Mahasiswa Terdaftar</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <i class="bi bi-person-badge-fill text-primary" style="font-size: 3rem;"></i>
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Dosen Pembimbing</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <i class="bi bi-journal-check text-warning" style="font-size: 3rem;"></i>
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Sesi Bimbingan</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="text-center fw-bold mb-3">Multi-Role</h5>
                    <p class="text-center text-muted">Akses terpisah untuk mahasiswa dan dosen dengan dashboard khusus</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h5 class="text-center fw-bold mb-3">Digital Logbook</h5>
                    <p class="text-center text-muted">Catat setiap sesi bimbingan secara digital dengan riwayat lengkap</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5 class="text-center fw-bold mb-3">Analytics</h5>
                    <p class="text-center text-muted">Visualisasi data akademik dengan grafik dan laporan informatif</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="text-center fw-bold mb-3">Secure</h5>
                    <p class="text-center text-muted">Sistem keamanan berlapis dengan enkripsi data terproteksi</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Footer for Home Page --}}
<footer class="site-footer bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-2">SMART-BA</h6>
                <p class="mb-1 small">Sistem Manajemen Akademik dan Bimbingan Terpadu</p>
                <p class="mb-0 small">Fakultas Syariah dan Hukum</p>
                <p class="mb-0 small">UIN Alauddin Makassar</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <h6 class="fw-bold mb-2">Kontak</h6>
                <p class="mb-1 small"><i class="bi bi-geo-alt-fill me-2"></i>Jl. Sultan Alauddin No. 63, Gowa</p>
                <p class="mb-1 small"><i class="bi bi-telephone-fill me-2"></i>(0411) 424835</p>
                <p class="mb-0 small"><i class="bi bi-envelope-fill me-2"></i>syariah@uin-alauddin.ac.id</p>
            </div>
        </div>
        <hr class="my-3 opacity-25">
        <div class="text-center">
            <p class="mb-0 small opacity-75">&copy; {{ date('Y') }} SMART-BA. Inisiatif Smart & Green Campus UIN Palopo. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection
