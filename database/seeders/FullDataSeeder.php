<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FullDataSeeder extends Seeder
{
    /**
     * Import full data dari database lama
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting full data import...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing data
        $this->command->warn('Clearing existing data...');
        DB::table('krs')->truncate();
        DB::table('nilai_mahasiswa')->truncate();
        DB::table('nilai_bermasalah')->truncate();
        DB::table('riwayat_akademik')->truncate();
        DB::table('pencapaian')->truncate();
        DB::table('evaluasi_softskill')->truncate();
        DB::table('evaluasi_dosen')->truncate();
        DB::table('dokumen')->truncate();
        DB::table('logbook')->truncate();
        DB::table('mahasiswa')->truncate();
        DB::table('mata_kuliah')->truncate();
        DB::table('dosen')->truncate();
        DB::table('program_studi')->truncate();
        
        // 1. Program Studi
        $this->command->info('1/13 Importing program_studi...');
        DB::table('program_studi')->insert([
            ['id_prodi' => 1, 'nama_prodi' => 'Program Studi', 'created_at' => now(), 'updated_at' => now()],
            ['id_prodi' => 2, 'nama_prodi' => "Hukum Keluarga\n(Akhwal Syakhsiyyah)", 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // 2. Dosen (11 dosen)
        $this->command->info('2/13 Importing dosen...');
        $dosen = [
            [1, 'Pembimbing Akademik', '', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [2, '2002057203', "Prof. Dr. A.\nSUKMAWATI ASSAAD, S.AG., M.PD", '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', 'dosen_2_1760900477.png'],
            [3, '2015058001', 'SABARUDDIN, S.HI., M.HI', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [4, '2001027703', 'Dr. FIRMAN MUHAMMAD ARIF, Lc., M.HI', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [5, '0928119101', 'Ulfa, S.Sos.,M.Si', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [6, '2007037002', 'Dr.,Dra. HELMI KAMAL, M.HI', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [7, '2017029003', 'RIZKA AMELIA ARMIN, M.Si', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [8, '2016128401', 'Dr. ARIFUDDIN, S.Pd.I., M.Pd.', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [9, '2030067402', 'Dr. MUHAMMAD TAHMID NUR, M.Ag', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [10, '2031125811', 'Prof. Dr. HAMZAH K., M.HI', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
            [11, '2021108901', 'Syamsuddin, S.H.I., M.H.', '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2', null],
        ];
        
        foreach ($dosen as $d) {
            DB::table('dosen')->insert([
                'id_dosen' => $d[0],
                'nidn_dosen' => $d[1],
                'nama_dosen' => $d[2],
                'password' => $d[3],
                'foto_dosen' => $d[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 3. Mata Kuliah
        $this->command->info('3/13 Importing mata_kuliah...');
        $matakuliah = [
            [1, 'Bahasa Arab', null, null],
            [2, 'Bahasa Indonesia', null, null],
            [3, 'Bahasa Inggris', null, null],
            [4, 'Fiqh Jinayah', null, null],
            [5, 'Fiqh Siyasah', null, null],
            [6, 'Filsafat Hukum', null, null],
            [7, 'Filsafat Hukum Islam', null, null],
            [8, 'Hukum Acara Peradilan Agama', null, null],
            [9, 'Hukum Acara PTUN', 2, null],
            [10, 'Hukum Administrasi Negara', null, null],
            [11, 'Hukum Agraria', null, null],
            [12, 'Hukum Keuangan Negara', 2, null],
            [13, 'Hukum Ketenagakerjaan', 2, null],
            [14, 'Hukum Konstitusi', null, null],
            [15, 'Hukum Perdata Islam di Indonesia', null, null],
            [16, 'Ilmu Negara', null, null],
            [17, 'Kaidah-kaidah Siyasah Syari\'iyyah', 2, null],
            [18, 'Kapita Selekta HTN', null, null],
            [19, 'Kewarganegaraan', null, null],
            [20, 'Metode Penelitian Hukum', null, null],
            [21, 'Pancasila', null, null],
            [22, 'Pengantar Hukum Indonesia', null, null],
            [23, 'Pengantar Ilmu Hukum', null, null],
            [24, 'Perancangan Kontrak', null, null],
            [25, 'Politik Hukum Islam', 3, null],
            [26, 'Sosiologi Hukum', 2, null],
            [27, 'Studi Keislaman', null, null],
        ];
        
        foreach ($matakuliah as $mk) {
            DB::table('mata_kuliah')->insert([
                'id_mk' => $mk[0],
                'nama_mk' => $mk[1],
                'sks' => $mk[2],
                'id_prodi' => $mk[3],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 4. Mahasiswa - Import dari CSV-like array (sample 20 mahasiswa untuk testing)
        $this->command->info('4/13 Importing mahasiswa (sample)...');
        $mahasiswaSample = [
            ['18 0301 0015', 'Salsabila Syamsuddin', 2018, 'A', 15, 0, 24, 126, '0.00', '3.13', 1, 1, 2, 2, 'mhs_18 0301 0015_1760900607.png'],
            ['2103010022', 'ARMY', 2021, 'A', 9, 4, 24, 160, '0.00', '3.00', 1, 1, 2, 10, null],
            ['1903010031', 'ANNI\'', 2019, 'A', 13, 0, 24, 96, '0.00', '3.00', 1, 0, 2, 2, null],
            ['1903010016', 'JAGRATARA', 2019, 'A', 13, 0, 24, 48, '0.00', '2.00', 0, 0, 2, 2, null],
            ['18 0301 0036', 'Ilham Adnan Zaiman', 2018, 'A', 15, 0, 24, 98, '0.00', '3.00', 0, 0, 2, 3, null],
            ['1903010040', 'FADEL MUHAMMAD', 2019, 'A', 13, 4, 24, 155, '0.00', '3.00', 0, 0, 2, 3, null],
            ['1903010052', 'SUKMA AYU', 2019, 'A', 13, 4, 24, 151, '0.00', '3.00', 0, 0, 2, 3, null],
            ['1903010082', 'ASDAR', 2019, 'A', 13, 4, 24, 152, '0.00', '3.00', 0, 0, 2, 6, null],
            ['1903010089', 'REGITA CAHYANI', 2019, 'A', 13, 4, 24, 152, '0.00', '3.00', 0, 0, 2, 6, null],
            ['2003010013', 'MUH AGUS ANUGRAH', 2020, 'A', 11, 0, 24, 154, '0.00', '3.00', 0, 0, 2, 2, null],
            ['2003010032', 'DARMAWAN', 2020, 'A', 11, 0, 24, 24, '0.00', '3.00', 1, 0, 2, 2, null],
            ['2103010004', 'KARINA', 2021, 'A', 9, 4, 24, 150, '0.00', '3.00', 0, 0, 2, 9, null],
            ['2103010009', 'MUHAMMAD THARIQ SYAUQY MUZHAFFAR', 2021, 'A', 9, 23, 24, 142, '0.00', '3.00', 0, 0, 2, 9, null],
            ['2103010011', 'ANDI TENRI BATARI RAHMAN', 2021, 'A', 9, 4, 24, 152, '0.00', '3.00', 0, 0, 2, 10, null],
            ['2103010014', 'MUFIDAH MAHMUD', 2021, 'A', 9, 4, 24, 150, '0.00', '3.00', 0, 0, 2, 10, null],
            ['2103010016', 'DEWI SARFIKA NENGSI', 2021, 'A', 9, 4, 24, 167, '0.00', '3.00', 0, 0, 2, 10, null],
            ['2103010029', 'YUSMILASARI', 2021, 'A', 9, 4, 24, 151, '0.00', '3.00', 0, 0, 2, 10, null],
            ['2103010032', 'MUH. QAYYUM', 2021, 'A', 9, 4, 24, 150, '0.00', '3.00', 0, 0, 2, 9, null],
            ['2103010036', 'Fauziah Rahmi Ihsan', 2021, 'A', 9, 4, 24, 153, '0.00', '3.00', 0, 0, 2, 10, null],
            ['2203010001', 'ADE PUTRI RAHAYU', 2022, 'A', 7, 8, 24, 137, '0.00', '3.00', 0, 0, 2, 11, null],
        ];
        
        foreach ($mahasiswaSample as $mhs) {
            DB::table('mahasiswa')->insert([
                'nim' => $mhs[0],
                'nama_mahasiswa' => $mhs[1],
                'angkatan' => $mhs[2],
                'status_semester' => $mhs[3],
                'semester_berjalan' => $mhs[4],
                'sks_semester' => $mhs[5],
                'batas_sks' => $mhs[6],
                'total_sks' => $mhs[7],
                'ips' => $mhs[8],
                'ipk' => $mhs[9],
                'krs_disetujui' => $mhs[10],
                'krs_notif_dilihat' => $mhs[11],
                'id_prodi' => $mhs[12],
                'id_dosen_pa' => $mhs[13],
                'password' => '$2y$10$3Rzf3.JfM1bzX7FxRbBqF.xyqutFaP3J/96AFaUqG8nJDNyPdXDo2',
                'foto_mahasiswa' => $mhs[14],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 5. Logbook
        $this->command->info('5/13 Importing logbook...');
        $logbooks = [
            [2, '2103010022', 10, 'Dosen', 'Dibaca', '2025-10-15', 'pengusulan topik penelitian', 'tes', 'tes123', '2025-10-15 16:47:13'],
            [9, '2103010022', 10, 'Dosen', 'Dibaca', '2025-10-18', 'pengusulan topik penelitian', 'silahkan menghadap ke saya untuk melakukan konsultasi tentang topik yang akan  kamu angkat', '', '2025-10-18 06:56:16'],
            [10, '1903010031', 2, 'Dosen', 'Belum Dibaca', '2025-10-19', 'pengusulan topik penelitian', 'tes', '', '2025-10-19 18:15:21'],
            [13, '18 0301 0015', 2, 'Dosen', 'Dibaca', '2025-10-20', 'Tindak Lanjut Nilai: Metode Penelitian Hukum', 'Berdasarkan laporan, nilai Anda untuk mata kuliah "Metode Penelitian Hukum" adalah C. Mohon segera diskusikan rencana perbaikannya.', 'menghadap ke saya ketika selesai', '2025-10-20 12:30:54'],
            [14, '18 0301 0015', 2, 'Dosen', 'Dibaca', '2025-10-20', 'Tindak Lanjut Nilai: Hukum Ketenagakerjaan', 'Berdasarkan laporan, nilai Anda untuk mata kuliah "Hukum Ketenagakerjaan" adalah D. Mohon segera diskusikan rencana perbaikannya.', '', '2025-10-20 12:31:08'],
        ];
        
        foreach ($logbooks as $log) {
            DB::table('logbook')->insert([
                'id_log' => $log[0],
                'nim_mahasiswa' => $log[1],
                'id_dosen' => $log[2],
                'pengisi' => $log[3],
                'status_baca' => $log[4],
                'tanggal_bimbingan' => $log[5],
                'topik_bimbingan' => $log[6],
                'isi_bimbingan' => $log[7],
                'tindak_lanjut' => $log[8],
                'created_at' => $log[9],
            ]);
        }
        
        // 6. Dokumen
        $this->command->info('6/13 Importing dokumen...');
        DB::table('dokumen')->insert([
            [
                'id_dokumen' => 1,
                'nim_mahasiswa' => '18 0301 0015',
                'id_dosen' => 2,
                'judul_dokumen' => 'Khs salsabilla',
                'nama_file' => '18 0301 0015_1760960589.pdf',
                'path_file' => 'dokumen/18 0301 0015_1760960589.pdf',
                'tipe_file' => 'pdf',
                'ukuran_file' => 116943,
                'tanggal_unggah' => '2025-10-20 11:43:09',
                'status_baca_dosen' => 'Sudah Dilihat',
            ],
            [
                'id_dokumen' => 2,
                'nim_mahasiswa' => '18 0301 0015',
                'id_dosen' => 2,
                'judul_dokumen' => 'Khs salsabilla',
                'nama_file' => '18 0301 0015_1760962982.pdf',
                'path_file' => 'dokumen/18 0301 0015_1760962982.pdf',
                'tipe_file' => 'pdf',
                'ukuran_file' => 124311,
                'tanggal_unggah' => '2025-10-20 12:23:02',
                'status_baca_dosen' => 'Sudah Dilihat',
            ],
        ]);
        
        // 7. Evaluasi Dosen
        $this->command->info('7/13 Importing evaluasi_dosen...');
        DB::table('evaluasi_dosen')->insert([
            'id_evaluasi_dosen' => 1,
            'nim_mahasiswa' => '2103010022',
            'id_dosen' => 10,
            'periode_evaluasi' => '2025 Ganjil',
            'skor_komunikasi' => 5,
            'skor_membantu' => 5,
            'skor_solusi' => 5,
            'saran_kritik' => 'sangat baik',
            'tanggal_submit' => '2025-10-16 02:10:51',
        ]);
        
        // 8. Evaluasi Softskill
        $this->command->info('8/13 Importing evaluasi_softskill...');
        $evaluasiSoftskill = [
            [1, '18 0301 0015', 2, '2025 Ganjil', 'Disiplin & Komitmen', 5, '0', '2025-10-15 16:51:43'],
            [2, '18 0301 0015', 2, '2025 Ganjil', 'Partisipasi & Keaktifan', 5, '0', '2025-10-15 16:51:43'],
            [3, '18 0301 0015', 2, '2025 Ganjil', 'Etika & Sopan Santun', 5, '0', '2025-10-15 16:51:43'],
            [4, '18 0301 0015', 2, '2025 Ganjil', 'Kepemimpinan & Kerjasama', 5, '0', '2025-10-15 16:51:43'],
        ];
        
        foreach ($evaluasiSoftskill as $es) {
            DB::table('evaluasi_softskill')->insert([
                'id_evaluasi' => $es[0],
                'nim_mahasiswa' => $es[1],
                'id_dosen' => $es[2],
                'periode_evaluasi' => $es[3],
                'kategori' => $es[4],
                'skor' => $es[5],
                'catatan' => $es[6],
                'tanggal_evaluasi' => $es[7],
            ]);
        }
        
        // 9. Pencapaian
        $this->command->info('9/13 Importing pencapaian...');
        $pencapaians = [
            [1, '2003010013', 'Seminar Proposal', 'Selesai', '2025-10-17'],
            [2, '2003010013', 'Penelitian Selesai', 'Belum Selesai', null],
            [3, '2003010013', 'Seminar Hasil', 'Belum Selesai', null],
            [4, '2003010013', 'Ujian Skripsi (Yudisium)', 'Belum Selesai', null],
            [5, '2003010013', 'Publikasi Jurnal', 'Belum Selesai', null],
            [6, '18 0301 0015', 'Seminar Proposal', 'Selesai', '2025-10-19'],
            [7, '18 0301 0015', 'Penelitian Selesai', 'Belum Selesai', null],
            [8, '18 0301 0015', 'Seminar Hasil', 'Belum Selesai', null],
            [9, '18 0301 0015', 'Ujian Skripsi (Yudisium)', 'Belum Selesai', null],
            [10, '18 0301 0015', 'Publikasi Jurnal', 'Belum Selesai', null],
        ];
        
        foreach ($pencapaians as $p) {
            DB::table('pencapaian')->insert([
                'id_pencapaian' => $p[0],
                'nim_mahasiswa' => $p[1],
                'nama_pencapaian' => $p[2],
                'status' => $p[3],
                'tanggal_selesai' => $p[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 10. Riwayat Akademik (sample)
        $this->command->info('10/13 Importing riwayat_akademik...');
        $riwayats = [
            [160, '2103010022', 1, '3.40', 21],
            [161, '2103010022', 2, '3.00', 21],
            [162, '2103010022', 3, '3.25', 21],
            [163, '2103010022', 4, '3.00', 21],
            [164, '2103010022', 5, '2.75', 19],
            [165, '2103010022', 6, '2.99', 21],
            [166, '18 0301 0015', 1, '3.00', 21],
            [167, '18 0301 0015', 2, '3.45', 21],
            [168, '18 0301 0015', 3, '2.75', 21],
            [169, '18 0301 0015', 4, '2.80', 21],
            [170, '18 0301 0015', 5, '3.36', 21],
            [171, '18 0301 0015', 6, '3.40', 21],
        ];
        
        foreach ($riwayats as $r) {
            DB::table('riwayat_akademik')->insert([
                'id_riwayat' => $r[0],
                'nim_mahasiswa' => $r[1],
                'semester' => $r[2],
                'ip_semester' => $r[3],
                'sks_semester' => $r[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 11. Nilai Bermasalah
        $this->command->info('11/13 Importing nilai_bermasalah...');
        $nilaiBermasalahs = [
            [4, '18 0301 0015', 'Bahasa Indonesia', 'C', 3, 'Belum', '2025-10-21 12:01:59'],
            [5, '18 0301 0015', 'Fiqh Siyasah', 'D', 3, 'Belum', '2025-10-21 12:01:59'],
            [6, '18 0301 0015', 'Hukum Keuangan Negara', 'E', 3, 'Belum', '2025-10-21 12:01:59'],
        ];
        
        foreach ($nilaiBermasalahs as $nb) {
            DB::table('nilai_bermasalah')->insert([
                'id_nilai' => $nb[0],
                'nim_mahasiswa' => $nb[1],
                'nama_mk' => $nb[2],
                'nilai_huruf' => $nb[3],
                'semester_diambil' => $nb[4],
                'status_perbaikan' => $nb[5],
                'tanggal_lapor' => $nb[6],
            ]);
        }
        
        // 12. Nilai Mahasiswa
        $this->command->info('12/13 Importing nilai_mahasiswa...');
        DB::table('nilai_mahasiswa')->insert([
            [
                'id_nilai' => 1,
                'nim_mahasiswa' => '18 0301 0015',
                'kode_mk' => 'MANUAL',
                'nama_mk' => 'Bahasa Arab',
                'nilai_huruf' => 'D',
                'semester_diambil' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_nilai' => 2,
                'nim_mahasiswa' => '18 0301 0015',
                'kode_mk' => 'MANUAL',
                'nama_mk' => 'Bahasa Indonesia',
                'nilai_huruf' => 'D',
                'semester_diambil' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        // 13. KRS (empty in original data)
        $this->command->info('13/13 KRS table (empty)');
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->newLine();
        $this->command->info('✅ Full data import completed!');
        $this->command->newLine();
        
        // Show summary
        $this->command->info('=== IMPORT SUMMARY ===');
        $this->command->table(['Table', 'Records'], [
            ['Program Studi', DB::table('program_studi')->count()],
            ['Dosen', DB::table('dosen')->count()],
            ['Mahasiswa', DB::table('mahasiswa')->count()],
            ['Mata Kuliah', DB::table('mata_kuliah')->count()],
            ['Logbook', DB::table('logbook')->count()],
            ['Dokumen', DB::table('dokumen')->count()],
            ['Evaluasi Dosen', DB::table('evaluasi_dosen')->count()],
            ['Evaluasi Softskill', DB::table('evaluasi_softskill')->count()],
            ['Pencapaian', DB::table('pencapaian')->count()],
            ['Riwayat Akademik', DB::table('riwayat_akademik')->count()],
            ['Nilai Bermasalah', DB::table('nilai_bermasalah')->count()],
            ['Nilai Mahasiswa', DB::table('nilai_mahasiswa')->count()],
        ]);
        
        $this->command->newLine();
        $this->command->info('🔑 Login Credentials (password yang sama dari database lama):');
        $this->command->line('');
        $this->command->line('DOSEN:');
        $this->command->line('  Username: 2002057203');
        $this->command->line('  Password: [password database lama]');
        $this->command->line('');
        $this->command->line('MAHASISWA:');
        $this->command->line('  Username: 18 0301 0015');
        $this->command->line('  Password: [password database lama]');
        $this->command->newLine();
        $this->command->info('🚀 Ready! Run: php artisan serve');
    }
}
