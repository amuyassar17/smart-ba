@extends('layouts.app')

@section('title', 'Beranda - SMART-BA')

@section('styles')
<style>
    .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #8b5cf6 100%);
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 20%, rgba(139, 92, 246, 0.3) 0%, transparent 50%);
        animation: moveGradient 20s ease infinite;
    }
    
    @keyframes moveGradient {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(50px, 50px) scale(1.1); }
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        color: white;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 2.5rem;
        line-height: 1.8;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .btn-hero {
        background: white;
        color: #8b5cf6;
        padding: 1rem 3rem;
        font-weight: 700;
        border-radius: 50px;
        font-size: 1.1rem;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-hero:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        color: #7c3aed;
    }
    
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 0;
        overflow: hidden;
    }
    
    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .shape1 {
        width: 300px;
        height: 300px;
        top: 10%;
        left: 10%;
        animation: float 20s infinite ease-in-out;
    }
    
    .shape2 {
        width: 200px;
        height: 200px;
        top: 60%;
        right: 15%;
        animation: float 15s infinite ease-in-out reverse;
    }
    
    .shape3 {
        width: 150px;
        height: 150px;
        bottom: 10%;
        left: 30%;
        animation: float 18s infinite ease-in-out;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }
    
    @media (max-width: 768px) {
        .hero-title { font-size: 2.5rem; }
        .hero-subtitle { font-size: 1rem; }
    }
</style>
@endsection

@section('content')
<div class="hero-section">
    <div class="floating-shapes">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
    </div>
    
    <div class="container text-center">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-star-fill me-2"></i>Smart & Green Campus Initiative
            </div>
            <h1 class="hero-title animate-fade-in-up">
                SMART-BA<br>
                <span style="font-weight: 800; background: linear-gradient(to right, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Bimbingan Akademik</span> Terpadu
            </h1>
            <p class="hero-subtitle animate-fade-in-up" style="animation-delay: 0.2s;">
                Platform bimbingan akademik digital untuk mendukung efisiensi, transparansi, dan keberlanjutan proses studi mahasiswa.
            </p>
            <div class="animate-fade-in-up" style="animation-delay: 0.4s;">
                <a href="{{ route('login') }}" class="btn-hero">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
                </a>
            </div>
            
            <div class="mt-5 pt-4" style="animation-delay: 0.6s;">
                <div class="row justify-content-center">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="glass-card p-3 rounded-4" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px);">
                            <i class="bi bi-people-fill fs-1 text-white mb-2"></i>
                            <p class="text-white mb-0 small fw-bold">Multi-Role Access</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="glass-card p-3 rounded-4" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px);">
                            <i class="bi bi-journal-text fs-1 text-white mb-2"></i>
                            <p class="text-white mb-0 small fw-bold">Digital Logbook</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="glass-card p-3 rounded-4" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px);">
                            <i class="bi bi-graph-up-arrow fs-1 text-white mb-2"></i>
                            <p class="text-white mb-0 small fw-bold">Real-time Analytics</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="glass-card p-3 rounded-4" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px);">
                            <i class="bi bi-shield-check fs-1 text-white mb-2"></i>
                            <p class="text-white mb-0 small fw-bold">Secure & Reliable</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
