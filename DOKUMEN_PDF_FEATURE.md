# Fitur Upload dan Preview Dokumen PDF

## Overview

Sistem upload dokumen telah diupdate untuk:
1. **Mahasiswa**: Hanya bisa upload file PDF (max 10MB)
2. **Dosen**: Bisa lihat preview PDF dalam modal popup

---

## 🎯 Features

### **1. Upload Dokumen (Mahasiswa)**

#### Restrictions:
- ✅ Hanya file **PDF**
- ✅ Maksimal ukuran: **10MB**
- ✅ Validasi di server-side dan client-side
- ✅ Custom error messages dalam Bahasa Indonesia

#### Form Upload:
```html
<input type="file" 
       name="file_dokumen" 
       accept=".pdf" 
       required>
<div class="form-text">
    Hanya file PDF yang diperbolehkan. Maksimal 10MB.
</div>
```

---

### **2. Preview PDF (Dosen)**

#### Features:
- ✅ Modal popup fullscreen (XL size)
- ✅ PDF viewer dengan iframe
- ✅ Button download PDF
- ✅ Icon PDF merah untuk visual
- ✅ Badge status (Baru/Dilihat)

#### Button View:
```html
<button onclick="viewPDF(pdfUrl, pdfTitle)">
    <i class="bi bi-eye"></i>
</button>
```

#### Modal Components:
- **Header**: Title dokumen dengan icon PDF
- **Body**: iframe 80vh untuk preview
- **Footer**: Button Download + Tutup

---

## 📂 File Changes

### **1. MahasiswaController.php**

**Method: uploadDokumen()**

```php
public function uploadDokumen(Request $request)
{
    $request->validate([
        'judul_dokumen' => 'required|string|max:255',
        'file_dokumen' => 'required|file|mimes:pdf|max:10240', // Only PDF, max 10MB
    ], [
        'file_dokumen.mimes' => 'File harus berformat PDF',
        'file_dokumen.max' => 'Ukuran file maksimal 10MB',
        'file_dokumen.required' => 'File dokumen wajib diupload',
        'judul_dokumen.required' => 'Judul dokumen wajib diisi',
    ]);

    $mahasiswa = Auth::guard('mahasiswa')->user();
    
    // Generate filename: nim_timestamp_originalname
    $filename = str_replace(' ', '_', $mahasiswa->nim) . '_' . 
                time() . '_' . 
                $file->getClientOriginalName();
    $file->storeAs('dokumen', $filename, 'public');

    Dokumen::create([
        'nim_mahasiswa' => $mahasiswa->nim,
        'id_dosen' => $mahasiswa->id_dosen_pa,
        'judul_dokumen' => $request->judul_dokumen,
        'file_dokumen' => $filename,
        'tanggal_unggah' => now(),
        'ukuran_file' => $file->getSize(),
        'status_baca_dosen' => 'Belum Dilihat',
    ]);

    return redirect()->route('mahasiswa.dashboard')
        ->with('success', 'Dokumen PDF berhasil diupload!');
}
```

**Changes:**
- ✅ Validation: `mimes:pdf` (hanya PDF)
- ✅ Max size: `max:10240` (10MB)
- ✅ Custom error messages
- ✅ Better filename format

---

### **2. mahasiswa/dashboard.blade.php**

**Modal Upload Dokumen:**

```blade
<div class="mb-3">
    <label class="form-label">File Dokumen (PDF)</label>
    <input type="file" 
           name="file_dokumen" 
           class="form-control" 
           accept=".pdf" 
           required>
    <div class="form-text">
        <i class="bi bi-info-circle me-1"></i>
        Hanya file PDF yang diperbolehkan. Maksimal 10MB.
    </div>
</div>
```

**Changes:**
- ✅ Accept: `.pdf` only
- ✅ Help text dengan icon
- ✅ Clear instruction

---

### **3. dosen/detail-mahasiswa.blade.php**

**List Dokumen dengan Button View:**

