@extends('layouts.app')

@section('title', 'Login - SMART-BA')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    .login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    
    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }
    
    .login-left {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .login-right {
        padding: 3rem;
    }
    
    .role-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .role-option {
        flex: 1;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .role-option:hover {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .role-option.active {
        border-color: #10b981;
        background: #10b981;
        color: white;
    }
    
    .role-option input[type="radio"] {
        display: none;
    }
    
    .role-option i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    .btn-login {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        padding: 0.75rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }
    
    @media (max-width: 768px) {
        .login-left {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="login-card">
                    <div class="row g-0">
                        {{-- Left Side - Info --}}
                        <div class="col-md-5 login-left">
                            <div class="text-center mb-4">
                                <i class="bi bi-bank" style="font-size: 4rem;"></i>
                            </div>
                            <h2 class="fw-bold mb-3">SMART-BA</h2>
                            <h5 class="mb-3">Sistem Manajemen Akademik dan Bimbingan Terpadu</h5>
                            <p class="mb-4 opacity-90">
                                Fakultas Syariah dan Hukum<br>
                                Universitas Islam Negeri Kota Palopo
                            </p>
                            <hr class="my-4 opacity-25">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Multi-Role Access</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Digital Logbook</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Real-time Analytics</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Secure & Reliable</li>
                            </ul>
                        </div>
                        
                        {{-- Right Side - Form --}}
                        <div class="col-md-7 login-right">
                            <div class="mb-4">
                                <h3 class="fw-bold mb-2">Selamat Datang!</h3>
                                <p class="text-muted">Silakan login untuk melanjutkan</p>
                            </div>
                            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                                @csrf
                                
                                {{-- Role Selector --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Login Sebagai:</label>
                                    <div class="role-selector">
                                        <label class="role-option active" for="role-mahasiswa">
                                            <input type="radio" name="role" id="role-mahasiswa" value="mahasiswa" checked>
                                            <i class="bi bi-person-circle"></i>
                                            <div class="fw-semibold">Mahasiswa</div>
                                        </label>
                                        <label class="role-option" for="role-dosen">
                                            <input type="radio" name="role" id="role-dosen" value="dosen">
                                            <i class="bi bi-person-badge"></i>
                                            <div class="fw-semibold">Dosen PA</div>
                                        </label>
                                    </div>
                                </div>
                                
                                {{-- Username --}}
                                <div class="mb-3">
                                    <label for="username" class="form-label fw-semibold">
                                        <span id="username-label">NIM</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="username" 
                                           name="username" 
                                           placeholder="Contoh: 1803010015" 
                                           value="{{ old('username') }}"
                                           required 
                                           autofocus>
                                    <small class="text-muted" id="username-hint">Masukkan NIM tanpa spasi</small>
                                </div>
                                
                                {{-- Password --}}
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control form-control-lg" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Masukkan password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                {{-- Submit Button --}}
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-login btn-lg text-white">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                                    </button>
                                </div>
                                
                                <div class="text-center">
                                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Role selector
    document.querySelectorAll('.role-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            const role = this.querySelector('input[type="radio"]').value;
            const usernameLabel = document.getElementById('username-label');
            const usernameHint = document.getElementById('username-hint');
            const usernameInput = document.getElementById('username');
            
            if (role === 'mahasiswa') {
                usernameLabel.textContent = 'NIM';
                usernameHint.textContent = 'Masukkan NIM tanpa spasi';
                usernameInput.placeholder = 'Contoh: 1803010015';
            } else {
                usernameLabel.textContent = 'NIDN';
                usernameHint.textContent = 'Masukkan NIDN';
                usernameInput.placeholder = 'Contoh: 2002057203';
            }
        });
    });
    
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    });
</script>
@endsection
