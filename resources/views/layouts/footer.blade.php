@if(Request::is('/'))
    {{-- Footer lengkap untuk landing page --}}
    <footer class="site-footer bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-2">SMART-BA</h6>
                    <p class="mb-1 small">Sistem Manajemen Akademik dan Bimbingan Terpadu</p>
                    <p class="mb-0 small">Fakultas Syariah dan Hukum</p>
                    <p class="mb-0 small">UIN Alauddin Makassar</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <h6 class="fw-bold mb-2">Kontak</h6>
                    <p class="mb-1 small"><i class="bi bi-geo-alt-fill me-2"></i>Jl. Sultan Alauddin No. 63, Gowa</p>
                    <p class="mb-1 small"><i class="bi bi-telephone-fill me-2"></i>(0411) 424835</p>
                    <p class="mb-0 small"><i class="bi bi-envelope-fill me-2"></i>syariah@uin-alauddin.ac.id</p>
                </div>
            </div>
            <hr class="my-3 opacity-25">
            <div class="text-center">
                <p class="mb-0 small opacity-75">&copy; {{ date('Y') }} SMART-BA. Inisiatif Smart & Green Campus UIN Palopo. All rights reserved.</p>
            </div>
        </div>
    </footer>
@else
    {{-- Footer sederhana untuk semua halaman lain --}}
    <footer class="site-footer bg-dark text-white py-3">
        <div class="container">
            <div class="text-center">
                <p class="mb-0 small opacity-75">&copy; {{ date('Y') }} SMART-BA. Inisiatif Smart & Green Campus UIN Palopo. All rights reserved.</p>
            </div>
        </div>
    </footer>
@endif
