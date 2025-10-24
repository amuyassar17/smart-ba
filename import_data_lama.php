<?php

echo "=== IMPORT DATA DARI DATABASE LAMA KE LARAVEL ===\n\n";

$oldDB = 'db_pa_akademi';
$newDB = 'smartba_laravel';
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect ke database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL\n\n";
    
    // 1. Import Program Studi
    echo "Importing program_studi...\n";
    $pdo->exec("INSERT INTO $newDB.program_studi (id_prodi, nama_prodi, created_at, updated_at)
                SELECT id_prodi, nama_prodi, NOW(), NOW() 
                FROM $oldDB.program_studi
                ON DUPLICATE KEY UPDATE nama_prodi = VALUES(nama_prodi)");
    echo "✓ program_studi imported\n\n";
    
    // 2. Import Dosen
    echo "Importing dosen...\n";
    $pdo->exec("INSERT INTO $newDB.dosen (id_dosen, nidn_dosen, nama_dosen, password, foto_dosen, created_at, updated_at)
                SELECT id_dosen, nidn_dosen, nama_dosen, password, foto_dosen, NOW(), NOW() 
                FROM $oldDB.dosen
                ON DUPLICATE KEY UPDATE 
                    nidn_dosen = VALUES(nidn_dosen),
                    nama_dosen = VALUES(nama_dosen),
                    password = VALUES(password),
                    foto_dosen = VALUES(foto_dosen)");
    echo "✓ dosen imported\n\n";
    
    // 3. Import Mata Kuliah
    echo "Importing mata_kuliah...\n";
    $pdo->exec("INSERT INTO $newDB.mata_kuliah (id_mk, nama_mk, sks, id_prodi, created_at, updated_at)
                SELECT id_mk, nama_mk, sks, id_prodi, NOW(), NOW() 
                FROM $oldDB.mata_kuliah
                ON DUPLICATE KEY UPDATE 
                    nama_mk = VALUES(nama_mk),
                    sks = VALUES(sks)");
    echo "✓ mata_kuliah imported\n\n";
    
    // 4. Import Mahasiswa
    echo "Importing mahasiswa...\n";
    $pdo->exec("INSERT INTO $newDB.mahasiswa (
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
                WHERE nim != 'NIM'
                ON DUPLICATE KEY UPDATE 
                    nama_mahasiswa = VALUES(nama_mahasiswa),
                    ipk = VALUES(ipk),
                    status_semester = VALUES(status_semester)");
    echo "✓ mahasiswa imported\n\n";
    
    // 5. Import Logbook
    echo "Importing logbook...\n";
    $pdo->exec("INSERT INTO $newDB.logbook (
                    id_log, nim_mahasiswa, id_dosen, pengisi, status_baca,
                    tanggal_bimbingan, topik_bimbingan, isi_bimbingan, tindak_lanjut, created_at
                )
                SELECT 
                    id_log, nim_mahasiswa, id_dosen, pengisi, status_baca,
                    tanggal_bimbingan, topik_bimbingan, isi_bimbingan, tindak_lanjut, created_at
                FROM $oldDB.logbook
                ON DUPLICATE KEY UPDATE 
                    status_baca = VALUES(status_baca)");
    echo "✓ logbook imported\n\n";
    
    // 6. Import Dokumen
    echo "Importing dokumen...\n";
    $pdo->exec("INSERT INTO $newDB.dokumen (
                    id_dokumen, nim_mahasiswa, id_dosen, judul_dokumen, nama_file,
                    path_file, tipe_file, ukuran_file, tanggal_unggah, status_baca_dosen
                )
                SELECT 
                    id_dokumen, nim_mahasiswa, id_dosen, judul_dokumen, nama_file,
                    path_file, tipe_file, ukuran_file, tanggal_unggah, status_baca_dosen
                FROM $oldDB.dokumen
                ON DUPLICATE KEY UPDATE 
                    status_baca_dosen = VALUES(status_baca_dosen)");
    echo "✓ dokumen imported\n\n";
    
    // 7. Import Evaluasi Dosen
    echo "Importing evaluasi_dosen...\n";
    $pdo->exec("INSERT INTO $newDB.evaluasi_dosen (
                    id_evaluasi_dosen, nim_mahasiswa, id_dosen, periode_evaluasi,
                    skor_komunikasi, skor_membantu, skor_solusi, saran_kritik, tanggal_submit
                )
                SELECT 
                    id_evaluasi_dosen, nim_mahasiswa, id_dosen, periode_evaluasi,
                    skor_komunikasi, skor_membantu, skor_solusi, saran_kritik, tanggal_submit
                FROM $oldDB.evaluasi_dosen
                ON DUPLICATE KEY UPDATE 
                    skor_komunikasi = VALUES(skor_komunikasi)");
    echo "✓ evaluasi_dosen imported\n\n";
    
    // 8. Import Evaluasi Softskill
    echo "Importing evaluasi_softskill...\n";
    $pdo->exec("INSERT INTO $newDB.evaluasi_softskill (
                    id_evaluasi, nim_mahasiswa, id_dosen, periode_evaluasi,
                    kategori, skor, catatan, tanggal_evaluasi
                )
                SELECT 
                    id_evaluasi, nim_mahasiswa, id_dosen, periode_evaluasi,
                    kategori, skor, catatan, tanggal_evaluasi
                FROM $oldDB.evaluasi_softskill
                ON DUPLICATE KEY UPDATE 
                    skor = VALUES(skor)");
    echo "✓ evaluasi_softskill imported\n\n";
    
    // 9. Import Pencapaian
    echo "Importing pencapaian...\n";
    $pdo->exec("INSERT INTO $newDB.pencapaian (
                    id_pencapaian, nim_mahasiswa, nama_pencapaian, status, tanggal_selesai, created_at, updated_at
                )
                SELECT 
                    id_pencapaian, nim_mahasiswa, nama_pencapaian, status, tanggal_selesai, NOW(), NOW()
                FROM $oldDB.pencapaian
                ON DUPLICATE KEY UPDATE 
                    status = VALUES(status),
                    tanggal_selesai = VALUES(tanggal_selesai)");
    echo "✓ pencapaian imported\n\n";
    
    // 10. Import Riwayat Akademik
    echo "Importing riwayat_akademik...\n";
    $pdo->exec("INSERT INTO $newDB.riwayat_akademik (
                    id_riwayat, nim_mahasiswa, semester, ip_semester, sks_semester, created_at, updated_at
                )
                SELECT 
                    id_riwayat, nim_mahasiswa, semester, ip_semester, sks_semester, NOW(), NOW()
                FROM $oldDB.riwayat_akademik
                ON DUPLICATE KEY UPDATE 
                    ip_semester = VALUES(ip_semester)");
    echo "✓ riwayat_akademik imported\n\n";
    
    // 11. Import Nilai Bermasalah
    echo "Importing nilai_bermasalah...\n";
    $pdo->exec("INSERT INTO $newDB.nilai_bermasalah (
                    id_nilai, nim_mahasiswa, nama_mk, nilai_huruf, semester_diambil, status_perbaikan, tanggal_lapor
                )
                SELECT 
                    id_nilai, nim_mahasiswa, nama_mk, nilai_huruf, semester_diambil, status_perbaikan, tanggal_lapor
                FROM $oldDB.nilai_bermasalah
                ON DUPLICATE KEY UPDATE 
                    status_perbaikan = VALUES(status_perbaikan)");
    echo "✓ nilai_bermasalah imported\n\n";
    
    // 12. Import Nilai Mahasiswa
    echo "Importing nilai_mahasiswa...\n";
    $pdo->exec("INSERT INTO $newDB.nilai_mahasiswa (
                    id_nilai, nim_mahasiswa, kode_mk, nama_mk, nilai_huruf, semester_diambil, created_at, updated_at
                )
                SELECT 
                    id_nilai, nim_mahasiswa, kode_mk, nama_mk, nilai_huruf, semester_diambil, NOW(), NOW()
                FROM $oldDB.nilai_mahasiswa
                ON DUPLICATE KEY UPDATE 
                    nilai_huruf = VALUES(nilai_huruf)");
    echo "✓ nilai_mahasiswa imported\n\n";
    
    // 13. Import KRS
    echo "Importing krs...\n";
    $pdo->exec("INSERT INTO $newDB.krs (
                    id_krs, nim_mahasiswa, id_mk, semester_diambil, nilai_huruf, sudah_dinilai, created_at, updated_at
                )
                SELECT 
                    id_krs, nim_mahasiswa, id_mk, semester_diambil, nilai_huruf, sudah_dinilai, NOW(), NOW()
                FROM $oldDB.krs
                ON DUPLICATE KEY UPDATE 
                    nilai_huruf = VALUES(nilai_huruf)");
    echo "✓ krs imported\n\n";
    
    // Summary
    echo "\n=== IMPORT SUMMARY ===\n";
    $stmt = $pdo->query("SELECT 
        (SELECT COUNT(*) FROM $newDB.program_studi) as program_studi,
        (SELECT COUNT(*) FROM $newDB.dosen) as dosen,
        (SELECT COUNT(*) FROM $newDB.mahasiswa) as mahasiswa,
        (SELECT COUNT(*) FROM $newDB.mata_kuliah) as mata_kuliah,
        (SELECT COUNT(*) FROM $newDB.logbook) as logbook,
        (SELECT COUNT(*) FROM $newDB.dokumen) as dokumen,
        (SELECT COUNT(*) FROM $newDB.evaluasi_dosen) as evaluasi_dosen,
        (SELECT COUNT(*) FROM $newDB.evaluasi_softskill) as evaluasi_softskill,
        (SELECT COUNT(*) FROM $newDB.pencapaian) as pencapaian,
        (SELECT COUNT(*) FROM $newDB.riwayat_akademik) as riwayat_akademik,
        (SELECT COUNT(*) FROM $newDB.nilai_bermasalah) as nilai_bermasalah,
        (SELECT COUNT(*) FROM $newDB.nilai_mahasiswa) as nilai_mahasiswa,
        (SELECT COUNT(*) FROM $newDB.krs) as krs
    ");
    
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);
    
    foreach ($summary as $table => $count) {
        echo str_pad($table, 25) . ": $count records\n";
    }
    
    echo "\n✅ ALL DATA IMPORTED SUCCESSFULLY!\n\n";
    
    // Show sample login credentials
    echo "=== SAMPLE LOGIN CREDENTIALS ===\n";
    echo "\nDOSEN:\n";
    $dosenSample = $pdo->query("SELECT nidn_dosen, nama_dosen FROM $newDB.dosen WHERE nidn_dosen IS NOT NULL LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dosenSample as $d) {
        echo "  Username: {$d['nidn_dosen']}\n";
        echo "  Name: {$d['nama_dosen']}\n";
        echo "  Password: [default dari database lama]\n\n";
    }
    
    echo "MAHASISWA:\n";
    $mhsSample = $pdo->query("SELECT nim, nama_mahasiswa FROM $newDB.mahasiswa WHERE nim != 'NIM' LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($mhsSample as $m) {
        echo "  Username: {$m['nim']}\n";
        echo "  Name: {$m['nama_mahasiswa']}\n";
        echo "  Password: [default dari database lama]\n\n";
    }
    
    echo "\n🚀 Ready to test! Run: php artisan serve\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
