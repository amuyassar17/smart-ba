# SMART-BA Laravel

**Smart Bimbingan Akademik** - Sistem bimbingan akademik digital untuk UIN Palopo yang telah dimigrasikan ke Laravel 12.

---

## 🚀 Quick Start

### 1. Clone & Install
```bash
cd /Volumes/Ahmad/Project/Pribadi/smartba-laravel
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env  # Jika belum ada
php artisan key:generate
```

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartba_laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Setup Database
```bash
# Create database (jika belum ada)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS smartba_laravel"

# Run migrations dan seed test data
php artisan migrate:fresh --seed
```

### 4. Setup Storage
```bash
php artisan storage:link
```

### 5. Run Application
```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 🔑 Test Login Credentials

### Dosen
- **Username**: `2002057203`
- **Password**: `password123`
- **Dashboard**: http://localhost:8000/dosen/dashboard

### Mahasiswa
- **Username**: `18 0301 0015`
- **Password**: `password123`
- **Dashboard**: http://localhost:8000/mahasiswa/dashboard

---

## 📁 Struktur Project

```
smartba-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Login/Logout
│   │   │   ├── MahasiswaController.php # Dashboard Mahasiswa
│   │   │   ├── DosenController.php     # Dashboard Dosen
│   │   │   └── HomeController.php      # Landing page
│   │   └── Middleware/
│   │       ├── CheckMahasiswa.php       # Auth guard mahasiswa
│   │       └── CheckDosen.php           # Auth guard dosen
│   ├── Models/
│   │   ├── Mahasiswa.php (+ 12 models lainnya)
│   │   ├── Dosen.php
│   │   ├── Logbook.php
│   │   └── ...
│   └── Console/Commands/
│       └── ImportDataLama.php           # Import dari DB lama
├── database/
│   ├── migrations/                      # 13 migrations
│   └── seeders/
│       └── DatabaseSeeder.php           # Test data
├── resources/
│   └── views/
│       ├── layouts/                     # App layout
│       ├── auth/                        # Login
│       ├── mahasiswa/                   # Dashboard mahasiswa
│       └── dosen/                       # Dashboard dosen
└── routes/
    └── web.php                          # 16 routes
```

---

## ✨ Fitur Utama

### Dashboard Mahasiswa
✅ Lihat IPK & Total SKS  
✅ Riwayat Logbook Bimbingan  
✅ Tambah Catatan Bimbingan  
✅ Upload Dokumen  
✅ Tracking Pencapaian (Sempro, Sidang, dll)  
✅ Grafik Perkembangan IP  
✅ Evaluasi Dosen PA  

### Dashboard Dosen
✅ Daftar Mahasiswa Bimbingan  
✅ Filter per Angkatan  
✅ Search Mahasiswa  
✅ Detail Mahasiswa  
✅ Approve/Reject KRS  
✅ Toggle Status Aktif/Non-Aktif  
✅ Notifikasi Logbook & Dokumen Baru  
✅ Peringatan Mahasiswa Bermasalah (IPK < 2.75)  

---

## 🔧 Artisan Commands

### Import Data Dari Database Lama
```bash
php artisan import:data-lama
```
**Note**: Database lama (`db_pa_akademi`) harus exists di server MySQL yang sama.

### Reset & Seed Ulang
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📊 Database Schema

### 13 Tabel Utama:
1. **program_studi** - Program studi
2. **dosen** - Data dosen & auth
3. **mahasiswa** - Data mahasiswa & auth
4. **mata_kuliah** - Mata kuliah
5. **logbook** - Riwayat bimbingan
6. **dokumen** - Upload dokumen mahasiswa
7. **evaluasi_dosen** - Evaluasi mahasiswa ke dosen
8. **evaluasi_softskill** - Evaluasi dosen ke mahasiswa
9. **pencapaian** - Milestone akademik
10. **riwayat_akademik** - IP per semester
11. **nilai_bermasalah** - Nilai C/D/E yang perlu diperbaiki
12. **nilai_mahasiswa** - Nilai mahasiswa
13. **krs** - Kartu Rencana Studi

---

## 🔐 Authentication

Menggunakan **multi-guard authentication**:
- Guard `mahasiswa` untuk login mahasiswa (NIM)
- Guard `dosen` untuk login dosen (NIDN)

Middleware:
- `mahasiswa` - Proteksi routes mahasiswa
- `dosen` - Proteksi routes dosen

---

## 🎨 Frontend Stack

- **Bootstrap 5.3.2** - UI Framework
- **Bootstrap Icons** - Icons
- **Google Fonts** - Montserrat & Lato
- **Chart.js** - Grafik IP (ready, belum diimplementasikan)

---

## 📋 TODO / Fitur Tambahan

- [ ] Upload foto profil (mahasiswa & dosen)
- [ ] PDF generation untuk laporan
- [ ] Realtime notifications
- [ ] Export data ke Excel
- [ ] Email notifications
- [ ] Chart.js integration untuk grafik
- [ ] Copy assets dari project lama

---

## 🔄 Migrasi dari PHP Native

Project ini adalah hasil migrasi dari sistem PHP native ke Laravel 12.

### Perubahan Utama:
- ✅ Native PHP → Laravel Framework
- ✅ Raw SQL → Eloquent ORM
- ✅ Manual routing → Laravel Routes
- ✅ Mixed auth logic → Multi-guard Auth
- ✅ Plain PHP views → Blade Templates
- ✅ No structure → MVC Pattern

---

## 🐛 Troubleshooting

### Database connection error
```bash
# Cek MySQL service
brew services list | grep mysql

# Restart MySQL
brew services restart mysql

# Cek database exists
mysql -u root -e "SHOW DATABASES LIKE 'smartba_laravel'"
```

### Storage link error
```bash
php artisan storage:link
```

### Migration error
```bash
# Fresh install
php artisan migrate:fresh --seed
```

---

## 📝 License

Internal project untuk UIN Palopo - Fakultas Syariah dan Hukum.

---

## 👨‍💻 Developer Notes

### Stack:
- Laravel 12
- PHP 8.4
- MySQL 8.0
- Bootstrap 5

### Development:
```bash
# Watch for changes
php artisan serve --host=0.0.0.0 --port=8000

# Tinker (Laravel REPL)
php artisan tinker
```

---

**🎓 SMART-BA** - Smart & Green Campus Initiative  
Universitas Islam Negeri Kota Palopo
