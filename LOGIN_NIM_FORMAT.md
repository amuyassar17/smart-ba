# Login Mahasiswa - Format NIM Otomatis

## Update Login Logic

### Problem:
- Database menyimpan NIM dengan format: `18 0301 0015` (dengan spasi)
- User ingin login dengan format: `1803010015` (tanpa spasi)
- Lebih user-friendly dan cepat

### Solution:
Sistem akan otomatis format NIM dari input user ke format database.

---

## How It Works

### Input Processing:

```php
Input User:     1803010015          (tanpa spasi)
↓
Hapus Spasi:    1803010015          (clean)
↓
Format:         18 0301 0015        (XX XXXX XXXX)
↓
Query Database: SELECT * WHERE nim = '18 0301 0015'
```

### Logic Flow:

```
1. User input NIM: "1803010015"
2. Sistem hapus semua spasi dari input
3. Validasi: harus digit dan minimal 10 karakter
4. Format menjadi: XX XXXX XXXX
   - 2 digit pertama
   - spasi
   - 4 digit berikutnya
   - spasi
   - 4 digit terakhir
5. Try login dengan format spasi
6. Jika gagal, try dengan input original
```

---

## Code Changes

### AuthController.php

**Before:**
```php
if ($request->role === 'mahasiswa') {
    $credentials['nim'] = $request->username;
    
    if (Auth::guard('mahasiswa')->attempt($credentials)) {
        // ...
    }
}
```

**After:**
```php
if ($request->role === 'mahasiswa') {
    // Hapus spasi dari input
    $nim = str_replace(' ', '', $request->username);
    
    // Format ke XX XXXX XXXX
    if (strlen($nim) >= 10 && ctype_digit($nim)) {
        $nim_formatted = substr($nim, 0, 2) . ' ' . 
                        substr($nim, 2, 4) . ' ' . 
                        substr($nim, 6);
    } else {
        $nim_formatted = $request->username;
    }
    
    // Try dengan format spasi
    $credentials['nim'] = $nim_formatted;
    if (Auth::guard('mahasiswa')->attempt($credentials)) {
        // Login berhasil
    }
    
    // Fallback: try dengan input original
    $credentials['nim'] = $request->username;
    if (Auth::guard('mahasiswa')->attempt($credentials)) {
        // Login berhasil
    }
}
```

---

## Examples

### Valid Inputs:

| User Input       | Formatted       | Database Match | Result |
|------------------|-----------------|----------------|--------|
| `1803010015`     | `18 0301 0015` | ✅ YES         | ✅ Login |
| `18 0301 0015`   | `18 0301 0015` | ✅ YES         | ✅ Login |
| `180301 0015`    | `18 0301 0015` | ✅ YES         | ✅ Login |
| `19 0401 0020`   | `19 0401 0020` | ✅ YES         | ✅ Login |

### Invalid Inputs:

| User Input    | Reason          | Result |
|---------------|-----------------|--------|
| `180301`      | Too short       | ❌ Fail |
| `18abc10015`  | Not all digits  | ❌ Fail |
| `180301001`   | Too short       | ❌ Fail |

---

## Login Page Updates

### Placeholder Text:
```html
<input 
    type="text" 
    placeholder="Contoh: 1803010015 (tanpa spasi)"
    name="username"
>
```

### Helper Text:
```html
<small class="text-muted">
    Mahasiswa: masukkan NIM tanpa spasi. Dosen: masukkan NIDN
</small>
```

---

## Testing

### Test Cases:

**1. Login dengan NIM tanpa spasi:**
```
Input: 1803010015
Password: 12345
Expected: ✅ Login Success
```

**2. Login dengan NIM dengan spasi:**
```
Input: 18 0301 0015
Password: 12345
Expected: ✅ Login Success
```

**3. Login dengan format campuran:**
```
Input: 180301 0015
Password: 12345
Expected: ✅ Login Success
```

**4. Login dengan password salah:**
```
Input: 1803010015
Password: wrong
Expected: ❌ Login Failed
```

**5. Login dengan NIM tidak ada:**
```
Input: 9999999999
Password: 12345
Expected: ❌ Login Failed
```

---

## Backward Compatibility

✅ **Sistem tetap support format lama:**
- User yang terbiasa input dengan spasi tetap bisa login
- Format apapun yang user masukkan akan di-normalize
- Tidak ada breaking changes untuk user existing

---

## Benefits

✅ **User Experience:**
- Lebih cepat input (tidak perlu spasi)
- Lebih natural (copy-paste NIM)
- Less error prone

✅ **Flexibility:**
- Support berbagai format input
- Fallback mechanism
- Backward compatible

✅ **Security:**
- Tidak mengubah data di database
- Hanya formatting di application layer
- Password tetap di-hash seperti biasa

---

## Files Changed

```
✅ app/Http/Controllers/AuthController.php
   - Update login logic untuk mahasiswa
   - Add NIM formatting
   - Add fallback mechanism

✅ resources/views/auth/login.blade.php
   - Update placeholder text
   - Add helper text
   - Improve UX
```

---

## Deployment Notes

### Production:
1. ✅ No database changes needed
2. ✅ No migration required
3. ✅ Just deploy code
4. ✅ Clear cache after deploy

### Commands:
```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

---

## Future Enhancements

Possible improvements:
- Auto-format input saat user mengetik (JavaScript)
- Validasi format NIM di client-side
- Show format hint di bawah input field
- Allow paste dari clipboard dengan auto-clean
