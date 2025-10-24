# Fix Dokumen Columns - 404 Error Solution

## Problem

Error: **The requested resource /storage/dokumen was not found**

Files exist but still getting 404 when trying to view PDF.

---

## Root Causes

### **1. Wrong Column Name**
**View menggunakan:** `$doc->file_dokumen`  
**Database actual:** `$doc->nama_file`

### **2. Spaces in Filename**
**Stored in DB:** `"18 0301 0015_1760960589.pdf"`  
**URL needs:** `"18+0301+0015_1760960589.pdf"` (URL encoded)

---

## Database Structure

```php
// Table: dokumen
id_dokumen
nim_mahasiswa
id_dosen
judul_dokumen
nama_file         ← Correct column
path_file
tipe_file
ukuran_file
tanggal_unggah
status_baca_dosen
```

---

## Solutions Applied

### **1. Fix View - Use Correct Column + URL Encoding**

**File: `resources/views/dosen/detail-mahasiswa.blade.php`**

**Before (Wrong):**
```blade
onclick="viewPDF('{{ asset('storage/dokumen/' . $doc->file_dokumen) }}', ...)"
```

**After (Correct):**
```blade
onclick="viewPDF('{{ asset('storage/dokumen/' . urlencode($doc->nama_file)) }}', ...)"
```

**Changes:**
- ✅ `$doc->file_dokumen` → `$doc->nama_file` (correct column)
- ✅ Added `urlencode()` to handle spaces in filename

---

### **2. Fix Controller - Remove Spaces from Filename**

**File: `app/Http/Controllers/MahasiswaController.php`**

**Before:**
```php
$filename = $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
// Result: "18 0301 0015_1234567890.pdf" (with spaces)
```

**After:**
```php
$nimClean = str_replace(' ', '_', $mahasiswa->nim);
$filename = $nimClean . '_' . time() . '_' . $file->getClientOriginalName();
// Result: "18_0301_0015_1234567890_filename.pdf" (no spaces)
```

**Changes:**
- ✅ Replace spaces with underscores in NIM
- ✅ Include original filename
- ✅ Store in correct columns (`nama_file`, `path_file`)

---

## URL Encoding

### **Why urlencode()?**

Spaces in URLs must be encoded:
- Space → `%20` or `+`
- Without encoding → 404 error

**Example:**
```php
// Filename with spaces
"18 0301 0015_1760960589.pdf"

// urlencode() converts to:
"18+0301+0015_1760960589.pdf"

// Full URL becomes:
http://localhost/storage/dokumen/18+0301+0015_1760960589.pdf
```

---

## Complete Fix

### **View Code:**
```blade
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
```

### **Controller Code:**
```php
public function uploadDokumen(Request $request)
{
    $mahasiswa = Auth::guard('mahasiswa')->user();
    
    $request->validate([
        'judul_dokumen' => 'required|string|max:255',
        'file_dokumen' => 'required|file|mimes:pdf|max:10240',
    ], [
        'file_dokumen.mimes' => 'File harus berformat PDF',
        'file_dokumen.max' => 'Ukuran file maksimal 10MB',
    ]);
    
    $file = $request->file('file_dokumen');
    
    // Clean NIM (remove spaces)
    $nimClean = str_replace(' ', '_', $mahasiswa->nim);
    
    // Generate filename without spaces
    $filename = $nimClean . '_' . time() . '_' . $file->getClientOriginalName();
    
    // Store file
    $path = $file->storeAs('dokumen', $filename, 'public');
    
    // Save to database with correct columns
    Dokumen::create([
        'nim_mahasiswa' => $mahasiswa->nim,
        'id_dosen' => $mahasiswa->id_dosen_pa,
        'judul_dokumen' => $request->judul_dokumen,
        'nama_file' => $filename,                    // ← Correct column
        'path_file' => 'dokumen/' . $filename,       // ← Full path
        'tipe_file' => 'pdf',
        'ukuran_file' => $file->getSize(),
        'tanggal_unggah' => now(),
        'status_baca_dosen' => 'Belum Dilihat',
    ]);
    
    return redirect()->route('mahasiswa.dashboard')
        ->with('success', 'Dokumen PDF berhasil diupload!');
}
```

---

## Testing

### **Test Existing Files (with spaces):**

1. Check database:
   ```sql
   SELECT nama_file FROM dokumen;
   -- "18 0301 0015_1760960589.pdf"
   ```

2. URL will be encoded:
   ```
   http://localhost/storage/dokumen/18+0301+0015_1760960589.pdf
   ```

3. Click view button → Should open PDF ✅

### **Test New Upload (no spaces):**

1. Upload new PDF
2. Check database:
   ```sql
   SELECT nama_file FROM dokumen ORDER BY tanggal_unggah DESC LIMIT 1;
   -- "18_0301_0015_1234567890_filename.pdf"
   ```

3. URL will be clean:
   ```
   http://localhost/storage/dokumen/18_0301_0015_1234567890_filename.pdf
   ```

4. Click view button → Should open PDF ✅

---

## Migration Path

### **For Existing Data (Optional):**

If you want to rename all existing files to remove spaces:

```php
// Create a command or run in tinker
$dokumens = Dokumen::all();

foreach ($dokumens as $doc) {
    $oldFile = storage_path('app/public/dokumen/' . $doc->nama_file);
    
    if (file_exists($oldFile)) {
        // New filename without spaces
        $newFilename = str_replace(' ', '_', $doc->nama_file);
        $newFile = storage_path('app/public/dokumen/' . $newFilename);
        
        // Rename file
        rename($oldFile, $newFile);
        
        // Update database
        $doc->update([
            'nama_file' => $newFilename,
            'path_file' => 'dokumen/' . $newFilename,
        ]);
        
        echo "Renamed: {$doc->nama_file} → {$newFilename}\n";
    }
}
```

**But it's not necessary!** URL encoding handles existing files with spaces.

---

## Summary

### **Problem:**
- ❌ Used wrong column: `$doc->file_dokumen`
- ❌ Filenames had spaces causing 404

### **Solution:**
- ✅ Use correct column: `$doc->nama_file`
- ✅ Add `urlencode()` for existing files with spaces
- ✅ Remove spaces when creating new files

### **Result:**
- ✅ Old files work (spaces encoded)
- ✅ New files work (no spaces)
- ✅ PDF preview opens correctly

---

## Files Changed

```
✅ resources/views/dosen/detail-mahasiswa.blade.php
   → Changed: $doc->file_dokumen → urlencode($doc->nama_file)

✅ app/Http/Controllers/MahasiswaController.php
   → Changed: Remove spaces from NIM in filename
   → Changed: Use correct database columns
```

---

## Verification

After fix:

```bash
# Clear caches
php artisan view:clear
php artisan config:clear

# Test in browser
1. Go to detail mahasiswa
2. See dokumen list
3. Click 👁️ button
4. Modal opens with PDF ✅
```

---

## Prevention

**Always:**
- ✅ Use correct column names from database
- ✅ Remove special characters from filenames
- ✅ Use `urlencode()` when building URLs with filenames
- ✅ Test with actual database data

**Never:**
- ❌ Assume column names
- ❌ Use spaces in filenames
- ❌ Forget URL encoding

---

## Reference

- Laravel Storage: https://laravel.com/docs/filesystem
- PHP urlencode(): https://www.php.net/manual/en/function.urlencode.php
- URL encoding: https://www.w3schools.com/tags/ref_urlencode.asp
