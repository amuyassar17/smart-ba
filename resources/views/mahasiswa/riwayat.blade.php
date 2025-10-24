@extends('layouts.app')

@section('title', 'Riwayat Akademik')

@section('content')
<div class="container my-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><i class="bi bi-clock-history"></i> Riwayat Akademik</h3>
            <p class="text-muted mb-0">{{ $mahasiswa->nama_mahasiswa }} - {{ $mahasiswa->nim }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('mahasiswa.riwayat.lengkapi') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Lengkapi Riwayat
            </a>
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center text-white py-4">
                    <i class="bi bi-trophy-fill mb-2" style="font-size: 2rem; opacity: 0.9;"></i>
                    <h6 class="text-uppercase mb-2 fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">IPK</h6>
                    <h1 class="mb-0 fw-bold" style="font-size: 2.5rem;">{{ number_format($mahasiswa->ipk, 2) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-center text-white py-4">
                    <i class="bi bi-graph-up-arrow mb-2" style="font-size: 2rem; opacity: 0.9;"></i>
                    <h6 class="text-uppercase mb-2 fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">IPS Terakhir</h6>
                    <h1 class="mb-0 fw-bold" style="font-size: 2.5rem;">{{ number_format($mahasiswa->ips, 2) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-center text-white py-4">
                    <i class="bi bi-journal-check mb-2" style="font-size: 2rem; opacity: 0.9;"></i>
                    <h6 class="text-uppercase mb-2 fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Total SKS</h6>
                    <h1 class="mb-0 fw-bold" style="font-size: 2.5rem;">{{ $mahasiswa->total_sks }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-center text-white py-4">
                    <i class="bi bi-calendar3 mb-2" style="font-size: 2rem; opacity: 0.9;"></i>
                    <h6 class="text-uppercase mb-2 fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Semester</h6>
                    <h1 class="mb-0 fw-bold" style="font-size: 2.5rem;">{{ $mahasiswa->semester_berjalan }}</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Perkembangan IP --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Grafik Perkembangan IP</h5>
        </div>
        <div class="card-body">
            <canvas id="ipChart" height="80"></canvas>
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Tabel Riwayat Per Semester --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Riwayat IP per Semester</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="15%">Semester</th>
                                    <th class="text-center" width="20%">IP Semester</th>
                                    <th class="text-center" width="15%">SKS</th>
                                    <th width="35%">Status</th>
                                    <th class="text-center" width="15%">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatAkademik as $riwayat)
                                    <tr>
                                        <td class="text-center">
                                            <strong>{{ $riwayat->semester }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <h5 class="mb-0">
                                                <span class="badge {{ $riwayat->ip_semester >= 3.5 ? 'bg-success' : ($riwayat->ip_semester >= 3.0 ? 'bg-primary' : ($riwayat->ip_semester >= 2.75 ? 'bg-warning' : 'bg-danger')) }} fs-6">
                                                    {{ number_format($riwayat->ip_semester, 2) }}
                                                </span>
                                            </h5>
                                        </td>
                                        <td class="text-center">
                                            {{ $riwayat->sks_semester }} SKS
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                @php
                                                    $percentage = ($riwayat->ip_semester / 4) * 100;
                                                    $color = $riwayat->ip_semester >= 3.5 ? 'success' : ($riwayat->ip_semester >= 3.0 ? 'primary' : ($riwayat->ip_semester >= 2.75 ? 'warning' : 'danger'));
                                                @endphp
                                                <div class="progress-bar bg-{{ $color }}" style="width: {{ $percentage }}%">
                                                    {{ round($percentage) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($riwayat->ip_semester >= 3.5)
                                                <span class="badge bg-success">Excellent</span>
                                            @elseif($riwayat->ip_semester >= 3.0)
                                                <span class="badge bg-primary">Good</span>
                                            @elseif($riwayat->ip_semester >= 2.75)
                                                <span class="badge bg-warning">Fair</span>
                                            @else
                                                <span class="badge bg-danger">Poor</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">Belum ada data riwayat akademik</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($riwayatAkademik->count() > 0)
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>IPK Keseluruhan:</strong></td>
                                        <td class="text-center"><strong>{{ $mahasiswa->total_sks }} SKS</strong></td>
                                        <td colspan="2" class="text-center">
                                            <h4 class="mb-0">
                                                <span class="badge {{ $mahasiswa->ipk >= 3.5 ? 'bg-success' : ($mahasiswa->ipk >= 3.0 ? 'bg-primary' : ($mahasiswa->ipk >= 2.75 ? 'bg-warning' : 'bg-danger')) }} fs-5">
                                                    {{ number_format($mahasiswa->ipk, 2) }}
                                                </span>
                                            </h4>
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- Nilai Mahasiswa --}}
            @if($nilaiMahasiswa->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-card-checklist"></i> Daftar Nilai Mata Kuliah</h5>
                        <span class="badge bg-primary">{{ $nilaiMahasiswa->count() }} Mata Kuliah</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode MK</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nilaiMahasiswa as $nilai)
                                        <tr>
                                            <td><code>{{ $nilai->kode_mk }}</code></td>
                                            <td>{{ $nilai->nama_mk }}</td>
                                            <td class="text-center">{{ $nilai->semester_diambil }}</td>
                                            <td class="text-center">
                                                <span class="badge 
                                                    {{ in_array($nilai->nilai_huruf, ['A', 'A-']) ? 'bg-success' : 
                                                       (in_array($nilai->nilai_huruf, ['B+', 'B', 'B-']) ? 'bg-primary' : 
                                                       ($nilai->nilai_huruf == 'C' ? 'bg-warning' : 'bg-danger')) }}">
                                                    {{ $nilai->nilai_huruf }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- KRS Per Semester --}}
            @if($krs->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-journal-bookmark"></i> Kartu Rencana Studi (KRS)</h5>
                        <span class="badge bg-info">{{ $krs->count() }} Mata Kuliah</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Mata Kuliah</th>
                                        <th class="text-center">SKS</th>
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($krs->groupBy('semester_diambil') as $semester => $mataKuliahList)
                                        <tr class="table-secondary">
                                            <td colspan="5" class="fw-bold">
                                                <i class="bi bi-calendar3"></i> Semester {{ $semester }}
                                            </td>
                                        </tr>
                                        @foreach($mataKuliahList as $item)
                                            <tr>
                                                <td>{{ $item->mataKuliah->nama_mk ?? 'N/A' }}</td>
                                                <td class="text-center">{{ $item->mataKuliah->sks ?? '-' }}</td>
                                                <td class="text-center">{{ $item->semester_diambil }}</td>
                                                <td class="text-center">
                                                    @if($item->nilai_huruf)
                                                        <span class="badge 
                                                            {{ in_array($item->nilai_huruf, ['A', 'A-']) ? 'bg-success' : 
                                                               (in_array($item->nilai_huruf, ['B+', 'B', 'B-']) ? 'bg-primary' : 
                                                               ($item->nilai_huruf == 'C' ? 'bg-warning' : 'bg-danger')) }}">
                                                            {{ $item->nilai_huruf }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->sudah_dinilai)
                                                        <span class="badge bg-success">Sudah Dinilai</span>
                                                    @else
                                                        <span class="badge bg-warning">Belum Dinilai</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Statistik --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Statistik Akademik</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Semester Aktif</span>
                            <strong>{{ $riwayatAkademik->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Rata-rata IP</span>
                            <strong>{{ number_format($riwayatAkademik->avg('ip_semester'), 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">IP Tertinggi</span>
                            <strong class="text-success">{{ number_format($riwayatAkademik->max('ip_semester'), 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">IP Terendah</span>
                            <strong class="text-danger">{{ number_format($riwayatAkademik->min('ip_semester'), 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total SKS Diambil</span>
                            <strong>{{ $riwayatAkademik->sum('sks_semester') }} SKS</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nilai Bermasalah --}}
            @if($nilaiBermasalah->count() > 0)
                <div class="card shadow-sm border-danger mb-4">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Nilai Bermasalah</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-3">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                Mata kuliah dengan nilai C, D, atau E perlu diperbaiki
                            </small>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach($nilaiBermasalah as $nilai)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <strong class="d-block">{{ $nilai->nama_mk }}</strong>
                                            <small class="text-muted">Semester {{ $nilai->semester_diambil }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger mb-1">{{ $nilai->nilai_huruf }}</span>
                                            <br>
                                            <small class="badge bg-secondary">{{ $nilai->status_perbaikan }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Predikat Kelulusan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-award"></i> Predikat Kelulusan</h6>
                </div>
                <div class="card-body text-center">
                    @php
                        $ipk = $mahasiswa->ipk;
                        $predikat = '';
                        $color = '';
                        $icon = '';
                        
                        if ($ipk >= 3.76) {
                            $predikat = 'Summa Cum Laude';
                            $color = 'success';
                            $icon = 'trophy-fill';
                        } elseif ($ipk >= 3.51) {
                            $predikat = 'Magna Cum Laude';
                            $color = 'primary';
                            $icon = 'award-fill';
                        } elseif ($ipk >= 3.01) {
                            $predikat = 'Cum Laude';
                            $color = 'info';
                            $icon = 'award';
                        } elseif ($ipk >= 2.76) {
                            $predikat = 'Memuaskan';
                            $color = 'warning';
                            $icon = 'star';
                        } else {
                            $predikat = 'Memadai';
                            $color = 'secondary';
                            $icon = 'star-half';
                        }
                    @endphp
                    
                    <i class="bi bi-{{ $icon }} text-{{ $color }}" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 mb-2 text-{{ $color }}">{{ $predikat }}</h4>
                    <p class="text-muted mb-0">
                        <small>Berdasarkan IPK: {{ number_format($mahasiswa->ipk, 2) }}</small>
                    </p>

                    <hr class="my-3">
                    
                    <div class="text-start">
                        <small class="text-muted d-block mb-1"><strong>Kriteria Predikat:</strong></small>
                        <small class="d-block text-muted">• Summa Cum Laude: ≥ 3.76</small>
                        <small class="d-block text-muted">• Magna Cum Laude: 3.51 - 3.75</small>
                        <small class="d-block text-muted">• Cum Laude: 3.01 - 3.50</small>
                        <small class="d-block text-muted">• Memuaskan: 2.76 - 3.00</small>
                        <small class="d-block text-muted">• Memadai: &lt; 2.76</small>
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
    const ipData = @json($riwayatAkademik);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ipData.map(r => 'Semester ' + r.semester),
            datasets: [{
                label: 'IP Semester',
                data: ipData.map(r => r.ip_semester),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#667eea',
                pointHoverBorderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            family: "'Montserrat', sans-serif"
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
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
                        stepSize: 0.5,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