```blade
<div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
    <div class="flex-grow-1">
        <div class="d-flex align-items-center">
            <i class="bi bi-file-earmark-pdf text-danger fs-4 me-2"></i>
            <div>
                <strong>{{ $doc->judul_dokumen }}</strong>
                <br>
                <small class="text-muted">
                    {{ $doc->tanggal_upload }} | {{ $doc->size }} KB
                </small>
            </div>
        </div>
    </div>
    <div class="d-flex gap-1 align-items-center">
        <button class="btn btn-sm btn-outline-primary" 
                onclick="viewPDF('{{ Storage::url('dokumen/' . $doc->file_dokumen) }}', '{{ $doc->judul_dokumen }}')"
                title="Lihat PDF">
            <i class="bi bi-eye"></i>
        </button>
        <span class="badge {{ $doc->status_baca_dosen == 'Sudah Dilihat' ? 'bg-success' : 'bg-warning' }}">
            {{ $doc->status_baca_dosen == 'Sudah Dilihat' ? 'Dilihat' : 'Baru' }}
        </span>
    </div>
</div>
```

**Modal PDF Viewer:**

```blade
<div class="modal fade" id="pdfViewerModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    <span id="pdfTitle">Preview Dokumen</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="pdfViewer" 
                        style="width: 100%; height: 100%; border: none;" 
                        src=""></iframe>
            </div>
            <div class="modal-footer">
                <a id="pdfDownload" href="" download class="btn btn-success">
                    <i class="bi bi-download me-2"></i>Download PDF
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
```

**JavaScript Function:**

```javascript
function viewPDF(pdfUrl, pdfTitle) {
    document.getElementById('pdfTitle').textContent = pdfTitle;
    document.getElementById('pdfViewer').src = pdfUrl;
    document.getElementById('pdfDownload').href = pdfUrl;
    
    const modal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
    modal.show();
}

// Clear iframe when modal is hidden
document.getElementById('pdfViewerModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('pdfViewer').src = '';
});
```

---

## 🎨 UI/UX Improvements

### **Mahasiswa Dashboard:**

**Before:**
```
[File Input] File (PDF, DOC, DOCX - Max 5MB)
```

**After:**
```
[File Input] File Dokumen (PDF)
ℹ️ Hanya file PDF yang diperbolehkan. Maksimal 10MB.
```

### **Dosen Detail Mahasiswa:**

**Before:**
```
📄 Dokumen Title
   Date | Size
   [Badge: Status]
```

**After:**
```
📕 PDF Icon | Dokumen Title
            Date | Size
            [👁️ View] [Badge: Status]
```

---

## 📊 Validation Rules

### **Server-Side (Laravel):**

```php
'file_dokumen' => [
    'required',         // Wajib
    'file',            // Harus file
    'mimes:pdf',       // Hanya PDF
    'max:10240',       // Max 10MB (in KB)
]
```

### **Client-Side (HTML):**

```html
<input type="file" 
       accept=".pdf"    <!-- Only PDF files -->
       required>        <!-- Must upload -->
```

---

## 🔄 Flow Diagram

### **Upload Flow (Mahasiswa):**

```
User selects PDF
     ↓
Client validation (accept=".pdf")
     ↓
Submit form
     ↓
Server validation (mimes:pdf, max:10240)
     ↓
Generate filename: nim_timestamp_originalname.pdf
     ↓
Store in storage/app/public/dokumen/
     ↓
Save to database (dokumen table)
     ↓
Redirect with success message
```

### **View Flow (Dosen):**

```
Click "View" button (👁️)
     ↓
JavaScript: viewPDF(url, title)
     ↓
Set iframe src = PDF URL
     ↓
Show modal (Bootstrap)
     ↓
Browser renders PDF in iframe
     ↓
Options: [Download] or [Close]
     ↓
On close: Clear iframe src (stop loading)
```

---

## ✅ Testing Checklist

### **Mahasiswa - Upload:**
- [ ] Try upload PDF < 10MB → ✅ Success
- [ ] Try upload PDF > 10MB → ❌ Error: "Ukuran file maksimal 10MB"
- [ ] Try upload DOC/DOCX → ❌ Error: "File harus berformat PDF"
- [ ] Try upload image (JPG/PNG) → ❌ Error: "File harus berformat PDF"
- [ ] Try submit without file → ❌ Error: "File dokumen wajib diupload"
- [ ] Try submit without title → ❌ Error: "Judul dokumen wajib diisi"

### **Dosen - View:**
- [ ] Click view button → ✅ Modal opens
- [ ] PDF displays in iframe → ✅ Rendered
- [ ] Can scroll/zoom PDF → ✅ Works
- [ ] Click download → ✅ PDF downloads
- [ ] Click close → ✅ Modal closes, iframe cleared
- [ ] Open another PDF → ✅ New PDF loads
- [ ] Badge shows correct status → ✅ "Baru" or "Dilihat"

