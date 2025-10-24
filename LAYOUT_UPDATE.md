# Layout Update - Detail Mahasiswa

## Perubahan Layout

### Before:
```
┌────────────────────────────────────────┐
│  Left Column (col-lg-8)               │
│  - Grafik IP                           │
│  - Riwayat Bimbingan                   │
│  - Dokumen                             │
│  - Tabs (Lapor Nilai, Penilaian, dll) │
│                                        │
│  Right Column (col-lg-4)              │
│  - Pencapaian                          │
│  - Riwayat Akademik                    │
└────────────────────────────────────────┘
```

### After:
```
┌──────────────────────┬──────────────────────┐
│ Left (col-lg-6)      │ Right (col-lg-6)     │
│                      │                      │
│ - Grafik IP          │ - Pencapaian         │
│ - Riwayat Bimbingan  │ - Riwayat Akademik   │
│ - Dokumen            │                      │
│                      │                      │
│ Tabs (Full Width Below)                    │
│ - Lapor Nilai                              │
│ - Penilaian                                │
│ - Peringatan Akademik                      │
└────────────────────────────────────────────┘
```

## Perubahan Yang Dilakukan:

### 1. Column Width
- Left Column: `col-lg-8` → `col-lg-6` (50%)
- Right Column: `col-lg-4` → `col-lg-6` (50%)

### 2. Row Spacing
- Added: `row g-4` (gap-4 untuk spacing antar kolom)

### 3. Card Heights (Optimized)
- Riwayat Bimbingan: `500px` → `400px`
- Dokumen: `300px` → `250px`
- Riwayat Akademik: auto → `300px` dengan scroll

### 4. Visual Balance
- Konten tersebar merata di kiri dan kanan
- Tabs tetap full width di bawah
- Scrollable cards untuk content yang panjang

## Benefits:

✅ Lebih seimbang visual
✅ Lebih mudah dibaca (50-50 split)
✅ Responsive tetap bagus
✅ Content density optimal
✅ Scroll independent per card
