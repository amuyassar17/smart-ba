@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h3>Dashboard Dosen PA</h3>
            <p class="text-muted">Selamat datang, {{ $dosen->nama_dosen }}</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-gradient-blue border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-people-fill fs-1 opacity-75"></i>
                    </div>
                    <h6 class="text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">Total Mahasiswa</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalMhs }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.2s; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill fs-1 opacity-75"></i>
                    </div>
                    <h6 class="text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">Perlu Perhatian</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalPeringatan }}</h2>
                    <small class="opacity-75">IPK < 2.75 atau Non-Aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gradient-purple border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-bell-fill fs-1 opacity-75"></i>
                    </div>
                    <h6 class="text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">Notifikasi Logbook</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalNotif }}</h2>
                    <small class="opacity-75">Belum dibaca</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gradient-green border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-check-circle-fill fs-1 opacity-75"></i>
                    </div>
                    <h6 class="text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">Mahasiswa Aktif</h6>
                    <h2 class="mb-0 fw-bold">{{ $semuaMahasiswa->where('status_semester', 'A')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Daftar Mahasiswa --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Daftar Mahasiswa Bimbingan</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <select name="angkatan" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatan as $thn)
                                    <option value="{{ $thn }}" {{ $angkatanFilter == $thn ? 'selected' : '' }}>
                                        Angkatan {{ $thn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIM..." value="{{ $searchQuery }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>IPK</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($semuaMahasiswa as $mhs)
                                    <tr class="{{ $mhs->ipk < 2.75 ? 'table-danger' : '' }}">
                                        <td>
                                            <strong>{{ $mhs->nama_mahasiswa }}</strong>
                                            @if($mhs->unread_logs > 0)
                                                <span class="badge bg-warning text-dark ms-1">{{ $mhs->unread_logs }}</span>
                                            @endif
                                            @if($mhs->unread_dokumen > 0)
                                                <span class="badge bg-info ms-1">{{ $mhs->unread_dokumen }} doc</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $mhs->nim }}</small></td>
                                        <td>
                                            <span class="badge {{ $mhs->ipk >= 3.0 ? 'bg-success' : ($mhs->ipk >= 2.75 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ number_format($mhs->ipk, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($mhs->status_semester == 'A')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $mhs->milestones_completed }}/5 milestone</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('dosen.mahasiswa.detail', $mhs->nim) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p class="mt-2">Tidak ada mahasiswa</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Notifikasi Logbook --}}
            @if($notifikasiLogbook->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="bi bi-bell"></i> Logbook Belum Dibaca</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($notifikasiLogbook as $notif)
                                <a href="{{ route('dosen.mahasiswa.detail', $notif->nim_mahasiswa) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $notif->mahasiswa->nama_mahasiswa }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $notif->nim_mahasiswa }}</small>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $notif->jumlah }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Mahasiswa Bermasalah --}}
            @if($mahasiswaBermasalah->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Mahasiswa Bermasalah</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($mahasiswaBermasalah as $mhs)
                                <a href="{{ route('dosen.mahasiswa.detail', $mhs->nim) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $mhs->nama_mahasiswa }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $mhs->nim }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger">IPK {{ number_format($mhs->ipk, 2) }}</span>
                                            <br>
                                            @if($mhs->status_semester == 'N')
                                                <small class="text-danger">Non-Aktif</small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Info Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informasi</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Total Mahasiswa:</strong> {{ $totalMhs }}</p>
                    <p class="small mb-2"><strong>Mahasiswa Aktif:</strong> {{ $semuaMahasiswa->where('status_semester', 'A')->count() }}</p>
                    <p class="small mb-2"><strong>Mahasiswa Non-Aktif:</strong> {{ $semuaMahasiswa->where('status_semester', 'N')->count() }}</p>
                    <p class="small mb-0"><strong>Angkatan:</strong> 
                        @foreach($angkatan as $thn)
                            <span class="badge bg-secondary me-1">{{ $thn }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
