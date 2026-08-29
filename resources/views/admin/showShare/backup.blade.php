<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe Unlock</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="col-md-6 mx-auto text-center">
            <div class="card shadow-lg p-4">
                <h4 class="card-title  fw-bold">{{ $shareLink->nama }}</h4>
                <h6 class="card-title mb-4 fw-bold">Subscribe untuk membuka link</h6>

                <!-- Tombol Subscribe (gembok) -->
                <a href="https://www.youtube.com/channel/UC0bcUTL31f_vC-EzZo-rzjA?sub_confirmation=1" target="_blank"
                    id="subscribeBtn"
                    class="btn btn-danger w-100 mb-3 d-flex justify-content-center align-items-center">
                    <i id="lockIcon" class="bi bi-lock-fill me-2"></i>
                    <span id="subscribeText">Subscribe YouTube</span>
                </a>

                <!-- Tombol Lanjut Affiliate (Step 1) -->
                <a href="{{ $affiliate->link }}" id="affiliateBtn" target="_blank"
                    class="btn btn-success w-100 disabled" tabindex="-1" aria-disabled="true">
                    ✅ Lanjut (Step 1)
                </a>

                <!-- Tombol Lanjut Asli (Step 2, hidden dulu) -->
                <a href="{{ $shareLink->link }}" id="realBtn" class="btn btn-primary w-100 mt-2 d-none">
                    🚀 Lanjut ke Link Asli (Step 2)
                </a>

                <script>
                    const affiliateBtn = document.getElementById("affiliateBtn");
                    const realBtn = document.getElementById("realBtn");

                    // Aktifkan tombol affiliate setelah subscribe
                    document.getElementById("subscribeBtn")?.addEventListener("click", function() {
                        affiliateBtn.classList.remove("disabled");
                        affiliateBtn.removeAttribute("aria-disabled");
                        affiliateBtn.removeAttribute("tabindex");
                    });

                    // Setelah klik tombol affiliate → sembunyikan tombol affiliate & munculkan tombol asli
                    affiliateBtn.addEventListener("click", function() {
                        setTimeout(() => {
                            affiliateBtn.classList.add("d-none"); // sembunyikan step 1
                            realBtn.classList.remove("d-none"); // tampilkan step 2
                        }, 500); // delay supaya user sempat diarahkan dulu
                    });
                </script>





            </div>
        </div>
    </div>

    <script>
        document.getElementById("subscribeBtn").addEventListener("click", function() {
            // Ubah icon gembok ke terbuka
            document.getElementById("lockIcon").classList.remove("bi-lock-fill");
            document.getElementById("lockIcon").classList.add("bi-unlock-fill");
            document.getElementById("subscribeText").innerText = "Subscribed";

            // Aktifkan tombol lanjut
            const nextBtn = document.getElementById("nextBtn");
            nextBtn.classList.remove("disabled");
            nextBtn.removeAttribute("aria-disabled");
            nextBtn.removeAttribute("tabindex");
        });
    </script>

</body>

</html>
