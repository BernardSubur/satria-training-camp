<footer class="bg-dark text-light pt-5 pb-4" style="font-family: 'Outfit', sans-serif;">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 pe-lg-5">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset('asset/images/logo-stc.png') }}" alt="Logo" width="60" class="bg-white rounded p-1">
                    <h4 class="fw-bold mb-0">Satria Training Camp</h4>
                </div>
                <p class="text-white-50 lh-lg">
                    Pusat pelatihan bela diri terpadu di Purwokerto. Kami berdedikasi membangun kekuatan fisik, mental juara dan kedisiplinan melalui seni bela diri Muaythai dan Boxing.
                </p>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-4">Tautan Cepat</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="#pricing" class="text-white-50 text-decoration-none hover-white">Daftar Paket</a></li>
                    <li><a href="#jadwal" class="text-white-50 text-decoration-none hover-white">Jadwal Latihan</a></li>
                    <li><a href="#lokasi" class="text-white-50 text-decoration-none hover-white">Lokasi Camp</a></li>
                    @if(Auth::check())
                        <li><a href="{{ url('/dashboard') }}" class="text-white-50 text-decoration-none hover-white">Dashboard Member</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none hover-white">Login Member</a></li>
                    @endif
                </ul>
            </div>

            <div class="col-lg-5 col-md-6">
                <h5 class="fw-bold mb-4">Hubungi Kami</h5>
                <ul class="list-unstyled d-flex flex-column gap-3">
                    <li class="d-flex align-items-start gap-3 text-white-50">
                        <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                        <span>GOR Satria Purwokerto, Jawa Tengah, Indonesia</span>
                    </li>
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-whatsapp text-success fs-5"></i>
                        <a href="https://wa.me/6288228844938" class="text-white-50 text-decoration-none hover-white" target="_blank">0882-2884-4938</a>
                    </li>
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-instagram text-danger fs-5"></i>
                        <a href="https://instagram.com/satria_training_camp" class="text-white-50 text-decoration-none hover-white" target="_blank">@satria_training_camp</a>
                    </li>
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-envelope-fill text-warning fs-5"></i>
                        <a href="mailto:adminsatriatrainingcamp@gmail.com" class="text-white-50 text-decoration-none hover-white">adminsatriatrainingcamp@gmail.com</a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary mt-5 mb-4">
        
        <div class="text-center text-white-50" style="font-size: 0.9rem;">
            &copy; {{ date('Y') }} Satria Training Camp. Hak Cipta Dilindungi.
        </div>
    </div>
</footer>

<style>
    .hover-white { transition: color 0.2s; }
    .hover-white:hover { color: white !important; }
</style>