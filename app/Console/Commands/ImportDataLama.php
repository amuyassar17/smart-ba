<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDataLama extends Command
{
    protected $signature = 'import:data-lama';
    protected $description = 'Import data dari database lama (db_pa_akademi) ke database Laravel';

    public function handle()
    {
        $this->info('=== IMPORT DATA DARI DATABASE LAMA ===');
        $this->newLine();

        $oldDB = 'db_pa_akademi';
        
        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Import using raw SQL with proper handling
            $this->info('1. Importing program_studi...');
            DB::insert("INSERT INTO program_studi (id_prodi, nama_prodi, created_at, updated_at)
                       SELECT id_prodi, nama_prodi, NOW(), NOW() FROM $oldDB.program_studi");
            $this->info('   ✓ ' . DB::table('program_studi')->count() . ' records');
            
            $this->info('2. Importing dosen...');
            DB::insert("INSERT INTO dosen (id_dosen, nidn_dosen, nama_dosen, password, foto_dosen, created_at, updated_at)
                       SELECT id_dosen, nidn_dosen, nama_dosen, password, foto_dosen, NOW(), NOW() FROM $oldDB.dosen");
            $this->info('   ✓ ' . DB::table('dosen')->count() . ' records');
            
            $this->info('3. Importing mata_kuliah...');
            DB::insert("INSERT INTO mata_kuliah (id_mk, nama_mk, sks, id_prodi, created_at, updated_at)
                       SELECT id_mk, nama_mk, sks, id_prodi, NOW(), NOW() FROM $oldDB.mata_kuliah");
            $this->info('   ✓ ' . DB::table('mata_kuliah')->count() . ' records');
            
            $this->info('4. Importing mahasiswa...');
            DB::insert("INSERT INTO mahasiswa (
                           nim, nama_mahasiswa, angkatan, status_semester, semester_berjalan, 
                           sks_semester, batas_sks, total_sks, ips, ipk, 
                           krs_disetujui, krs_notif_dilihat, id_prodi, id_dosen_pa, 
                           password, foto_mahasiswa, created_at, updated_at
                       )
                       SELECT 
                           nim, nama_mahasiswa, angkatan, status_semester, semester_berjalan,
                           sks_semester, batas_sks, total_sks, ips, ipk,
                           krs_disetujui, krs_notif_dilihat, id_prodi, id_dosen_pa,
                           password, foto_mahasiswa, NOW(), NOW()
                       FROM $oldDB.mahasiswa
                       WHERE nim != 'NIM'");
            $this->info('   ✓ ' . DB::table('mahasiswa')->count() . ' records');
            
            $this->info('5. Importing logbook...');
            DB::insert("INSERT INTO logbook (
                           id_log, nim_mahasiswa, id_dosen, pengisi, status_baca,
                           tanggal_bimbingan, topik_bimbingan, isi_bimbingan, tindak_lanjut, created_at
                       )
                       SELECT 
                           id_log, nim_mahasiswa, id_dosen, pengisi, status_baca,
                           tanggal_bimbingan, topik_bimbingan, isi_bimbingan, tindak_lanjut, created_at
                       FROM $oldDB.logbook");
            $this->info('   ✓ ' . DB::table('logbook')->count() . ' records');
            
            $this->info('6. Importing dokumen...');
            DB::insert("INSERT INTO dokumen (
                           id_dokumen, nim_mahasiswa, id_dosen, judul_dokumen, nama_file,
                           path_file, tipe_file, ukuran_file, tanggal_unggah, status_baca_dosen
                       )
                       SELECT 
                           id_dokumen, nim_mahasiswa, id_dosen, judul_dokumen, nama_file,
                           path_file, tipe_file, ukuran_file, tanggal_unggah, status_baca_dosen
                       FROM $oldDB.dokumen");
            $this->info('   ✓ ' . DB::table('dokumen')->count() . ' records');
            
            $this->info('7. Importing evaluasi_dosen...');
            DB::insert("INSERT INTO evaluasi_dosen 
                       SELECT * FROM $oldDB.evaluasi_dosen");
            $this->info('   ✓ ' . DB::table('evaluasi_dosen')->count() . ' records');
            
            $this->info('8. Importing evaluasi_softskill...');
            DB::insert("INSERT INTO evaluasi_softskill 
                       SELECT * FROM $oldDB.evaluasi_softskill");
            $this->info('   ✓ ' . DB::table('evaluasi_softskill')->count() . ' records');
            
            $this->info('9. Importing pencapaian...');
            DB::insert("INSERT INTO pencapaian (id_pencapaian, nim_mahasiswa, nama_pencapaian, status, tanggal_selesai, created_at, updated_at) 
                       SELECT id_pencapaian, nim_mahasiswa, nama_pencapaian, status, tanggal_selesai, NOW(), NOW() 
                       FROM $oldDB.pencapaian");
            $this->info('   ✓ ' . DB::table('pencapaian')->count() . ' records');
            
            $this->info('10. Importing riwayat_akademik...');
            DB::insert("INSERT INTO riwayat_akademik (id_riwayat, nim_mahasiswa, semester, ip_semester, sks_semester, created_at, updated_at) 
                       SELECT id_riwayat, nim_mahasiswa, semester, ip_semester, sks_semester, NOW(), NOW() 
                       FROM $oldDB.riwayat_akademik");
            $this->info('   ✓ ' . DB::table('riwayat_akademik')->count() . ' records');
            
            $this->info('11. Importing nilai_bermasalah...');
            DB::insert("INSERT INTO nilai_bermasalah 
                       SELECT * FROM $oldDB.nilai_bermasalah");
            $this->info('   ✓ ' . DB::table('nilai_bermasalah')->count() . ' records');
            
            $this->info('12. Importing nilai_mahasiswa...');
            DB::insert("INSERT INTO nilai_mahasiswa (id_nilai, nim_mahasiswa, kode_mk, nama_mk, nilai_huruf, semester_diambil, created_at, updated_at) 
                       SELECT id_nilai, nim_mahasiswa, kode_mk, nama_mk, nilai_huruf, semester_diambil, NOW(), NOW() 
                       FROM $oldDB.nilai_mahasiswa");
            $this->info('   ✓ ' . DB::table('nilai_mahasiswa')->count() . ' records');
            
            $this->info('13. Importing krs...');
            DB::insert("INSERT INTO krs (id_krs, nim_mahasiswa, id_mk, semester_diambil, nilai_huruf, sudah_dinilai, created_at, updated_at) 
                       SELECT id_krs, nim_mahasiswa, id_mk, semester_diambil, nilai_huruf, sudah_dinilai, NOW(), NOW() 
                       FROM $oldDB.krs");
            $this->info('   ✓ ' . DB::table('krs')->count() . ' records');
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->newLine();
            $this->info('✅ ALL DATA IMPORTED SUCCESSFULLY!');
            $this->newLine();
            
            // Show summary
            $this->info('=== IMPORT SUMMARY ===');
            $this->table(['Table', 'Records'], [
                ['Program Studi', DB::table('program_studi')->count()],
                ['Dosen', DB::table('dosen')->count()],
                ['Mahasiswa', DB::table('mahasiswa')->count()],
                ['Mata Kuliah', DB::table('mata_kuliah')->count()],
                ['Logbook', DB::table('logbook')->count()],
                ['Dokumen', DB::table('dokumen')->count()],
                ['Pencapaian', DB::table('pencapaian')->count()],
                ['Riwayat Akademik', DB::table('riwayat_akademik')->count()],
            ]);
            
            $this->newLine();
            $this->info('🔑 Sample Login Credentials:');
            $this->line('');
            $this->line('DOSEN:');
            $this->line('  Username: 2002057203');
            $this->line('  Password: [gunakan password default database lama]');
            $this->line('');
            $this->line('MAHASISWA:');
            $this->line('  Username: 18 0301 0015');
            $this->line('  Password: [gunakan password default database lama]');
            $this->newLine();
            
            $this->info('🚀 Ready to test! Run: php artisan serve');
            $this->info('   Then open: http://localhost:8000');
            
        } catch (\Exception $e) {
            $this->error('ERROR: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}
