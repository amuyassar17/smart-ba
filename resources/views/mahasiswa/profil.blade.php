@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@section('content')
<div class="container my-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><i class="bi bi-person-circle"></i> Profil Mahasiswa</h3>
            <p class="text-muted mb-0">Informasi lengkap data akademik dan pribadi</p>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
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
                        @if($mahasiswa->foto_mahasiswa)
                            <img src="{{ asset('storage/' . $mahasiswa->foto_mahasiswa) }}" 
                                 alt="Foto" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px; font-size: 4rem;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $mahasiswa->nama_mahasiswa }}</h4>
                    <p class="text-muted mb-3">{{ $mahasiswa->nim }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($mahasiswa->status_semester == 'A')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Non-Aktif</span>
                        @endif
                        <span class="badge bg-primary">Angkatan {{ $mahasiswa->angkatan }}</span>
                    </div>
                </div>
            </div>

            {{-- Academic Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-mortarboard"></i> Ringkasan Akademik</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">IPK</label>
                        <h3 class="mb-0 {{ $mahasiswa->ipk >= 3.0 ? 'text-success' : ($mahasiswa->ipk >= 2.75 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format($mahasiswa->ipk, 2) }}
                        </h3>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small">IPS Terakhir</label>
                        <h4 class="mb-0">{{ number_format($mahasiswa->ips, 2) }}</h4>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total SKS</span>
                            <strong>{{ $mahasiswa->total_sks }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Semester Berjalan</span>
                            <strong>{{ $mahasiswa->semester_berjalan }}</strong>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Batas SKS</span>
                            <strong>{{ $mahasiswa->batas_sks }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KRS Status --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-clipboard-check"></i> Status KRS</h6>
                </div>
                <div class="card-body">
                    @if($mahasiswa->krs_disetujui)
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill"></i>
                            <strong>KRS Disetujui</strong>
                            <p class="mb-0 small mt-2">KRS Anda telah disetujui oleh Dosen PA</p>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <strong>Menunggu Persetujuan</strong>
                            <p class="mb-0 small mt-2">KRS Anda sedang menunggu persetujuan Dosen PA</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column - Details --}}
        <div class="col-lg-8">
            {{-- Data Akademik --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Data Akademik</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Program Studi</th>
                            <td>{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dosen Pembimbing Akademik</th>
                            <td>{{ $mahasiswa->dosenPA->nama_dosen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>{{ $mahasiswa->nim }}</td>
                        </tr>
                        <tr>
                            <th>Angkatan</th>
                            <td>{{ $mahasiswa->angkatan }}</td>
                        </tr>
                        <tr>
                            <th>Semester Berjalan</th>
                            <td>Semester {{ $mahasiswa->semester_berjalan }}</td>
                        </tr>
                        <tr>
                            <th>Status Akademik</th>
                            <td>
                                @if($mahasiswa->status_semester == 'A')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Pencapaian --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-trophy"></i> Pencapaian Akademik</h5>
                </div>
                <div class="card-body">
                    @php
                        $daftarPencapaian = [
                            'Seminar Proposal', 
                            'Penelitian Selesai',
                            'Seminar Hasil', 
                            'Ujian Skripsi (Yudisium)', 
                            'Publikasi Jurnal'
                        ];
                        $pencapaianMap = $mahasiswa->pencapaian->keyBy('nama_pencapaian');
                        $selesai = $pencapaianMap->where('status', 'Selesai')->count();
                        $progress = count($daftarPencapaian) > 0 ? round(($selesai / count($daftarPencapaian)) * 100) : 0;
                    @endphp

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Progress Keseluruhan</span>
                            <strong>{{ $progress }}%</strong>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                 style="width: {{ $progress }}%">
                                {{ $selesai }}/{{ count($daftarPencapaian) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($daftarPencapaian as $item)
                            @php
                                $completed = isset($pencapaianMap[$item]) && $pencapaianMap[$item]->status == 'Selesai';
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="card {{ $completed ? 'border-success' : 'border-light' }}">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($completed)
                                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                                @else
                                                    <i class="bi bi-circle text-muted fs-3"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 {{ $completed ? 'text-success' : '' }}">{{ $item }}</h6>
                                                @if($completed && isset($pencapaianMap[$item]->tanggal_selesai))
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($pencapaianMap[$item]->tanggal_selesai)->format('d M Y') }}
                                                    </small>
                                                @else
                                                    <small class="text-muted">Belum Selesai</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Riwayat IP per Semester --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Riwayat IP per Semester</h5>
                </div>
                <div class="card-body">
                    <canvas id="ipChart" height="80"></canvas>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1 opacity-75">Semester Aktif</h6>
                                    <h3 class="mb-0">{{ $mahasiswa->riwayatAkademik->count() }}</h3>
                                </div>
                                <i class="bi bi-calendar3 fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1 opacity-75">Rata-rata IP</h6>
                                    <h3 class="mb-0">{{ number_format($mahasiswa->riwayatAkademik->avg('ip_semester'), 2) }}</h3>
                                </div>
                                <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik IP Semester
    const ctx = document.getElementById('ipChart').getContext('2d');
    const ipData = @json($mahasiswa->riwayatAkademik);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ipData.map(r => 'Sem ' + r.semester),
            datasets: [{
                label: 'IP Semester',
                data: ipData.map(r => r.ip_semester),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'IP: ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0,
                    max: 4,
                    ticks: {
                        stepSize: 0.5
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection
