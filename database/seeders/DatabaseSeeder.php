<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding test data...');
        
        // 1. Program Studi
        DB::table('program_studi')->insert([
            ['id_prodi' => 1, 'nama_prodi' => 'Hukum Tata Negara', 'created_at' => now(), 'updated_at' => now()],
            ['id_prodi' => 2, 'nama_prodi' => 'Hukum Keluarga (Akhwal Syakhsiyyah)', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // 2. Dosen (password default: password123)
        DB::table('dosen')->insert([
            [
                'id_dosen' => 1,
                'nidn_dosen' => '2002057203',
                'nama_dosen' => 'Prof. Dr. A. SUKMAWATI ASSAAD, S.AG., M.PD',
                'password' => Hash::make('password123'),
                'foto_dosen' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => 2,
                'nidn_dosen' => '2015058001',
                'nama_dosen' => 'SABARUDDIN, S.HI., M.HI',
                'password' => Hash::make('password123'),
                'foto_dosen' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        // 3. Mahasiswa (password default: password123)
        DB::table('mahasiswa')->insert([
            [
                'nim' => '18 0301 0015',
                'nama_mahasiswa' => 'Salsabila Syamsuddin',
                'angkatan' => 2018,
                'status_semester' => 'A',
                'semester_berjalan' => 15,
                'sks_semester' => 0,
                'batas_sks' => 24,
                'total_sks' => 126,
                'ips' => 3.13,
                'ipk' => 3.13,
                'krs_disetujui' => true,
                'krs_notif_dilihat' => false,
                'id_prodi' => 2,
                'id_dosen_pa' => 1,
                'password' => Hash::make('password123'),
                'foto_mahasiswa' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2103010022',
                'nama_mahasiswa' => 'ARMY',
                'angkatan' => 2021,
                'status_semester' => 'A',
                'semester_berjalan' => 9,
                'sks_semester' => 4,
                'batas_sks' => 24,
                'total_sks' => 160,
                'ips' => 3.00,
                'ipk' => 3.00,
                'krs_disetujui' => true,
                'krs_notif_dilihat' => false,
                'id_prodi' => 2,
                'id_dosen_pa' => 1,
                'password' => Hash::make('password123'),
                'foto_mahasiswa' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        // 4. Logbook
        DB::table('logbook')->insert([
            [
                'nim_mahasiswa' => '18 0301 0015',
                'id_dosen' => 1,
                'pengisi' => 'Dosen',
                'status_baca' => 'Belum Dibaca',
                'tanggal_bimbingan' => '2025-10-20',
                'topik_bimbingan' => 'Konsultasi Judul Skripsi',
                'isi_bimbingan' => 'Mahasiswa mengajukan 3 judul skripsi untuk dipertimbangkan.',
                'tindak_lanjut' => 'Pilih 1 judul dan buat proposal',
                'created_at' => now(),
            ],
            [
                'nim_mahasiswa' => '2103010022',
                'id_dosen' => 1,
                'pengisi' => 'Mahasiswa',
                'status_baca' => 'Belum Dibaca',
                'tanggal_bimbingan' => '2025-10-21',
                'topik_bimbingan' => 'Diskusi Metodologi Penelitian',
                'isi_bimbingan' => 'Membahas metode penelitian yang sesuai untuk topik yang dipilih.',
                'tindak_lanjut' => null,
                'created_at' => now(),
            ],
        ]);
        
        // 5. Pencapaian
        $pencapaianList = [
            'Seminar Proposal',
            'Ujian Komperehensif',
            'Seminar Hasil',
            'Ujian Skripsi (Yudisium)',
            'Publikasi Jurnal'
        ];
        
        foreach (['18 0301 0015', '2103010022'] as $nim) {
            foreach ($pencapaianList as $index => $pencapaian) {
                DB::table('pencapaian')->insert([
                    'nim_mahasiswa' => $nim,
                    'nama_pencapaian' => $pencapaian,
                    'status' => $index < 2 ? 'Selesai' : 'Belum Selesai',
                    'tanggal_selesai' => $index < 2 ? now()->subMonths(3 - $index) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // 6. Riwayat Akademik
        for ($sem = 1; $sem <= 6; $sem++) {
            DB::table('riwayat_akademik')->insert([
                'nim_mahasiswa' => '18 0301 0015',
                'semester' => $sem,
                'ip_semester' => rand(280, 380) / 100,
                'sks_semester' => 21,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Test data seeded successfully!');
        $this->command->newLine();
        $this->command->info('🔑 Login Credentials (password: password123):');
        $this->command->line('');
        $this->command->line('DOSEN:');
        $this->command->line('  Username: 2002057203');
        $this->command->line('  Password: password123');
        $this->command->line('');
        $this->command->line('MAHASISWA:');
        $this->command->line('  Username: 18 0301 0015');
        $this->command->line('  Password: password123');
        $this->command->newLine();
        $this->command->info('🚀 Run: php artisan serve');
        $this->command->info('   Open: http://localhost:8000');
    }
}
