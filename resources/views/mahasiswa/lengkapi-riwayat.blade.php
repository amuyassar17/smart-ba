@extends('layouts.app')

@section('title', 'Lengkapi Riwayat Akademik')

@section('content')
<div class="container my-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><i class="bi bi-pencil-square"></i> Lengkapi Riwayat Akademik</h3>
            <p class="text-muted mb-0">Isi IP dan SKS yang Anda peroleh di setiap semester. IPK dan Total SKS di profil Anda akan ter-update secara otomatis.</p>
        </div>
        <a href="{{ route('mahasiswa.riwayat') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info Card --}}
    <div class="card shadow-sm mb-4 border-info">
        <div class="card-body">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <i class="bi bi-info-circle-fill text-info" style="font-size: 2rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-2">Petunjuk Pengisian</h5>
                    <ul class="mb-0">
                        <li>Isi <strong>Indeks Prestasi (IP)</strong> dengan nilai 0.00 - 4.00</li>
                        <li>Isi <strong>Jumlah SKS</strong> yang diambil per semester (biasanya 18-24 SKS)</li>
                        <li>Kosongkan kedua field untuk semester yang belum Anda tempuh</li>
                        <li>IP dan SKS harus diisi keduanya atau kosongkan keduanya untuk setiap semester</li>
                        <li>IPK akan dihitung otomatis setelah Anda menyimpan perubahan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('mahasiswa.riwayat.simpan') }}" method="POST" id="formRiwayat">
        @csrf
        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Form Input Riwayat Akademik</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="25%" class="text-center">Semester</th>
                                <th width="37.5%" class="text-center">Indeks Prestasi (IP)</th>
                                <th width="37.5%" class="text-center">Jumlah SKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatAkademik as $semester => $data)
                                <tr>
                                    <td class="text-center">
                                        <strong class="fs-5">Semester {{ $semester }}</strong>
                                    </td>
                                    <td>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            min="0" 
                                            max="4" 
                                            name="ip_semester[{{ $semester }}]" 
                                            class="form-control form-control-lg text-center ip-input" 
                                            placeholder="Contoh: 3.52"
                                            value="{{ old('ip_semester.'.$semester, $data['ip_semester']) }}"
                                            data-semester="{{ $semester }}"
                                        >
                                    </td>
                                    <td>
                                        <input 
                                            type="number" 
                                            min="0" 
                                            max="30" 
                                            name="sks_semester[{{ $semester }}]" 
                                            class="form-control form-control-lg text-center sks-input" 
                                            placeholder="Contoh: 21"
                                            value="{{ old('sks_semester.'.$semester, $data['sks_semester']) }}"
                                            data-semester="{{ $semester }}"
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-center py-3">
                                    <small class="text-muted">
                                        <i class="bi bi-lightbulb"></i> 
                                        Kosongkan semester yang belum Anda tempuh
                                    </small>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Preview IPK --}}
        <div class="card shadow-sm mt-4 border-success" id="previewCard" style="display: none;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2"><i class="bi bi-calculator"></i> Preview Perhitungan</h5>
                        <p class="mb-0 text-muted">IPK akan dihitung berdasarkan data yang Anda masukkan</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <small class="text-muted d-block">Estimasi IPK:</small>
                        <h2 class="mb-0 text-success" id="previewIPK">0.00</h2>
                        <small class="text-muted" id="previewInfo">0 SKS dari 0 semester</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success btn-lg shadow">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ipInputs = document.querySelectorAll('.ip-input');
    const sksInputs = document.querySelectorAll('.sks-input');
    const previewCard = document.getElementById('previewCard');
    const previewIPK = document.getElementById('previewIPK');
    const previewInfo = document.getElementById('previewInfo');
    
    // Function to calculate IPK preview
    function calculatePreview() {
        let totalIP = 0;
        let totalSKS = 0;
        let semesterCount = 0;
        
        ipInputs.forEach((ipInput, index) => {
            const ip = parseFloat(ipInput.value) || 0;
            const sks = parseInt(sksInputs[index].value) || 0;
            
            if (ip > 0 && sks > 0) {
                totalIP += (ip * sks);
                totalSKS += sks;
                semesterCount++;
            }
        });
        
        const ipk = totalSKS > 0 ? (totalIP / totalSKS).toFixed(2) : '0.00';
        
        // Update preview
        if (semesterCount > 0) {
            previewCard.style.display = 'block';
            previewIPK.textContent = ipk;
            previewInfo.textContent = `${totalSKS} SKS dari ${semesterCount} semester`;
            
            // Color coding
            if (ipk >= 3.5) {
                previewIPK.className = 'mb-0 text-success';
            } else if (ipk >= 3.0) {
                previewIPK.className = 'mb-0 text-primary';
            } else if (ipk >= 2.75) {
                previewIPK.className = 'mb-0 text-warning';
            } else {
                previewIPK.className = 'mb-0 text-danger';
            }
        } else {
            previewCard.style.display = 'none';
        }
    }
    
    // Validation for IP input (0.00 - 4.00)
    ipInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = parseFloat(this.value);
            
            if (value > 4) {
                this.value = 4;
                this.classList.add('is-invalid');
                setTimeout(() => this.classList.remove('is-invalid'), 2000);
            } else if (value < 0) {
                this.value = 0;
                this.classList.add('is-invalid');
                setTimeout(() => this.classList.remove('is-invalid'), 2000);
            }
            
            calculatePreview();
        });
        
        input.addEventListener('blur', function() {
            if (this.value !== '') {
                let value = parseFloat(this.value);
                this.value = value.toFixed(2);
            }
        });
    });
    
    // Validation for SKS input (0 - 30)
    sksInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = parseInt(this.value);
            
            if (value > 30) {
                this.value = 30;
                this.classList.add('is-invalid');
                setTimeout(() => this.classList.remove('is-invalid'), 2000);
            } else if (value < 0) {
                this.value = 0;
                this.classList.add('is-invalid');
                setTimeout(() => this.classList.remove('is-invalid'), 2000);
            }
            
            calculatePreview();
        });
    });
    
    // Initial calculation on page load
    calculatePreview();
    
    // Form validation before submit
    document.getElementById('formRiwayat').addEventListener('submit', function(e) {
        let hasError = false;
        let errorMessages = [];
        
        ipInputs.forEach((ipInput, index) => {
            const semester = ipInput.dataset.semester;
            const ip = ipInput.value.trim();
            const sks = sksInputs[index].value.trim();
            
            // Check if one is filled but not the other
            if ((ip && !sks) || (!ip && sks)) {
                hasError = true;
                errorMessages.push(`Semester ${semester}: IP dan SKS harus diisi keduanya`);
                ipInput.classList.add('is-invalid');
                sksInputs[index].classList.add('is-invalid');
            } else {
                ipInput.classList.remove('is-invalid');
                sksInputs[index].classList.remove('is-invalid');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('Perhatian:\n\n' + errorMessages.join('\n'));
            return false;
        }
        
        // Confirm before submit
        const confirmed = confirm('Apakah Anda yakin ingin menyimpan riwayat akademik ini? IPK dan data profil Anda akan diperbarui.');
        if (!confirmed) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
@endsection
