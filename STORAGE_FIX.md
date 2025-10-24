# Storage URL Fix - 404 Error Solution

## Problem

Error: **The requested resource /storage/dokumen/ was not found on this server.**

File exists in `storage/app/public/dokumen/` but cannot be accessed via web.

---

## Root Cause

Using `Storage::url()` instead of `asset()` for generating public URLs.

**Wrong:**
```blade
Storage::url('dokumen/' . $file)
→ Generates: /storage/dokumen/file.pdf
```

**Correct:**
```blade
asset('storage/dokumen/' . $file)
→ Generates: http://localhost/storage/dokumen/file.pdf
```

---

## Solution

### **1. Check Symlink Exists**

```bash
cd /Volumes/Ahmad/Project/Pribadi/smartba-laravel
ls -la public/ | grep storage
```

**Expected output:**
```
lrwxr-xr-x  storage -> /path/to/storage/app/public
```

If not exists:
```bash
php artisan storage:link
```

---

### **2. Check Files Exist**

```bash
ls -la storage/app/public/dokumen/
```

**Expected:**
```
-rwxrwxr-x  1903010040_1761322806.pdf
-rwxrwxr-x  other_files.pdf
```

---

### **3. Fix Permissions**

```bash
chmod -R 775 storage/app/public/dokumen
```

---

### **4. Fix Code - Use asset() Instead of Storage::url()**

**File: `resources/views/dosen/detail-mahasiswa.blade.php`**

**Before (Wrong):**
```blade
onclick="viewPDF('{{ Storage::url('dokumen/' . $doc->file_dokumen) }}', ...)"
```

**After (Correct):**
```blade
onclick="viewPDF('{{ asset('storage/dokumen/' . $doc->file_dokumen) }}', ...)"
```

---

### **5. Clear Cache**

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

---

## Explanation

### **Storage Structure:**

```
Laravel Project/
├── public/
│   ├── index.php
│   └── storage → symlink to storage/app/public
│
└── storage/
    └── app/
        └── public/
            └── dokumen/
                └── file.pdf
```

### **URL Generation:**

**Method 1: asset() - For Symlinked Public Files**
```php
asset('storage/dokumen/file.pdf')
→ http://localhost/storage/dokumen/file.pdf
```
✅ **Use this for files in storage/app/public/**

**Method 2: Storage::url() - For Configured Disks**
```php
Storage::url('dokumen/file.pdf')
→ /storage/dokumen/file.pdf (relative URL)
```
❌ **Don't use directly in onclick attributes**

**Method 3: Storage::disk('public')->url()**
```php
Storage::disk('public')->url('dokumen/file.pdf')
→ /storage/dokumen/file.pdf
```
❌ **Same issue as Storage::url()**

---

## Testing

### **Test in Browser:**

1. Check symlink exists:
   ```
   http://localhost/storage/dokumen/1903010040_1761322806.pdf
   ```
   Should show PDF ✅

2. Check via asset():
   ```blade
   {{ asset('storage/dokumen/1903010040_1761322806.pdf') }}
   ```
   Should generate full URL ✅

3. Click "View" button
   Should open modal with PDF ✅

---

## Common Issues & Solutions

### **Issue 1: Symlink doesn't exist**

**Error:**
```
404 Not Found
```

**Solution:**
```bash
php artisan storage:link
```

---

### **Issue 2: Symlink points to wrong location**

**Check:**
```bash
ls -la public/storage
```

**Should show:**
```
storage -> /full/path/to/storage/app/public
```

**Fix:**
```bash
rm public/storage
php artisan storage:link
```

---

### **Issue 3: Permission denied**

**Error:**
```
403 Forbidden
```

**Solution:**
```bash
chmod -R 775 storage/app/public
chmod -R 775 public/storage
```

---

### **Issue 4: File not found in storage**

**Check database vs filesystem:**

```php
// In tinker
$doc = \App\Models\Dokumen::first();
dd($doc->file_dokumen);

// Check if file exists
$exists = file_exists(storage_path('app/public/dokumen/' . $doc->file_dokumen));
dd($exists);
```

---

### **Issue 5: URL encoding issues**

If filename has spaces:

**Database:**
```
18 0301 0015_123456789.pdf
```

**Should be stored as:**
```
18_0301_0015_123456789.pdf  (spaces replaced with _)
```

**Fix in controller:**
```php
$filename = str_replace(' ', '_', $mahasiswa->nim) . '_' . 
            time() . '_' . 
            $file->getClientOriginalName();
```

---

## Prevention

### **Always use asset() for public files:**

```blade
<!-- ✅ Correct -->
<img src="{{ asset('storage/images/photo.jpg') }}">
<a href="{{ asset('storage/dokumen/file.pdf') }}">Download</a>
<iframe src="{{ asset('storage/dokumen/file.pdf') }}"></iframe>

<!-- ❌ Wrong -->
<img src="{{ Storage::url('images/photo.jpg') }}">
<a href="{{ Storage::url('dokumen/file.pdf') }}">Download</a>
```

### **Use Storage::url() only in controllers:**

```php
// Controller
public function download($id)
{
    $doc = Dokumen::findOrFail($id);
    $path = storage_path('app/public/dokumen/' . $doc->file_dokumen);
    return response()->download($path);
}
```

---

## Verification Checklist

After fix, verify:

- [ ] Symlink exists: `ls -la public/storage`
- [ ] Files exist: `ls -la storage/app/public/dokumen/`
- [ ] Permissions correct: `775` on folders, `664` on files
- [ ] Code uses `asset()` not `Storage::url()`
- [ ] Cache cleared
- [ ] Browser can access: `/storage/dokumen/file.pdf`
- [ ] Modal opens and shows PDF
- [ ] Download button works

---

## Production Deployment

On production server:

```bash
# 1. Create symlink
php artisan storage:link

# 2. Set permissions
chmod -R 775 storage/app/public
chown -R www-data:www-data storage/app/public

# 3. Verify symlink
ls -la public/storage

# 4. Test URL
curl http://yourdomain.com/storage/dokumen/test.pdf

# 5. Clear caches
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

---

## Summary

**Problem:** 404 error when accessing /storage/dokumen/

**Cause:** Used `Storage::url()` instead of `asset()`

**Solution:** Changed to `asset('storage/dokumen/' . $filename)`

**Result:** ✅ Files now accessible via web

---

## Code Changes

**File: `resources/views/dosen/detail-mahasiswa.blade.php`**

```diff
- onclick="viewPDF('{{ Storage::url('dokumen/' . $doc->file_dokumen) }}', ...)"
+ onclick="viewPDF('{{ asset('storage/dokumen/' . $doc->file_dokumen) }}', ...)"
```

**Commands:**
```bash
php artisan view:clear
chmod -R 775 storage/app/public/dokumen
```

---

## Reference

- [Laravel Storage Documentation](https://laravel.com/docs/filesystem)
- [Laravel Public Disk](https://laravel.com/docs/filesystem#the-public-disk)
- [Creating Symbolic Links](https://laravel.com/docs/filesystem#the-public-disk)