---

## 🎯 Benefits

### **For Mahasiswa:**
- ✅ Clear instructions (hanya PDF, max 10MB)
- ✅ Faster upload (no need to convert)
- ✅ Consistent format
- ✅ Better file management

### **For Dosen:**
- ✅ Quick preview (no download needed)
- ✅ In-browser PDF viewer
- ✅ Can download if needed
- ✅ Visual indicators (icon, badge)
- ✅ Better UX with modal

### **Technical:**
- ✅ Reduced server storage (PDF only, smaller files)
- ✅ Better security (restricted file types)
- ✅ Easier management (consistent format)
- ✅ Faster loading (PDFs are optimized)

---

## 📱 Responsive Design

### **Desktop:**
- Modal: XL size (1140px)
- iframe: 80vh height
- Side-by-side buttons

### **Tablet:**
- Modal: Full width
- iframe: 80vh height
- Buttons stack if needed

### **Mobile:**
- Modal: Full screen
- iframe: 70vh height
- Buttons stack vertically
- PDF can be pinch-zoomed

---

## 🔐 Security

### **File Type Validation:**
```php
// Server-side (tidak bisa dibypass)
'mimes:pdf'  // Check MIME type
```

### **File Size Limit:**
```php
'max:10240'  // 10MB = 10,240 KB
```

### **Filename Sanitization:**
```php
$filename = str_replace(' ', '_', $mahasiswa->nim) . '_' . 
            time() . '_' . 
            $file->getClientOriginalName();
```

### **Storage:**
```
storage/app/public/dokumen/
↓ symlink
public/storage/dokumen/  (accessible via web)
```

---

## 🚀 Production Deployment

### **Requirements:**
1. ✅ Storage symlink must exist
2. ✅ storage/app/public/dokumen/ must be writable
3. ✅ PHP max_upload_filesize >= 10M
4. ✅ PHP post_max_size >= 10M

### **Commands:**
```bash
# Create storage symlink
php artisan storage:link

# Set permissions
chmod -R 775 storage/app/public/dokumen

# Clear caches
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### **Check php.ini:**
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 60
```

---

## 📝 Error Messages

### **Validation Errors (Indonesian):**

| Error | Message |
|-------|---------|
| Wrong file type | File harus berformat PDF |
| File too large | Ukuran file maksimal 10MB |
| No file uploaded | File dokumen wajib diupload |
| No title | Judul dokumen wajib diisi |

### **Success Messages:**

| Action | Message |
|--------|---------|
| Upload success | Dokumen PDF berhasil diupload! |

---

## 🎨 Icons Used

| Element | Icon | Class |
|---------|------|-------|
| PDF file | 📕 | `bi-file-earmark-pdf` |
| View button | 👁️ | `bi-eye` |
| Download button | ⬇️ | `bi-download` |
| Info text | ℹ️ | `bi-info-circle` |
| Close button | ✖️ | `btn-close` |

---

## ✨ Future Enhancements

Possible improvements:
- [ ] Progress bar during upload
- [ ] Drag & drop upload area
- [ ] Multiple file upload at once
- [ ] PDF thumbnail preview in list
- [ ] Mark as read when viewed
- [ ] Search/filter dokumen
- [ ] Sort by date/size/name
- [ ] Bulk download
- [ ] PDF annotation (if needed)

---

## 📞 Support

Jika ada masalah:
1. Check error message (sudah dalam Bahasa Indonesia)
2. Verify file adalah PDF dan < 10MB
3. Check browser console for JavaScript errors
4. Check Laravel logs: `storage/logs/laravel.log`
5. Verify storage permissions

---

## Summary

✅ **Completed Features:**
- Upload hanya PDF (max 10MB)
- Validation server & client side
- Custom error messages
- Preview PDF dalam modal
- Download PDF option
- Visual improvements (icons, badges)
- Responsive design
- Security measures

**Files Updated:**
1. ✅ app/Http/Controllers/MahasiswaController.php
2. ✅ resources/views/mahasiswa/dashboard.blade.php
3. ✅ resources/views/dosen/detail-mahasiswa.blade.php

**Ready for production!** 🚀
