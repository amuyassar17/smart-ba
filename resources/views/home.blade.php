@extends('layouts.app')

@section('title', 'SMART-BA - Sistem Bimbingan Akademik')

@section('styles')
<style>
    body {
        background: white;
    }
    
    .hero-section {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        padding: 100px 0;
        color: white;
        min-height: 400px;
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
    
    .section-about {
        padding: 80px 0;
        background: #f9fafb;
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .section-title i {
        font-size: 2.5rem;
        color: #10b981;
    }
    
    .about-image {
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .about-content h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1f2937;
    }
    
    .about-content p {
        font-size: 1rem;
        line-height: 1.8;
        color: #4b5563;
        margin-bottom: 1.5rem;
    }
    
    .section-contact {
        padding: 80px 0;
        background: white;
    }
    
    .contact-info {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .contact-item {
        margin-bottom: 2rem;
    }
    
    .contact-item h6 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .contact-item h6 i {
        font-size: 1.5rem;
        color: #10b981;
    }
    
    .contact-item p {
        margin-bottom: 0.25rem;
        color: #4b5563;
    }
    
    .social-links a {
        display: inline-block;
        width: 45px;
        height: 45px;
        background: #10b981;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 45px;
        margin-right: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .social-links a:hover {
        background: #059669;
        transform: translateY(-3px);
    }
    
    .map-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        height: 400px;
    }
    
    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .site-footer {
        background: #1f2937;
        color: white;
        margin-top: 0;
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
                    Universitas Islam Negeri Kota Palopo
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

{{-- Section Tentang SMART-BA --}}
<div class="section-about" id="profil">
    <div class="container">
        <h2 class="section-title justify-content-center">
            <i class="bi bi-info-circle-fill"></i>
            Tentang SMART-BA
        </h2>
        
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <img src="https://placehold.co/600x400/10b981/white?text=SMART-BA+Dashboard" alt="Dashboard SMART-BA" class="about-image">
            </div>
            <div class="col-lg-7">
                <div class="about-content">
                    <h3>Inovasi Digital untuk Kampus Cerdas & Hijau</h3>
                    <p>
                        <strong>SMART-BA (Smart Bimbingan Akademik)</strong> adalah wujud komitmen UIN Palopo menuju Smart & Green Campus. 
                        Dengan mendigitalkan proses bimbingan, kami mengurangi penggunaan kertas dan meningkatkan efisiensi interaksi 
                        antara dosen dan mahasiswa.
                    </p>
                    <p>
                        Platform ini menyediakan data terpusat, memudahkan monitoring, dan memastikan setiap mahasiswa mendapatkan 
                        bimbingan yang terarah dan terdokumentasi dengan baik, kapan saja dan di mana saja.
                    </p>
                    
                    <div class="row mt-4" id="fasilitas">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Paperless</h6>
                                    <p class="small mb-0 text-muted">Mengurangi penggunaan kertas untuk lingkungan hijau</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Efisien</h6>
                                    <p class="small mb-0 text-muted">Proses bimbingan lebih cepat dan terorganisir</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Terdokumentasi</h6>
                                    <p class="small mb-0 text-muted">Riwayat bimbingan tersimpan dengan aman</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Terintegrasi</h6>
                                    <p class="small mb-0 text-muted">Data akademik terintegrasi dalam satu sistem</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section Tetap Terhubung --}}
<div class="section-contact" id="kontak">
    <div class="container">
        <h2 class="section-title justify-content-center">
            <i class="bi bi-headset"></i>
            Tetap Terhubung Bersama Kami
        </h2>
        
        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="contact-info">
                    <div class="contact-item">
                        <h6>
                            <i class="bi bi-telephone-fill"></i>
                            Pusat Aduan
                        </h6>
                        <p>+62821-93362277</p>
                    </div>
                    
                    <div class="contact-item">
                        <h6>
                            <i class="bi bi-geo-alt-fill"></i>
                            Alamat
                        </h6>
                        <p>[cite_start]</p>
                        <p>Jalan Agatis, Kelurahan Balandai, Kecamatan Bara,<br>Kota Palopo, Sulawesi Selatan</p>
                        <p>[cite: 178]</p>
                    </div>
                    
                    <div class="contact-item">
                        <h6>
                            <i class="bi bi-envelope-fill"></i>
                            Alamat Email
                        </h6>
                        <p>kontak@uinpalopo.ac.id</p>
                    </div>
                    
                    <div class="contact-item">
                        <h6 class="mb-3">Media Sosial</h6>
                        <div class="social-links">
                            <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.932848688879!2d120.19626031475425!3d-2.9923456979584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d93d2f1a8c9f6c9%3A0x8e4c9c8c8c8c8c8c!2sUIN%20Palopo!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Footer --}}
<footer class="site-footer py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-2">SMART-BA</h6>
                <p class="mb-1 small">Sistem Manajemen Akademik dan Bimbingan Terpadu</p>
                <p class="mb-0 small">Fakultas Syariah dan Hukum</p>
                <p class="mb-0 small">Universitas Islam Negeri Kota Palopo</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <h6 class="fw-bold mb-2">Kontak</h6>
                <p class="mb-1 small"><i class="bi bi-geo-alt-fill me-2"></i>Jl. Agatis, Balandai, Kota Palopo</p>
                <p class="mb-1 small"><i class="bi bi-telephone-fill me-2"></i>+62821-93362277</p>
                <p class="mb-0 small"><i class="bi bi-envelope-fill me-2"></i>kontak@uinpalopo.ac.id</p>
            </div>
        </div>
        <hr class="my-3 opacity-25">
        <div class="text-center">
            <p class="mb-0 small opacity-75">&copy; {{ date('Y') }} SMART-BA. Inisiatif Smart & Green Campus UIN Palopo. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection
