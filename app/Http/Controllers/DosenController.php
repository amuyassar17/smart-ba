<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Logbook;
use App\Models\Dokumen;
use App\Models\NilaiBermasalah;
use App\Models\MataKuliah;
use App\Models\EvaluasiSoftskill;

class DosenController extends Controller
{
    public function dashboard(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        // Filter
        $angkatanFilter = $request->input('angkatan');
        $searchQuery = $request->input('search');

        // Data ringkasan
        $totalMhs = Mahasiswa::where('id_dosen_pa', $dosen->id_dosen)->count();
        
        $totalNotif = Logbook::whereHas('mahasiswa', function($q) use ($dosen) {
                $q->where('id_dosen_pa', $dosen->id_dosen);
            })
            ->where('pengisi', 'Mahasiswa')
            ->where('status_baca', 'Belum Dibaca')
            ->count();
        
        $totalPeringatan = Mahasiswa::where('id_dosen_pa', $dosen->id_dosen)
            ->where(function($q) {
                $q->where('ipk', '<', 2.75)
                  ->orWhere('status_semester', 'N');
            })
            ->count();

        // Notifikasi logbook per mahasiswa
        $notifikasiLogbook = Logbook::select('nim_mahasiswa')
            ->selectRaw('COUNT(*) as jumlah')
            ->whereHas('mahasiswa', function($q) use ($dosen) {
                $q->where('id_dosen_pa', $dosen->id_dosen);
            })
            ->where('pengisi', 'Mahasiswa')
            ->where('status_baca', 'Belum Dibaca')
            ->with('mahasiswa:nim,nama_mahasiswa')
            ->groupBy('nim_mahasiswa')
            ->get();

        // Mahasiswa bermasalah
        $mahasiswaBermasalah = Mahasiswa::where('id_dosen_pa', $dosen->id_dosen)
            ->where(function($q) {
                $q->where('ipk', '<', 2.75)
                  ->orWhere('status_semester', 'N');
            })
            ->orderBy('ipk', 'asc')
            ->get();

        // Daftar angkatan
        $angkatan = Mahasiswa::where('id_dosen_pa', $dosen->id_dosen)
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        // Query mahasiswa dengan filter
        $mahasiswaQuery = Mahasiswa::where('id_dosen_pa', $dosen->id_dosen)
            ->with('programStudi')
            ->withCount([
                'pencapaian as milestones_completed' => function($q) {
                    $q->where('status', 'Selesai');
                },
                'logbook as unread_logs' => function($q) {
                    $q->where('pengisi', 'Mahasiswa')->where('status_baca', 'Belum Dibaca');
                },
                'dokumen as unread_dokumen' => function($q) {
                    $q->where('status_baca_dosen', 'Belum Dilihat');
                }
            ]);

        if ($angkatanFilter) {
            $mahasiswaQuery->where('angkatan', $angkatanFilter);
        }

        if ($searchQuery) {
            $mahasiswaQuery->where(function($q) use ($searchQuery) {
                $q->where('nama_mahasiswa', 'like', "%{$searchQuery}%")
                  ->orWhere('nim', 'like', "%{$searchQuery}%");
            });
        }

        $semuaMahasiswa = $mahasiswaQuery->orderBy('nama_mahasiswa', 'asc')->get();

        return view('dosen.dashboard', compact(
            'dosen',
            'totalMhs',
            'totalNotif',
            'totalPeringatan',
            'notifikasiLogbook',
            'mahasiswaBermasalah',
            'angkatan',
            'semuaMahasiswa',
            'angkatanFilter',
            'searchQuery'
        ));
    }

    public function detailMahasiswa($nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $mahasiswa = Mahasiswa::with(['programStudi', 'dosenPA'])
            ->where('nim', $nim)
            ->where('id_dosen_pa', $dosen->id_dosen)
            ->firstOrFail();

        // Load relationships
        $mahasiswa->load([
            'logbook' => function($q) {
                $q->orderBy('tanggal_bimbingan', 'desc');
            },
            'dokumen' => function($q) {
                $q->orderBy('tanggal_unggah', 'desc');
            },
            'pencapaian',
            'riwayatAkademik' => function($q) {
                $q->orderBy('semester', 'asc');
            },
            'nilaiBermasalah' => function($q) {
                $q->where('status_perbaikan', 'Belum');
            }
        ]);

        return view('dosen.detail-mahasiswa', compact('mahasiswa', 'dosen'));
    }

    public function approveKRS($nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('id_dosen_pa', $dosen->id_dosen)
            ->firstOrFail();

        $mahasiswa->update([
            'krs_disetujui' => true,
            'krs_notif_dilihat' => false,
        ]);

        return redirect()->back()->with('success', 'KRS berhasil disetujui!');
    }

    public function rejectKRS($nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('id_dosen_pa', $dosen->id_dosen)
            ->firstOrFail();

        $mahasiswa->update(['krs_disetujui' => false]);

        return redirect()->back()->with('success', 'Persetujuan KRS dibatalkan!');
    }

