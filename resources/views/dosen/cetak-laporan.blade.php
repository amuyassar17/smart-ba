<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bimbingan Akademik - {{ $mahasiswa->nama_mahasiswa }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
            body {
                font-size: 12pt;
            }
        }
        
        body {
            background: white;
            padding: 20px;
        }
        
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            gap: 15px;
        }
        
        .kop-surat .logo {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
        }
        
        .kop-surat .kop-text {
            flex-grow: 1;
            text-align: center;
        }
        
        .kop-surat h4 {
            margin: 5px 0;
            font-weight: bold;
        }
        
        .kop-surat p {
            margin: 2px 0;
            font-size: 11pt;
        }
        
        table {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .table-bordered {
            border: 1px solid #000 !important;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            padding: 8px;
        }
        
        .section-title {
            background: #f0f0f0;
            padding: 8px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
            border-left: 4px solid #0d6efd;
        }
        
        .ttd-section {
            margin-top: 40px;
        }
        
        .info-table td {
            padding: 5px 10px;
        }
        
        .info-table td:first-child {
            width: 30%;
            font-weight: bold;
        }
    </style>
</head>
<body>
    {{-- Button Print --}}
    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Cetak / Save PDF
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="bi bi-x"></i> Tutup
        </button>
    </div>

    {{-- Kop Surat --}}
    <div class="kop-surat">
        <div class="logo">
            <img src="{{ asset('images/logo-uin-palopo.png') }}" alt="Logo UIN Palopo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <div class="kop-text">
            <h4>UNIVERSITAS ISLAM NEGERI KOTA PALOPO</h4>
            <h4>FAKULTAS SYARIAH DAN HUKUM</h4>
            <p>Jalan Agatis, Kelurahan Balandai, Kecamatan Bara, Kota Palopo, Sulawesi Selatan</p>
            <p>Telp: +62821-93362277 | Email: kontak@uinpalopo.ac.id</p>
        </div>
    </div>

    {{-- Judul Laporan --}}
    <div class="text-center mb-4">
        <h5 class="fw-bold">LAPORAN BIMBINGAN AKADEMIK</h5>
        @if(isset($tampilkanSemua) && $tampilkanSemua)
            <p class="mb-1"><span class="badge bg-info">LAPORAN LENGKAP (SEMUA HISTORY)</span></p>
        @else
            <p class="mb-1"><span class="badge bg-success">LAPORAN AKTIF (NILAI BELUM DIKIRIM)</span></p>
        @endif
        <p class="mb-1">Periode: {{ date('Y') }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB</p>
    </div>

    {{-- Data Mahasiswa --}}
    <div class="section-title">I. DATA MAHASISWA</div>
    <table class="info-table">
        <tr>
            <td>Nama Lengkap</td>
            <td>: {{ $mahasiswa->nama_mahasiswa }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>: {{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>: {{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</td>
        </tr>
        <tr>
            <td>Angkatan</td>
            <td>: {{ $mahasiswa->angkatan }}</td>
        </tr>
        <tr>
            <td>Semester Berjalan</td>
            <td>: Semester {{ $mahasiswa->semester_berjalan }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ $mahasiswa->status_semester == 'A' ? 'Aktif' : 'Non-Aktif' }}</td>
        </tr>
        <tr>
            <td>Dosen Pembimbing Akademik</td>
            <td>: {{ $mahasiswa->dosenPA->nama_dosen ?? '-' }}</td>
        </tr>
    </table>

    {{-- Data Akademik --}}
    <div class="section-title">II. DATA AKADEMIK</div>
    <table class="info-table">
        <tr>
            <td>IPK (Indeks Prestasi Kumulatif)</td>
            <td>: <strong>{{ number_format($mahasiswa->ipk, 2) }}</strong></td>
        </tr>
        <tr>
            <td>IPS (Indeks Prestasi Semester)</td>
            <td>: {{ number_format($mahasiswa->ips, 2) }}</td>
        </tr>
        <tr>
            <td>Total SKS</td>
            <td>: {{ $mahasiswa->total_sks }} SKS</td>
        </tr>
        <tr>
            <td>Status KRS</td>
            <td>: {{ $mahasiswa->krs_disetujui ? 'Disetujui' : 'Belum Disetujui' }}</td>
        </tr>
    </table>

    {{-- Riwayat IP per Semester --}}
    <div class="section-title">III. RIWAYAT INDEKS PRESTASI PER SEMESTER</div>
    @if($mahasiswa->riwayatAkademik->count() > 0)
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="15%">Semester</th>
                    <th class="text-center" width="20%">IP Semester</th>
                    <th class="text-center" width="20%">SKS Semester</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa->riwayatAkademik as $riwayat)
                    <tr>
                        <td class="text-center">{{ $riwayat->semester }}</td>
                        <td class="text-center"><strong>{{ number_format($riwayat->ip_semester, 2) }}</strong></td>
                        <td class="text-center">{{ $riwayat->sks_semester }} SKS</td>
                        <td>
                            @if($riwayat->ip_semester >= 3.5)
                                Sangat Memuaskan
                            @elseif($riwayat->ip_semester >= 3.0)
                                Memuaskan
                            @elseif($riwayat->ip_semester >= 2.75)
                                Cukup
                            @else
                                Perlu Perbaikan
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="2" class="text-end"><strong>IPK Keseluruhan:</strong></td>
                    <td class="text-center"><strong>{{ $mahasiswa->total_sks }} SKS</strong></td>
                    <td class="text-center"><strong>{{ number_format($mahasiswa->ipk, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-muted">Belum ada data riwayat akademik.</p>
    @endif

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Pencapaian/Milestone --}}
    <div class="section-title">IV. PENCAPAIAN KEMAJUAN STUDI</div>
    @php
        $daftarPencapaian = [
            'Seminar Proposal', 
            'Ujian Komperehensif',
            'Seminar Hasil', 
            'Ujian Skripsi (Yudisium)', 
            'Publikasi Jurnal'
        ];
        $pencapaianMap = $mahasiswa->pencapaian->keyBy('nama_pencapaian');
        $selesai = $pencapaianMap->where('status', 'Selesai')->count();
        $progress = count($daftarPencapaian) > 0 ? round(($selesai / count($daftarPencapaian)) * 100) : 0;
    @endphp
    
    <p><strong>Progress Keseluruhan: {{ $progress }}% ({{ $selesai }}/{{ count($daftarPencapaian) }} selesai)</strong></p>
    
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th width="5%">No</th>
                <th width="50%">Nama Pencapaian</th>
                <th width="20%">Status</th>
                <th width="25%">Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarPencapaian as $index => $item)
                @php
                    $completed = isset($pencapaianMap[$item]) && $pencapaianMap[$item]->status == 'Selesai';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item }}</td>
                    <td class="text-center">{{ $completed ? '✓ Selesai' : '○ Belum' }}</td>
                    <td class="text-center">
                        @if($completed && isset($pencapaianMap[$item]->tanggal_selesai))
                            {{ \Carbon\Carbon::parse($pencapaianMap[$item]->tanggal_selesai)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Nilai Bermasalah --}}
    @if($mahasiswa->nilaiBermasalah->count() > 0)
        <div class="section-title">V. NILAI BERMASALAH (C/D/E)</div>
        @if(isset($tampilkanSemua) && $tampilkanSemua)
            <p><strong>Catatan:</strong> Laporan ini menampilkan <u>semua history</u> nilai bermasalah (termasuk yang sudah dikirim ke logbook).</p>
        @else
            <p><strong>Catatan:</strong> Laporan ini hanya menampilkan nilai bermasalah yang <u>belum dikirim</u> ke logbook (masih aktif).</p>
        @endif
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Mata Kuliah</th>
                    <th width="10%">Nilai</th>
                    <th width="10%">Semester</th>
                    <th width="20%">Tanggal Lapor</th>
                    <th width="15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa->nilaiBermasalah as $index => $nilai)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $nilai->nama_mk }}</td>
                        <td class="text-center"><strong>{{ $nilai->nilai_huruf }}</strong></td>
                        <td class="text-center">{{ $nilai->semester_diambil }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($nilai->tanggal_lapor)->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @if($nilai->dikirim_ke_logbook)
                                <span class="badge bg-secondary">Sudah Dikirim</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Dikirim</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(!isset($tampilkanSemua) || !$tampilkanSemua)
            <p class="text-danger"><em>*Perlu tindak lanjut untuk perbaikan nilai</em></p>
        @endif
    @endif

    {{-- Riwayat Bimbingan --}}
    <div class="section-title">VI. RIWAYAT BIMBINGAN AKADEMIK</div>
    @if($mahasiswa->logbook->count() > 0)
        <p><strong>Total Bimbingan: {{ $mahasiswa->logbook->count() }} kali pertemuan</strong></p>
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tanggal</th>
                    <th width="25%">Topik</th>
                    <th width="43%">Isi Bimbingan</th>
                    <th width="15%">Pengisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa->logbook->take(10) as $index => $log)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->format('d/m/Y') }}</td>
                        <td>{{ $log->topik_bimbingan }}</td>
                        <td style="font-size: 10pt;">{{ Str::limit($log->isi_bimbingan, 100) }}</td>
                        <td class="text-center">{{ $log->pengisi }}</td>
                    </tr>
                @endforeach
                @if($mahasiswa->logbook->count() > 10)
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <em>... dan {{ $mahasiswa->logbook->count() - 10 }} bimbingan lainnya</em>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
        <p class="text-muted">Belum ada catatan bimbingan.</p>
    @endif

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Evaluasi Soft Skill --}}
    @if($mahasiswa->evaluasiSoftskill->count() > 0)
        <div class="section-title">VII. EVALUASI SOFT SKILL TERAKHIR</div>
        @php
            $latestPeriod = $mahasiswa->evaluasiSoftskill->first()->periode_evaluasi ?? '-';
            $evaluasiByKategori = $mahasiswa->evaluasiSoftskill->groupBy('kategori');
        @endphp
        <p><strong>Periode: {{ $latestPeriod }}</strong></p>
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th width="60%">Kategori</th>
                    <th width="40%" class="text-center">Skor (1-5)</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['Disiplin & Komitmen', 'Partisipasi & Keaktifan', 'Etika & Sopan Santun', 'Kepemimpinan & Kerjasama'] as $kategori)
                    @php
                        $evaluasi = $evaluasiByKategori->get($kategori)?->first();
                        $skor = $evaluasi ? $evaluasi->skor : '-';
                    @endphp
                    <tr>
                        <td>{{ $kategori }}</td>
                        <td class="text-center"><strong>{{ $skor }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Kesimpulan dan Rekomendasi --}}
    <div class="section-title">VIII. KESIMPULAN DAN REKOMENDASI</div>
    <div style="min-height: 100px; padding: 10px; border: 1px solid #ddd;">
        @php
            $ipk = $mahasiswa->ipk;
            $totalBimbingan = $mahasiswa->logbook->count();
            $progressPencapaian = $progress;
        @endphp
        
        <p><strong>Kesimpulan:</strong></p>
        <ul>
            <li>Mahasiswa memiliki IPK {{ number_format($ipk, 2) }} 
                @if($ipk >= 3.5)
                    dengan predikat <strong>Sangat Memuaskan</strong>
                @elseif($ipk >= 3.0)
                    dengan predikat <strong>Memuaskan</strong>
                @elseif($ipk >= 2.75)
                    dengan predikat <strong>Cukup</strong>
                @else
                    yang <strong>perlu ditingkatkan</strong>
                @endif
            </li>
            <li>Progress kemajuan studi: <strong>{{ $progressPencapaian }}%</strong></li>
            <li>Aktivitas bimbingan: <strong>{{ $totalBimbingan }} kali pertemuan</strong></li>
            @if($mahasiswa->nilaiBermasalah->count() > 0)
                <li class="text-danger">Terdapat <strong>{{ $mahasiswa->nilaiBermasalah->count() }} nilai bermasalah</strong> yang perlu diperbaiki</li>
            @endif
        </ul>
        
        <p><strong>Rekomendasi:</strong></p>
        <ul>
            @if($ipk < 2.75)
                <li>Mahasiswa perlu meningkatkan prestasi akademik melalui bimbingan intensif</li>
            @endif
            @if($mahasiswa->nilaiBermasalah->count() > 0)
                <li>Segera lakukan perbaikan nilai untuk mata kuliah yang bermasalah</li>
            @endif
            @if($progressPencapaian < 50)
                <li>Percepat pencapaian milestone akademik sesuai jadwal</li>
            @endif
            @if($totalBimbingan < 5)
                <li>Tingkatkan frekuensi bimbingan akademik dengan dosen PA</li>
            @endif
            <li>Konsisten dalam mengikuti perkuliahan dan memenuhi kewajiban akademik</li>
        </ul>
    </div>

    {{-- Tanda Tangan --}}
    <div class="ttd-section">
        <div class="row">
            <div class="col-6">
                <p class="mb-1">Mengetahui,</p>
                <p class="mb-1 fw-bold">Mahasiswa</p>
                <br><br><br>
                <p class="mb-0 fw-bold">{{ $mahasiswa->nama_mahasiswa }}</p>
                <p class="mb-0">NIM: {{ $mahasiswa->nim }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1">Palopo, {{ date('d F Y') }}</p>
                <p class="mb-1 fw-bold">Dosen Pembimbing Akademik</p>
                <br><br><br>
                <p class="mb-0 fw-bold">{{ $dosen->nama_dosen }}</p>
                <p class="mb-0">NIDN: {{ $dosen->nidn_dosen }}</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
