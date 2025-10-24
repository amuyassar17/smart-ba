<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center py-2" href="{{ route('home') }}">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-mortarboard-fill" style="font-size: 1.5rem; color: var(--green-primary);"></i>
                    </div>
                </div>
                <div>
                    <span class="d-block fw-bold" style="font-size: 1.1rem; letter-spacing: 0.5px;">SMART-BA</span>
                    <small class="d-block opacity-75" style="font-size: 0.75rem; line-height: 1.2;">Fakultas Syariah dan Hukum<br>UIN Kota Palopo</small>
                </div>
            </div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest('mahasiswa')
                @guest('dosen')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill" href="{{ route('home') }}">
                            <i class="bi bi-house-fill me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link px-4 py-2 rounded-pill" href="{{ route('login') }}" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); font-weight: 600;">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                    </li>
                @endguest
                @endguest
                
                @auth('mahasiswa')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill" href="{{ route('mahasiswa.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill" href="{{ route('mahasiswa.profil') }}">
                            <i class="bi bi-person-circle me-1"></i>Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill" href="{{ route('mahasiswa.riwayat') }}">
                            <i class="bi bi-clock-history me-1"></i>Riwayat
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 dropdown">
                        <a class="nav-link dropdown-toggle px-3 py-2 rounded-pill d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                            <div class="bg-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-person-fill" style="color: var(--green-primary);"></i>
                            </div>
                            <span class="d-none d-lg-inline">{{ Auth::guard('mahasiswa')->user()->nama_mahasiswa }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius: 12px;">
                            <li class="px-3 py-2">
                                <small class="text-muted">Mahasiswa</small>
                                <div class="fw-bold text-truncate" style="max-width: 200px;">{{ Auth::guard('mahasiswa')->user()->nama_mahasiswa }}</div>
                                <small class="text-muted">{{ Auth::guard('mahasiswa')->user()->nim }}</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('mahasiswa.profil') }}">
                                    <i class="bi bi-person-circle me-2"></i>Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('mahasiswa.riwayat') }}">
                                    <i class="bi bi-clock-history me-2"></i>Riwayat Akademik
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
                
                @auth('dosen')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill" href="{{ route('dosen.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 dropdown">
                        <a class="nav-link dropdown-toggle px-3 py-2 rounded-pill d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                            <div class="bg-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-person-badge-fill" style="color: var(--green-primary);"></i>
                            </div>
                            <span class="d-none d-lg-inline">{{ Auth::guard('dosen')->user()->nama_dosen }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius: 12px;">
                            <li class="px-3 py-2">
                                <small class="text-muted">Dosen PA</small>
                                <div class="fw-bold text-truncate" style="max-width: 200px;">{{ Auth::guard('dosen')->user()->nama_dosen }}</div>
                                <small class="text-muted">{{ Auth::guard('dosen')->user()->nidn_dosen }}</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
