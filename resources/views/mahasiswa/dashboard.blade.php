@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container my-5">
    {{-- Header Info --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Selamat Datang, {{ $mahasiswa->nama_mahasiswa }}</h4>
                            <p class="text-muted mb-1">NIM: {{ $mahasiswa->nim }} | Angkatan: {{ $mahasiswa->angkatan }}</p>
                            <p class="text-muted mb-0">Dosen PA: {{ $mahasiswa->dosenPA->nama_dosen ?? '-' }}</p>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-1">IPK</h5>
                            <h2 class="mb-0 text-primary">{{ number_format($mahasiswa->ipk, 2) }}</h2>
                            <small class="text-muted">Total SKS: {{ $mahasiswa->total_sks }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($notifKRS)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> KRS Anda telah disetujui oleh Dosen PA!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-gradient-blue border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-calendar3 fs-1 opacity-75"></i>
                    </div>
                    <h6 class="card-title text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">Semester</h6>
                    <h2 class="mb-0 fw-bold">{{ $mahasiswa->semester_berjalan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gradient-green border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-graph-up fs-1 opacity-75"></i>
                    </div>
                    <h6 class="card-title text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">IPS Terakhir</h6>
                    <h2 class="mb-0 fw-bold">{{ number_format($mahasiswa->ips, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gradient-purple border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-journal-text fs-1 opacity-75"></i>
                    </div>
                    <h6 class="card-title text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">Logbook</h6>
                    <h2 class="mb-0 fw-bold">{{ $logbook->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gradient-orange border-0 shadow-lg animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-file-earmark-text fs-1 opacity-75"></i>
                    </div>
                    <h6 class="card-title text-uppercase mb-2 opacity-90" style="font-size: 0.75rem; letter-spacing: 1px;">Dokumen</h6>
                    <h2 class="mb-0 fw-bold">{{ $dokumen->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Riwayat Bimbingan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Bimbingan</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahLogbook">
                        <i class="bi bi-plus-circle"></i> Tambah Catatan
                    </button>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @forelse($logbook as $log)
                        <div class="mb-3 p-3 border-start border-4 {{ $log->pengisi == 'Dosen' ? 'border-primary bg-light' : 'border-success' }} rounded">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">{{ $log->topik_bimbingan }}</h6>
                                <span class="badge {{ $log->pengisi == 'Dosen' ? 'bg-primary' : 'bg-success' }}">
                                    {{ $log->pengisi }}
                                </span>
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->format('d F Y') }}</small>
                            <p class="mt-2 mb-1">{{ $log->isi_bimbingan }}</p>
                            @if($log->tindak_lanjut)
                                <div class="mt-2 p-2 bg-white rounded">
                                    <small class="text-muted">Tindak Lanjut:</small>
                                    <p class="mb-0 small">{{ $log->tindak_lanjut }}</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-journal-text text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Belum ada riwayat bimbingan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Grafik IP Semester --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Grafik IP per Semester</h5>
                </div>
                <div class="card-body">
                    <canvas id="ipChart" height="80"></canvas>
                </div>
            </div>

            {{-- Dokumen --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dokumen</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalUploadDokumen">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </div>
                <div class="card-body">
                    @forelse($dokumen as $doc)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                            <div>
                                <strong>{{ $doc->judul_dokumen }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($doc->tanggal_unggah)->format('d M Y') }} | 
                                    {{ number_format($doc->ukuran_file / 1024, 2) }} KB
                                </small>
                            </div>
                            <span class="badge {{ $doc->status_baca_dosen == 'Sudah Dilihat' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $doc->status_baca_dosen }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada dokumen</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Pencapaian Studi --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Pencapaian Studi</h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                             style="width: {{ $persentaseKemajuan }}%">
                            {{ $persentaseKemajuan }}%
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($daftarPencapaian as $item)
                            @php
                                $selesai = isset($pencapaian[$item]) && $pencapaian[$item]->status == 'Selesai';
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="{{ $selesai ? 'text-success fw-bold' : 'text-muted' }}">
                                    @if($selesai)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-circle text-muted"></i>
                                    @endif
                                    {{ $item }}
                                </span>
                                @if($selesai && isset($pencapaian[$item]->tanggal_selesai))
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($pencapaian[$item]->tanggal_selesai)->format('d/m/Y') }}
                                    </small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Evaluasi Softskill --}}
            @if($evaluasiSoftskill->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Evaluasi Softskill</h5>
                    </div>
                    <div class="card-body">
                        @foreach($evaluasiSoftskill as $periode => $evals)
                            <h6 class="text-primary">{{ $periode }}</h6>
                            @foreach($evals as $eval)
                                <div class="mb-2">
                                    <small class="text-muted">{{ $eval->kategori }}</small>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" style="width: {{ ($eval->skor / 5) * 100 }}%">
                                            {{ $eval->skor }}/5
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <hr>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Evaluasi Dosen PA --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-star-fill text-warning me-2"></i>Evaluasi Dosen PA
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        <i class="bi bi-calendar3 me-1"></i>Periode: <strong>{{ $periodeSekarang }}</strong>
                    </p>
                    
                    @if($sudahEvaluasiDosen)
                        {{-- Show submitted evaluation --}}
                        <div class="alert alert-success mb-3" style="border-radius: 12px;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Evaluasi Terkirim</strong>
                                    <p class="mb-0 small">{{ \Carbon\Carbon::parse($evaluasiDosenTerakhir->tanggal_submit)->format('d F Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2">Hasil Evaluasi Anda:</small>
                            
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small><i class="bi bi-chat-dots me-1"></i>Komunikasi</small>
                                    <span class="badge bg-primary">{{ $evaluasiDosenTerakhir->skor_komunikasi }}/5</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: {{ ($evaluasiDosenTerakhir->skor_komunikasi / 5) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small><i class="bi bi-hand-thumbs-up me-1"></i>Membantu</small>
                                    <span class="badge bg-success">{{ $evaluasiDosenTerakhir->skor_membantu }}/5</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($evaluasiDosenTerakhir->skor_membantu / 5) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small><i class="bi bi-lightbulb me-1"></i>Solusi</small>
                                    <span class="badge bg-info">{{ $evaluasiDosenTerakhir->skor_solusi }}/5</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: {{ ($evaluasiDosenTerakhir->skor_solusi / 5) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            @if($evaluasiDosenTerakhir->saran_kritik)
                                <div class="mt-3 p-3 bg-light rounded" style="border-radius: 12px;">
                                    <small class="text-muted d-block mb-1"><i class="bi bi-chat-square-quote me-1"></i>Saran & Kritik:</small>
                                    <p class="mb-0 small">{{ $evaluasiDosenTerakhir->saran_kritik }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle me-1"></i>Terima kasih atas evaluasi Anda!
                        </p>
                    @else
                        {{-- Show evaluation form button --}}
                        <div class="text-center py-3">
                            <i class="bi bi-star fs-1 text-warning mb-3"></i>
                            <p class="text-muted mb-3">Berikan evaluasi kepada Dosen PA Anda untuk periode ini</p>
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalEvaluasiDosen">
                                <i class="bi bi-pencil-square me-2"></i>Isi Evaluasi Sekarang
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Logbook --}}
<div class="modal fade" id="modalTambahLogbook" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mahasiswa.logbook.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Catatan Bimbingan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Bimbingan</label>
                        <input type="date" name="tanggal_bimbingan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Topik Bimbingan</label>
                        <input type="text" name="topik_bimbingan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Bimbingan</label>
                        <textarea name="isi_bimbingan" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Upload Dokumen --}}
<div class="modal fade" id="modalUploadDokumen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mahasiswa.dokumen.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Dokumen</label>
                        <input type="text" name="judul_dokumen" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File (PDF, DOC, DOCX - Max 5MB)</label>
                        <input type="file" name="file_dokumen" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Evaluasi Dosen --}}
<div class="modal fade" id="modalEvaluasiDosen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mahasiswa.evaluasi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Evaluasi Dosen PA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Komunikasi (1-5)</label>
                        <input type="number" name="skor_komunikasi" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Membantu (1-5)</label>
                        <input type="number" name="skor_membantu" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Memberikan Solusi (1-5)</label>
                        <input type="number" name="skor_solusi" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Saran & Kritik</label>
                        <textarea name="saran_kritik" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Evaluasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik IP Semester dengan desain modern
    const ctx = document.getElementById('ipChart').getContext('2d');
    const ipData = @json($riwayatAkademik);
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(139, 92, 246, 0.8)');
    gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.4)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ipData.map(r => 'Semester ' + r.semester),
            datasets: [{
                label: 'IP Semester',
                data: ipData.map(r => parseFloat(r.ip_semester)),
                borderColor: '#8b5cf6',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#8b5cf6',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 13,
                            weight: '600',
                            family: 'Lato'
                        },
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderColor: '#8b5cf6',
                    borderWidth: 2,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return ' IP: ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        color: '#6b7280'
                    }
                },
                y: {
                    beginAtZero: false,
                    min: 0,
                    max: 4,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        borderDash: [5, 5]
                    },
                    ticks: {
                        stepSize: 0.5,
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#6b7280',
                        callback: function(value) {
                            return value.toFixed(1);
                        }
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
</script>
@endsection
