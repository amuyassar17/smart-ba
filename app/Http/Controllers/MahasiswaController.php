<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logbook;
use App\Models\Dokumen;
use App\Models\Pencapaian;
use App\Models\RiwayatAkademik;
use App\Models\EvaluasiDosen;
use App\Models\EvaluasiSoftskill;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->load('dosenPA', 'programStudi');

        // Ambil data logbook
        $logbook = Logbook::where('nim_mahasiswa', $mahasiswa->nim)
            ->orderBy('tanggal_bimbingan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil data pencapaian
        $daftarPencapaian = [
            'Seminar Proposal', 
            'Ujian Komperehensif', 
            'Seminar Hasil', 
            'Ujian Skripsi (Yudisium)', 
            'Publikasi Jurnal'
        ];
        
        $pencapaian = Pencapaian::where('nim_mahasiswa', $mahasiswa->nim)->get()->keyBy('nama_pencapaian');
        $totalPencapaian = count($daftarPencapaian);
        $jumlahSelesai = $pencapaian->where('status', 'Selesai')->count();
        $persentaseKemajuan = $totalPencapaian > 0 ? round(($jumlahSelesai / $totalPencapaian) * 100) : 0;

        // Ambil riwayat akademik untuk chart
        $riwayatAkademik = RiwayatAkademik::where('nim_mahasiswa', $mahasiswa->nim)
            ->orderBy('semester', 'asc')
            ->get();

        // Ambil dokumen
        $dokumen = Dokumen::where('nim_mahasiswa', $mahasiswa->nim)
            ->orderBy('tanggal_unggah', 'desc')
            ->get();

        // Ambil evaluasi softskill
        $evaluasiSoftskill = EvaluasiSoftskill::where('nim_mahasiswa', $mahasiswa->nim)
            ->orderBy('periode_evaluasi', 'desc')
            ->orderBy('kategori', 'asc')
            ->get()
            ->groupBy('periode_evaluasi');

        // Cek notifikasi
        $notifKRS = $mahasiswa->krs_disetujui && !$mahasiswa->krs_notif_dilihat;
        $notifLogbook = Logbook::where('nim_mahasiswa', $mahasiswa->nim)
            ->where('pengisi', 'Dosen')
            ->where('status_baca', 'Belum Dibaca')
            ->count();

        // Tandai notifikasi sudah dilihat
        if ($notifKRS) {
            $mahasiswa->update(['krs_notif_dilihat' => true]);
        }
        if ($notifLogbook > 0) {
            Logbook::where('nim_mahasiswa', $mahasiswa->nim)
                ->where('pengisi', 'Dosen')
                ->update(['status_baca' => 'Dibaca']);
        }

        // Cek evaluasi dosen periode saat ini
        $currentYear = date('Y');
        $currentMonth = date('n');
        $periodeSekarang = $currentYear . ' ' . (($currentMonth >= 2 && $currentMonth <= 7) ? 'Genap' : 'Ganjil');
        
        $evaluasiDosenTerakhir = EvaluasiDosen::where('nim_mahasiswa', $mahasiswa->nim)
            ->where('periode_evaluasi', $periodeSekarang)
            ->first();
        
        $sudahEvaluasiDosen = $evaluasiDosenTerakhir !== null;

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'logbook',
            'daftarPencapaian',
            'pencapaian',
            'persentaseKemajuan',
            'riwayatAkademik',
            'dokumen',
            'evaluasiSoftskill',
            'notifKRS',
            'notifLogbook',
            'periodeSekarang',
            'sudahEvaluasiDosen',
            'evaluasiDosenTerakhir'
        ));
    }

    public function storeLogbook(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required|string|max:255',
            'isi_bimbingan' => 'nullable|string',
        ]);

        Logbook::create([
            'nim_mahasiswa' => $mahasiswa->nim,
            'id_dosen' => $mahasiswa->id_dosen_pa,
            'pengisi' => 'Mahasiswa',
            'status_baca' => 'Belum Dibaca',
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan' => $request->topik_bimbingan,
            'isi_bimbingan' => $request->isi_bimbingan,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Catatan bimbingan berhasil disimpan!');
    }

    public function storeEvaluasiDosen(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $request->validate([
            'skor_komunikasi' => 'required|integer|min:1|max:5',
            'skor_membantu' => 'required|integer|min:1|max:5',
            'skor_solusi' => 'required|integer|min:1|max:5',
            'saran_kritik' => 'nullable|string',
        ]);

        $currentYear = date('Y');
        $currentMonth = date('n');
        $periodeSekarang = $currentYear . ' ' . (($currentMonth >= 2 && $currentMonth <= 7) ? 'Genap' : 'Ganjil');

        EvaluasiDosen::create([
            'nim_mahasiswa' => $mahasiswa->nim,
            'id_dosen' => $mahasiswa->id_dosen_pa,
            'periode_evaluasi' => $periodeSekarang,
            'skor_komunikasi' => $request->skor_komunikasi,
            'skor_membantu' => $request->skor_membantu,
            'skor_solusi' => $request->skor_solusi,
            'saran_kritik' => $request->saran_kritik,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Evaluasi berhasil dikirim!');
    }

    public function uploadDokumen(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $file = $request->file('file_dokumen');
        $filename = $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dokumen', $filename, 'public');

        Dokumen::create([
            'nim_mahasiswa' => $mahasiswa->nim,
            'id_dosen' => $mahasiswa->id_dosen_pa,
            'judul_dokumen' => $request->judul_dokumen,
            'nama_file' => $filename,
            'path_file' => 'storage/' . $path,
            'tipe_file' => $file->getClientOriginalExtension(),
            'ukuran_file' => $file->getSize(),
            'status_baca_dosen' => 'Belum Dilihat',
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumen berhasil diunggah!');
    }

    public function profil()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->load('dosenPA', 'programStudi', 'riwayatAkademik', 'pencapaian');

        return view('mahasiswa.profil', compact('mahasiswa'));
    }

    public function riwayat()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->load('dosenPA', 'programStudi');

        // Ambil riwayat akademik lengkap
        $riwayatAkademik = RiwayatAkademik::where('nim_mahasiswa', $mahasiswa->nim)
            ->orderBy('semester', 'asc')
            ->get();

        // Ambil nilai mahasiswa
        $nilaiMahasiswa = $mahasiswa->nilaiMahasiswa()
            ->orderBy('semester_diambil', 'desc')
            ->get();

        // Ambil nilai bermasalah
        $nilaiBermasalah = $mahasiswa->nilaiBermasalah()
            ->where('status_perbaikan', 'Belum')
            ->orderBy('semester_diambil', 'desc')
            ->get();

        // Ambil KRS
        $krs = $mahasiswa->krs()
            ->with('mataKuliah')
            ->orderBy('semester_diambil', 'desc')
            ->get();

        return view('mahasiswa.riwayat', compact(
            'mahasiswa',
            'riwayatAkademik',
            'nilaiMahasiswa',
            'nilaiBermasalah',
            'krs'
        ));
    }

    public function lengkapiRiwayat()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        // Generate 14 semester (S1 biasanya max 14 semester / 7 tahun)
        $maxSemester = 14;
        $riwayatAkademik = [];
        
        // Ambil data riwayat yang sudah ada
        $existingRiwayat = RiwayatAkademik::where('nim_mahasiswa', $mahasiswa->nim)
            ->get()
            ->keyBy('semester');
        
        // Generate array untuk 14 semester dengan data existing jika ada
        for ($i = 1; $i <= $maxSemester; $i++) {
            $riwayatAkademik[$i] = [
                'semester' => $i,
                'ip_semester' => $existingRiwayat->has($i) ? $existingRiwayat[$i]->ip_semester : null,
                'sks_semester' => $existingRiwayat->has($i) ? $existingRiwayat[$i]->sks_semester : null,
                'exists' => $existingRiwayat->has($i)
            ];
        }
        
        return view('mahasiswa.lengkapi-riwayat', compact('mahasiswa', 'riwayatAkademik'));
    }

    public function simpanRiwayat(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        // Validasi input
        $request->validate([
            'ip_semester' => 'required|array',
            'ip_semester.*' => 'nullable|numeric|min:0|max:4',
            'sks_semester' => 'required|array',
            'sks_semester.*' => 'nullable|integer|min:0|max:30',
        ], [
            'ip_semester.*.numeric' => 'IP harus berupa angka',
            'ip_semester.*.min' => 'IP minimal 0.00',
            'ip_semester.*.max' => 'IP maksimal 4.00',
            'sks_semester.*.integer' => 'SKS harus berupa angka bulat',
            'sks_semester.*.min' => 'SKS minimal 0',
            'sks_semester.*.max' => 'SKS maksimal 30',
        ]);
        
        $totalIP = 0;
        $totalSKS = 0;
        $semesterDiisi = 0;
        
        // Loop setiap semester
        foreach ($request->ip_semester as $semester => $ip) {
            $sks = $request->sks_semester[$semester] ?? null;
            
            // Skip jika IP atau SKS kosong
            if (empty($ip) && empty($sks)) {
                continue;
            }
            
            // Validasi: IP dan SKS harus diisi bersamaan
            if (empty($ip) || empty($sks)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => "Semester {$semester}: IP dan SKS harus diisi keduanya atau kosongkan keduanya"]);
            }
            
            // Update or Create riwayat akademik
            RiwayatAkademik::updateOrCreate(
                [
                    'nim_mahasiswa' => $mahasiswa->nim,
                    'semester' => $semester
                ],
                [
                    'ip_semester' => $ip,
                    'sks_semester' => $sks
                ]
            );
            
            // Hitung total untuk IPK
            $totalIP += ($ip * $sks);
            $totalSKS += $sks;
            $semesterDiisi++;
        }
        
        // Hitung IPK
        $ipk = $totalSKS > 0 ? round($totalIP / $totalSKS, 2) : 0;
        
        // Hitung IPS terakhir (ambil semester tertinggi yang diisi)
        $lastSemester = max(array_keys(array_filter($request->ip_semester)));
        $ips = $request->ip_semester[$lastSemester] ?? $mahasiswa->ips;
        
        // Update data mahasiswa
        $mahasiswa->update([
            'ipk' => $ipk,
            'ips' => $ips,
            'total_sks' => $totalSKS,
            'semester_berjalan' => $lastSemester
        ]);
        
        return redirect()->route('mahasiswa.riwayat')
            ->with('success', "Riwayat akademik berhasil disimpan! IPK Anda: {$ipk} ({$semesterDiisi} semester, {$totalSKS} SKS)");
    }
}