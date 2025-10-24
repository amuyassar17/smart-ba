<?php

// Script to populate Controllers and Middlewares

$files = [
    'app/Http/Controllers/AuthController.php' => <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('mahasiswa')->check()) {
            return redirect()->route('mahasiswa.dashboard');
        }
        if (Auth::guard('dosen')->check()) {
            return redirect()->route('dosen.dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'required|in:mahasiswa,dosen',
        ]);

        $credentials = [
            'password' => $request->password,
        ];

        if ($request->role === 'mahasiswa') {
            $credentials['nim'] = $request->username;
            
            if (Auth::guard('mahasiswa')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('mahasiswa.dashboard'));
            }
        } else {
            $credentials['nidn_dosen'] = $request->username;
            
            if (Auth::guard('dosen')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('dosen.dashboard'));
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('mahasiswa')->check()) {
            Auth::guard('mahasiswa')->logout();
        } elseif (Auth::guard('dosen')->check()) {
            Auth::guard('dosen')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
PHP,

    'app/Http/Controllers/HomeController.php' => <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function profil()
    {
        return view('profil');
    }

    public function fasilitas()
    {
        return view('fasilitas');
    }

    public function kontak()
    {
        return view('kontak');
    }
}
PHP,

    'app/Http/Controllers/MahasiswaController.php' => <<<'PHP'
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
        
        $sudahEvaluasiDosen = EvaluasiDosen::where('nim_mahasiswa', $mahasiswa->nim)
            ->where('periode_evaluasi', $periodeSekarang)
            ->exists();

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
            'sudahEvaluasiDosen'
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
}
PHP,

    'app/Http/Controllers/DosenController.php' => <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Logbook;
use App\Models\Dokumen;

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
}
PHP,

    'app/Http/Middleware/CheckMahasiswa.php' => <<<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMahasiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('mahasiswa')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai mahasiswa terlebih dahulu.');
        }

        return $next($request);
    }
}
PHP,

    'app/Http/Middleware/CheckDosen.php' => <<<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDosen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('dosen')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai dosen terlebih dahulu.');
        }

        return $next($request);
    }
}
PHP,
];

foreach ($files as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    if (file_exists($fullPath)) {
        echo "Writing: $path\n";
        file_put_contents($fullPath, $content);
    }
}

echo "\n✅ All Controllers and Middlewares populated!\n";
