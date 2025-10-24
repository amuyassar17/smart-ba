@extends('layouts.app')

@section('title', 'Profil Dosen')

@section('content')
<div class="container my-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><i class="bi bi-person-badge"></i> Profil Dosen</h3>
            <p class="text-muted mb-0">Informasi lengkap dan statistik bimbingan</p>
        </div>
        <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        {{-- Left Column - Personal Info --}}
        <div class="col-lg-4">
            {{-- Profile Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($dosen->foto_dosen)
                            <img src="{{ asset('storage/' . $dosen->foto_dosen) }}" 
                                 alt="Foto" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px; font-size: 4rem;">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $dosen->nama_dosen }}</h4>
                    <p class="text-muted mb-3">NIDN: {{ $dosen->nidn_dosen }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-primary">Dosen Pembimbing Akademik</span>
                    </div>
                </div>
            </div>

            {{-- Statistics Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Ringkasan Bimbingan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Total Mahasiswa Bimbingan</label>
                        <h3 class="mb-0 text-primary">{{ $totalMahasiswa }}</h3>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small">Mahasiswa Aktif</label>
                        <h4 class="mb-0 text-success">{{ $mahasiswaAktif }}</h4>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <label class="text-muted small">Rata-rata IPK Mahasiswa</label>
                        <h4 class="mb-0 {{ $rataRataIPK >= 3.0 ? 'text-success' : ($rataRataIPK >= 2.75 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format($rataRataIPK, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Evaluation Summary --}}
            @if($evaluasiDosen->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-star-fill"></i> Evaluasi Mahasiswa</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h2 class="mb-0 text-warning">{{ number_format($rataKeseluruhan, 2) }}</h2>
                        <p class="text-muted small mb-0">dari 5.0</p>
                        <p class="text-muted small">Berdasarkan {{ $evaluasiDosen->count() }} evaluasi</p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small">Komunikasi</span>
                            <strong>{{ number_format($rataKomunikasi, 2) }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ ($rataKomunikasi / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small">Membantu</span>
                            <strong>{{ number_format($rataMembantu, 2) }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ ($rataMembantu / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small">Memberikan Solusi</span>
                            <strong>{{ number_format($rataSolusi, 2) }}</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ ($rataSolusi / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column - Details --}}
        <div class="col-lg-8">
            {{-- Info Dosen --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Dosen</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama Lengkap</th>
                            <td>{{ $dosen->nama_dosen }}</td>
                        </tr>
                        <tr>
                            <th>NIDN</th>
                            <td>{{ $dosen->nidn_dosen }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><span class="badge bg-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <th>Jumlah Mahasiswa Bimbingan</th>
                            <td><strong>{{ $totalMahasiswa }}</strong> mahasiswa</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Statistik Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-people-fill fs-1 mb-2 opacity-75"></i>
                            <h3 class="mb-1">{{ $totalMahasiswa }}</h3>
                            <small class="text-uppercase opacity-75">Total Mahasiswa</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-check-circle-fill fs-1 mb-2 opacity-75"></i>
                            <h3 class="mb-1">{{ $mahasiswaAktif }}</h3>
                            <small class="text-uppercase opacity-75">Mahasiswa Aktif</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-graph-up-arrow fs-1 mb-2 opacity-75"></i>
                            <h3 class="mb-1">{{ number_format($rataRataIPK, 2) }}</h3>
                            <small class="text-uppercase opacity-75">Rata-rata IPK</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Mahasiswa Bimbingan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Mahasiswa Bimbingan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Program Studi</th>
                                    <th class="text-center">IPK</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mahasiswaBimbingan as $index => $mhs)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $mhs->nama_mahasiswa }}</strong></td>
                                        <td><small>{{ $mhs->nim }}</small></td>
                                        <td><small>{{ $mhs->programStudi->nama_prodi ?? '-' }}</small></td>
                                        <td class="text-center">
                                            <span class="badge {{ $mhs->ipk >= 3.0 ? 'bg-success' : ($mhs->ipk >= 2.75 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ number_format($mhs->ipk, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($mhs->status_semester == 'A')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada mahasiswa bimbingan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Evaluasi Detail --}}
            @if($evaluasiDosen->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Detail Evaluasi dari Mahasiswa</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @foreach($evaluasiDosen as $eval)
                        <div class="mb-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ $eval->mahasiswa->nama_mahasiswa ?? 'Mahasiswa' }}</strong>
                                    <br>
                                    <small class="text-muted">Periode: {{ $eval->periode_evaluasi }}</small>
                                </div>
                                <span class="badge bg-warning text-dark">
                                    {{ number_format(($eval->skor_komunikasi + $eval->skor_membantu + $eval->skor_solusi) / 3, 2) }}/5
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">Komunikasi: <strong>{{ $eval->skor_komunikasi }}/5</strong></small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Membantu: <strong>{{ $eval->skor_membantu }}/5</strong></small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Solusi: <strong>{{ $eval->skor_solusi }}/5</strong></small>
                                </div>
                            </div>
                            @if($eval->saran_kritik)
                                <div class="mt-2 p-2 bg-white rounded">
                                    <small class="text-muted d-block mb-1"><i class="bi bi-chat-quote"></i> Saran & Kritik:</small>
                                    <p class="mb-0 small">{{ $eval->saran_kritik }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
