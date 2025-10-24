# Setup Logo dan Favicon UIN Palopo

## Langkah-langkah:

### 1. Simpan Logo ke Folder Public

Simpan logo UIN Palopo yang sudah dikirim dengan nama:
- **`public/images/logo-uin-palopo.png`** (untuk navbar)

### 2. Buat Favicon dalam Berbagai Ukuran

Gunakan logo untuk membuat favicon dengan ukuran berbeda:

#### Menggunakan Online Tools:
- Website: https://realfavicongenerator.net/ atau https://favicon.io/
- Upload logo UIN Palopo
- Generate semua ukuran favicon

#### Atau Manual dengan Tools:
```bash
# Install ImageMagick (jika belum ada)
# macOS:
brew install imagemagick

# Resize logo ke berbagai ukuran:
convert logo-uin-palopo.png -resize 32x32 favicon-32x32.png
convert logo-uin-palopo.png -resize 16x16 favicon-16x16.png
convert logo-uin-palopo.png -resize 180x180 apple-touch-icon.png
convert logo-uin-palopo.png -resize 192x192 android-chrome-192x192.png
convert logo-uin-palopo.png -resize 512x512 android-chrome-512x512.png

# Create ICO file
convert logo-uin-palopo.png -define icon:auto-resize=64,48,32,16 favicon.ico
```

### 3. Struktur File yang Dibutuhkan

```
smartba-laravel/
├── public/
│   ├── images/
│   │   ├── logo-uin-palopo.png     (Full size logo untuk navbar)
│   │   ├── favicon-32x32.png       (Favicon 32x32)
│   │   ├── favicon-16x16.png       (Favicon 16x16)
│   │   └── apple-touch-icon.png    (iOS icon 180x180)
│   └── favicon.ico                 (Root favicon)
```

### 4. Copy Files ke Lokasi

Setelah generate semua file, copy ke folder:

```bash
# Copy logo
cp /path/to/logo-uin-palopo.png public/images/

# Copy favicons
cp /path/to/favicon-32x32.png public/images/
cp /path/to/favicon-16x16.png public/images/
cp /path/to/apple-touch-icon.png public/images/
cp /path/to/favicon.ico public/
```

### 5. Verifikasi

Setelah semua file di tempatkan, refresh browser dan cek:
- ✅ Logo muncul di navbar
- ✅ Favicon muncul di browser tab
- ✅ Icon muncul saat save to home screen (mobile)

### 6. Clear Cache Laravel

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

## Quick Setup (Manual)

Jika tidak ada ImageMagick, bisa manual:

1. **Simpan logo asli** ke `public/images/logo-uin-palopo.png`

2. **Buka logo di image editor** (Photoshop, GIMP, Preview, dll)

3. **Export/Save As** dalam ukuran:
   - 32x32 px → `public/images/favicon-32x32.png`
   - 16x16 px → `public/images/favicon-16x16.png`
   - 180x180 px → `public/images/apple-touch-icon.png`

4. **Convert to ICO** menggunakan online converter:
   - Upload logo ke https://convertio.co/png-ico/
   - Download `favicon.ico`
   - Simpan ke `public/favicon.ico`

## Hasil Akhir

Setelah setup selesai:
- ✅ Navbar akan menampilkan logo UIN Palopo
- ✅ Browser tab akan menampilkan favicon
- ✅ Bookmark akan menampilkan icon
- ✅ Mobile home screen akan menampilkan icon

## Testing

Test di berbagai browser:
- Chrome
- Firefox
- Safari
- Edge
- Mobile browsers

Hard refresh (Ctrl+Shift+R / Cmd+Shift+R) jika favicon tidak muncul.