    public function toggleStatus($nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('id_dosen_pa', $dosen->id_dosen)
            ->firstOrFail();

        $newStatus = $mahasiswa->status_semester === 'A' ? 'N' : 'A';
        $mahasiswa->update(['status_semester' => $newStatus]);

        return redirect()->back()->with('success', 'Status mahasiswa berhasil diubah!');
    }

    public function storeLogbook(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('id_dosen_pa', $dosen->id_dosen)
            ->firstOrFail();

        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required|string|max:255',
            'isi_bimbingan' => 'required|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        Logbook::create([
            'nim_mahasiswa' => $mahasiswa->nim,
            'id_dosen' => $dosen->id_dosen,
            'pengisi' => 'Dosen',
            'status_baca' => 'Belum Dibaca',
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan' => $request->topik_bimbingan,
            'isi_bimbingan' => $request->isi_bimbingan,
            'tindak_lanjut' => $request->tindak_lanjut,
        ]);

        return redirect()->back()->with('success', 'Catatan bimbingan berhasil ditambahkan!');
    }

    public function storeNilaiBermasalah(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('id_dosen_pa', $dosen->id_dosen)
            ->firstOrFail();

        $request->validate([
            'nama_mk' => 'required|array|min:1',
            'nama_mk.*' => 'required|string',
            'nilai_huruf' => 'required|array|min:1',
            'nilai_huruf.*' => 'required|in:C,D,E',
            'semester_diambil' => 'required|array|min:1',
            'semester_diambil.*' => 'required|integer|min:1|max:14',
        ], [
            'nama_mk.*.required' => 'Nama mata kuliah harus diisi',
            'nilai_huruf.*.required' => 'Nilai harus dipilih',
            'nilai_huruf.*.in' => 'Nilai harus C, D, atau E',
            'semester_diambil.*.required' => 'Semester harus diisi',
            'semester_diambil.*.min' => 'Semester minimal 1',
            'semester_diambil.*.max' => 'Semester maksimal 14',
        ]);

        $count = 0;

        foreach ($request->nama_mk as $index => $namaMk) {
            if (empty($namaMk)) {
                continue;
            }

            // Check if already exists
            $exists = NilaiBermasalah::where('nim_mahasiswa', $mahasiswa->nim)
                ->where('nama_mk', $namaMk)
                ->where('semester_diambil', $request->semester_diambil[$index])
                ->where('status_perbaikan', 'Belum')
                ->exists();

            if (!$exists) {
                NilaiBermasalah::create([
                    'nim_mahasiswa' => $mahasiswa->nim,
                    'nama_mk' => $namaMk,
                    'nilai_huruf' => $request->nilai_huruf[$index],
                    'semester_diambil' => $request->semester_diambil[$index],
                    'status_perbaikan' => 'Belum',
                ]);
                $count++;
            }
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "Berhasil melaporkan {$count} nilai bermasalah!");
        } else {
            return redirect()->back()->with('info', 'Tidak ada nilai baru yang dilaporkan (mungkin sudah pernah dilaporkan sebelumnya)');
        }
    }

    public function storeEvaluasiSoftskill(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('id_dosen_pa', $dosen->id_dosen)
            ->firstOrFail();

        $request->validate([
            'periode_evaluasi' => 'required|string',
            'skor_disiplin' => 'required|integer|min:1|max:5',
            'skor_partisipasi' => 'required|integer|min:1|max:5',
            'skor_etika' => 'required|integer|min:1|max:5',
            'skor_kepemimpinan' => 'required|integer|min:1|max:5',
        ], [
            'skor_disiplin.required' => 'Skor disiplin & komitmen harus diisi',
            'skor_partisipasi.required' => 'Skor partisipasi & keaktifan harus diisi',
            'skor_etika.required' => 'Skor etika & sopan santun harus diisi',
            'skor_kepemimpinan.required' => 'Skor kepemimpinan & kerjasama harus diisi',
            '*.min' => 'Skor minimal 1',
            '*.max' => 'Skor maksimal 5',
        ]);

        // Create atau update evaluasi untuk periode ini
        $kategoriMapping = [
            'Disiplin & Komitmen' => $request->skor_disiplin,
            'Partisipasi & Keaktifan' => $request->skor_partisipasi,
            'Etika & Sopan Santun' => $request->skor_etika,
            'Kepemimpinan & Kerjasama' => $request->skor_kepemimpinan,
        ];

        foreach ($kategoriMapping as $kategori => $skor) {
            EvaluasiSoftskill::updateOrCreate(
                [
                    'nim_mahasiswa' => $mahasiswa->nim,
                    'id_dosen' => $dosen->id_dosen,
                    'kategori' => $kategori,
                    'periode_evaluasi' => $request->periode_evaluasi,
                ],
                [
                    'skor' => $skor,
                ]
            );
        }

        return redirect()->back()->with('success', 'Penilaian soft skill berhasil disimpan!');
    }
}