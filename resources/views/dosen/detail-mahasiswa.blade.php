@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="container my-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>{{ $mahasiswa->nama_mahasiswa }}</h3>
            <p class="text-muted mb-0">NIM: {{ $mahasiswa->nim }} | Angkatan: {{ $mahasiswa->angkatan }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dosen.mahasiswa.cetak-laporan', $mahasiswa->nim) }}" class="btn btn-success" target="_blank">
                <i class="bi bi-printer"></i> Cetak Laporan
            </a>
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info & Quick Actions --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Program Studi</th>
                                    <td>{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Dosen PA</th>
                                    <td>{{ $mahasiswa->dosenPA->nama_dosen ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>IPK</th>
                                    <td><strong class="fs-5 {{ $mahasiswa->ipk >= 3.0 ? 'text-success' : ($mahasiswa->ipk >= 2.75 ? 'text-warning' : 'text-danger') }}">{{ number_format($mahasiswa->ipk, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>IPS Terakhir</th>
                                    <td>{{ number_format($mahasiswa->ips, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Total SKS</th>
                                    <td>{{ $mahasiswa->total_sks }}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>{{ $mahasiswa->semester_berjalan }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($mahasiswa->status_semester == 'A')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>KRS</th>
                                    <td>
                                        @if($mahasiswa->krs_disetujui)
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-warning">Belum Disetujui</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($mahasiswa->krs_disetujui)
                            <form action="{{ route('dosen.mahasiswa.reject-krs', $mahasiswa->nim) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Yakin ingin tolak KRS?')">
                                    <i class="bi bi-x-circle"></i> Tolak KRS
                                </button>
                            </form>
                        @else
                            <form action="{{ route('dosen.mahasiswa.approve-krs', $mahasiswa->nim) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Setujui KRS
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('dosen.mahasiswa.toggle-status', $mahasiswa->nim) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100" onclick="return confirm('Yakin ingin ubah status?')">
                                <i class="bi bi-toggle-on"></i> Ubah ke {{ $mahasiswa->status_semester == 'A' ? 'Non-Aktif' : 'Aktif' }}
                            </button>
                        </form>

                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalTambahLogbook">
                            <i class="bi bi-journal-plus"></i> Tambah Catatan Bimbingan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Riwayat Bimbingan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Riwayat Bimbingan ({{ $mahasiswa->logbook->count() }})</h5>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @forelse($mahasiswa->logbook as $log)
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
                        <div class="text-center py-4">
                            <i class="bi bi-journal-text text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Belum ada riwayat bimbingan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Tabs untuk Laporan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-lapor-nilai" data-bs-toggle="tab" data-bs-target="#lapor-nilai" type="button" role="tab">
                                <i class="bi bi-exclamation-triangle"></i> Lapor Nilai
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-penilaian" data-bs-toggle="tab" data-bs-target="#penilaian" type="button" role="tab">
                                <i class="bi bi-star"></i> Penilaian
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-peringatan" data-bs-toggle="tab" data-bs-target="#peringatan" type="button" role="tab">
                                <i class="bi bi-megaphone"></i> Peringatan Akademik
                                @if($mahasiswa->nilaiBermasalah->count() > 0)
                                    <span class="badge bg-danger ms-1">{{ $mahasiswa->nilaiBermasalah->count() }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        {{-- Tab Lapor Nilai --}}
                        <div class="tab-pane fade show active" id="lapor-nilai" role="tabpanel">
                            <div class="mb-3">
                                <h5 class="mb-2"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Lapor Nilai Bermasalah (C/D/E)</h5>
                                <p class="text-muted mb-3">Masukkan semua mata kuliah bermasalah sekaligus. Laporan ini akan menggantikan laporan sebelumnya.</p>
                            </div>

                            <form action="{{ route('dosen.mahasiswa.nilai-bermasalah.store', $mahasiswa->nim) }}" method="POST" id="formNilaiBermasalah">
                                @csrf
                                <div id="nilai-container">
                                    {{-- Initial Row --}}
                                    <div class="row mb-3 nilai-row">
                                        <div class="col-md-5">
                                            <label class="form-label">Ketik nama mata kuliah...</label>
                                            <input type="text" name="nama_mk[]" class="form-control" placeholder="Contoh: Algoritma Pemrograman">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Nilai</label>
                                            <select name="nilai_huruf[]" class="form-select">
                                                <option value="">Pilih</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Semester</label>
                                            <input type="number" name="semester_diambil[]" class="form-control" placeholder="Smt" min="1" max="14">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-nilai" style="visibility: hidden;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahNilai">
                                        <i class="bi bi-plus-circle"></i> Tambah Mata Kuliah
                                    </button>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-send"></i> Simpan Laporan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Tab Penilaian Soft Skill --}}
                        <div class="tab-pane fade" id="penilaian" role="tabpanel">
                            @php
                                $currentYear = date('Y');
                                $currentMonth = date('n');
                                $periodeSekarang = $currentYear . ' ' . (($currentMonth >= 2 && $currentMonth <= 7) ? 'Genap' : 'Ganjil');
                            @endphp

                            <div class="mb-3">
                                <h5 class="mb-2"><i class="bi bi-star-fill text-warning"></i> Form Penilaian Soft Skill</h5>
                                <p class="text-muted mb-0">Periode: <strong>{{ $periodeSekarang }}</strong>. Beri skor 1-5.</p>
                            </div>

                            <form action="{{ route('dosen.mahasiswa.evaluasi-softskill.store', $mahasiswa->nim) }}" method="POST">
                                @csrf
                                <input type="hidden" name="periode_evaluasi" value="{{ $periodeSekarang }}">

                                {{-- Disiplin & Komitmen --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Disiplin & Komitmen</label>
                                    <select name="skor_disiplin" class="form-select" required>
                                        <option value="">Pilih Skor</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>

                                {{-- Partisipasi & Keaktifan --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Partisipasi & Keaktifan</label>
                                    <select name="skor_partisipasi" class="form-select" required>
                                        <option value="">Pilih Skor</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>

                                {{-- Etika & Sopan Santun --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Etika & Sopan Santun</label>
                                    <select name="skor_etika" class="form-select" required>
                                        <option value="">Pilih Skor</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>

                                {{-- Kepemimpinan & Kerjasama --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Kepemimpinan & Kerjasama</label>
                                    <select name="skor_kepemimpinan" class="form-select" required>
                                        <option value="">Pilih Skor</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-send"></i> Kirim Penilaian
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Tab Peringatan Akademik --}}
                        <div class="tab-pane fade" id="peringatan" role="tabpanel">
                            @if($mahasiswa->nilaiBermasalah->count() > 0)
                                <div class="alert alert-warning border-warning shadow-sm mb-4" style="border-left: 4px solid #ffc107;">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="alert-heading mb-2">
                                                <i class="bi bi-megaphone"></i> Peringatan Akademik
                                            </h5>
                                            <p class="mb-3">Daftar mata kuliah dengan nilai C, D, atau E.</p>
                                            
                                            <div class="mb-3">
                                                @foreach($mahasiswa->nilaiBermasalah as $nilai)
                                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white rounded border">
                                                        <div>
                                                            <strong>{{ $nilai->nama_mk }}</strong>
                                                            <br>
                                                            <small class="text-muted">Semester {{ $nilai->semester_diambil }}</small>
                                                        </div>
                                                        <span class="badge bg-danger fs-6">{{ $nilai->nilai_huruf }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <button type="button" class="btn btn-success" id="btnKirimPeringatan">
                                                <i class="bi bi-send"></i> Kirim Peringatan ke Logbook
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Preview Template --}}
                                <div class="card border-info mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="bi bi-file-text"></i> Preview Template Peringatan</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <strong>Topik:</strong> Peringatan Nilai Akademik
                                        </div>
                                        <div>
                                            <strong>Isi Pembahasan:</strong>
                                            <div class="border rounded p-3 mt-2 bg-light">
                                                <p class="mb-2">Berdasarkan laporan, terdapat beberapa nilai yang perlu mendapat perhatian khusus:</p>
                                                <ul class="mb-2">
                                                    @foreach($mahasiswa->nilaiBermasalah as $nilai)
                                                        <li><strong>{{ $nilai->nama_mk }}</strong> (Nilai: {{ $nilai->nilai_huruf }})</li>
                                                    @endforeach
                                                </ul>
                                                <p class="mb-0">Mohon segera diskusikan rencana perbaikan untuk mata kuliah di atas.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                    <h5 class="mt-3 text-success">Tidak Ada Peringatan</h5>
                                    <p class="text-muted">Tidak ada nilai bermasalah yang perlu ditindaklanjuti.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
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
                <div class="card-header bg-white">
                    <h5 class="mb-0">Dokumen ({{ $mahasiswa->dokumen->count() }})</h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @forelse($mahasiswa->dokumen as $doc)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-pdf text-danger fs-4 me-2"></i>
                                    <div>
                                        <strong>{{ $doc->judul_dokumen }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($doc->tanggal_unggah)->format('d M Y') }} | 
                                            {{ number_format($doc->ukuran_file / 1024, 2) }} KB
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="viewPDF('{{ asset('storage/dokumen/' . urlencode($doc->nama_file)) }}', '{{ $doc->judul_dokumen }}')"
                                        title="Lihat PDF">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <span class="badge {{ $doc->status_baca_dosen == 'Sudah Dilihat' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $doc->status_baca_dosen == 'Sudah Dilihat' ? 'Dilihat' : 'Baru' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="bi bi-file-earmark-pdf text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2 mb-0 small">Belum ada dokumen</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pencapaian --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pencapaian</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalUpdatePencapaian">
                        <i class="bi bi-pencil"></i> Update
                    </button>
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

                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar bg-success progress-bar-striped" style="width: {{ $progress }}%">
                            {{ $progress }}%
                        </div>
                    </div>

                    <ul class="list-group list-group-flush">
                        @foreach($daftarPencapaian as $item)
                            @php
                                $selesai = isset($pencapaianMap[$item]) && $pencapaianMap[$item]->status == 'Selesai';
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="{{ $selesai ? 'text-success fw-bold' : 'text-muted' }}">
                                    @if($selesai)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-circle text-muted"></i>
                                    @endif
                                    {{ $item }}
                                </span>
                                @if($selesai && isset($pencapaianMap[$item]->tanggal_selesai))
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($pencapaianMap[$item]->tanggal_selesai)->format('d/m/Y') }}
                                    </small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Riwayat Akademik --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Riwayat Akademik</h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @forelse($mahasiswa->riwayatAkademik as $riwayat)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>Semester {{ $riwayat->semester }}</strong>
                                <br>
                                <small class="text-muted">{{ $riwayat->sks_semester }} SKS</small>
                            </div>
                            <span class="badge {{ $riwayat->ip_semester >= 3.0 ? 'bg-success' : ($riwayat->ip_semester >= 2.75 ? 'bg-warning' : 'bg-danger') }} fs-6">
                                {{ number_format($riwayat->ip_semester, 2) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Update Pencapaian --}}
<div class="modal fade" id="modalUpdatePencapaian" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-flag-fill"></i> Update Kemajuan Studi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dosen.mahasiswa.pencapaian.update', $mahasiswa->nim) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Centang pencapaian yang sudah selesai dan isi tanggal selesainya.</p>
                    
                    @php
                        $daftarPencapaian = [
                            'Seminar Proposal', 
                            'Ujian Komperehensif',
                            'Seminar Hasil', 
                            'Ujian Skripsi (Yudisium)', 
                            'Publikasi Jurnal'
                        ];
                        $pencapaianMap = $mahasiswa->pencapaian->keyBy('nama_pencapaian');
                    @endphp

                    @foreach($daftarPencapaian as $item)
                        @php
                            $completed = isset($pencapaianMap[$item]) && $pencapaianMap[$item]->status == 'Selesai';
                            $tanggal = $completed ? $pencapaianMap[$item]->tanggal_selesai : '';
                        @endphp
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pencapaian[]" value="{{ $item }}" 
                                       id="pencapaian{{ $loop->index }}" {{ $completed ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="pencapaian{{ $loop->index }}">
                                    {{ $item }}
                                </label>
                            </div>
                            <input type="date" name="tanggal_selesai[{{ $item }}]" class="form-control mt-2" 
                                   placeholder="Tanggal selesai" value="{{ $tanggal }}">
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Kemajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Logbook --}}
<div class="modal fade" id="modalTambahLogbook" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('dosen.mahasiswa.logbook.store', $mahasiswa->nim) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Catatan Bimbingan untuk {{ $mahasiswa->nama_mahasiswa }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Bimbingan</label>
                        <input type="date" name="tanggal_bimbingan" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Topik Bimbingan</label>
                        <input type="text" name="topik_bimbingan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Bimbingan</label>
                        <textarea name="isi_bimbingan" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindak Lanjut</label>
                        <textarea name="tindak_lanjut" class="form-control" rows="3"></textarea>
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
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0,
                    max: 4,
                    ticks: {
                        stepSize: 0.5
                    }
                }
            }
        }
    });

    // Dynamic Nilai Bermasalah Form
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('nilai-container');
        const btnTambah = document.getElementById('btnTambahNilai');
        
        if (btnTambah) {
            btnTambah.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'row mb-3 nilai-row';
                newRow.innerHTML = `
                    <div class="col-md-5">
                        <input type="text" name="nama_mk[]" class="form-control" placeholder="Ketik nama mata kuliah...">
                    </div>
                    <div class="col-md-3">
                        <select name="nilai_huruf[]" class="form-select">
                            <option value="">Pilih</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="semester_diambil[]" class="form-control" placeholder="Smt" min="1" max="14">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-nilai">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                
                container.appendChild(newRow);
                
                // Add remove listener
                const removeBtn = newRow.querySelector('.remove-nilai');
                removeBtn.addEventListener('click', function() {
                    newRow.remove();
                });
            });
        }
        
        // Initial remove buttons (delegated event)
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-nilai')) {
                const row = e.target.closest('.nilai-row');
                if (container.querySelectorAll('.nilai-row').length > 1) {
                    row.remove();
                }
            }
        });

        // Kirim Peringatan ke Logbook
        const btnKirimPeringatan = document.getElementById('btnKirimPeringatan');
        if (btnKirimPeringatan) {
            btnKirimPeringatan.addEventListener('click', function() {
                // Get nilai bermasalah data
                const nilaiBermasalah = @json($mahasiswa->nilaiBermasalah);
                
                // Build list for template
                let nilaiList = '';
                nilaiBermasalah.forEach(function(nilai) {
                    nilaiList += '- ' + nilai.nama_mk + ' (Nilai: ' + nilai.nilai_huruf + ')\n';
                });
                
                // Template text
                const topik = 'Peringatan Nilai Akademik';
                const isi = 'Berdasarkan laporan, terdapat beberapa nilai yang perlu mendapat perhatian khusus:\n\n' + 
                           nilaiList + 
                           '\nMohon segera diskusikan rencana perbaikan untuk mata kuliah di atas.';
                
                // Set today's date
                const today = new Date().toISOString().split('T')[0];
                
                // Fill modal form
                document.querySelector('#modalTambahLogbook input[name="tanggal_bimbingan"]').value = today;
                document.querySelector('#modalTambahLogbook input[name="topik_bimbingan"]').value = topik;
                document.querySelector('#modalTambahLogbook textarea[name="isi_bimbingan"]').value = isi;
                
                // Open modal
                const modal = new bootstrap.Modal(document.getElementById('modalTambahLogbook'));
                modal.show();
            });
        }
    });
</script>
{{-- Modal Preview PDF --}}
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="pdfViewerModalLabel">
                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    <span id="pdfTitle">Preview Dokumen</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;" src=""></iframe>
            </div>
            <div class="modal-footer">
                <a id="pdfDownload" href="" download class="btn btn-success">
                    <i class="bi bi-download me-2"></i>Download PDF
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Function to view PDF in modal
function viewPDF(pdfUrl, pdfTitle) {
    document.getElementById('pdfTitle').textContent = pdfTitle;
    document.getElementById('pdfViewer').src = pdfUrl;
    document.getElementById('pdfDownload').href = pdfUrl;
    
    const modal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
    modal.show();
}

// Clear iframe when modal is hidden to stop loading
document.getElementById('pdfViewerModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('pdfViewer').src = '';
});
</script>
@endsection
