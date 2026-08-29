<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Follow Unlock</title>

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
                <h4 class="card-title fw-bold">{{ $shareLink->nama }}</h4>
                <h6 class="card-title mb-4 fw-bold">Ikuti Halaman Facebook untuk membuka link</h6>

                <!-- Tombol Follow Facebook -->
                <a href="{{ config('services.facebook.page_url') }}" target="_blank" id="followBtn"
                    class="btn btn-primary w-100 mb-3 d-flex justify-content-center align-items-center">
                    <i id="lockIcon" class="bi bi-facebook me-2"></i>
                    <span id="followText">Klik Ikuti Halaman Facebook</span>
                </a>

                <!-- Tombol Lanjut Affiliate (Step 1 dari 2) -->
                <a href="{{ $affiliate->link }}" id="affiliateBtn" target="_blank"
                    class="btn btn-success w-100 disabled" tabindex="-1" aria-disabled="true">
                    ✅ Lanjut (Step 1/2)
                </a>

                <!-- Tombol Lanjut Asli (Step 2 dari 2, hidden dulu) -->
                <a href="{{ $shareLink->link }}" target="_blank" id="realBtn"
                    class="btn btn-success fw-bold w-100 mt-2 d-none">
                    <span>🔓 Buka Link Tujuan (2/2)</span>
                    <i class="bi bi-box-arrow-up-right ms-2"></i>
                </a>

            </div>
        </div>
    </div>

    <script>
        const followBtn = document.getElementById("followBtn");
        const affiliateBtn = document.getElementById("affiliateBtn");
        const realBtn = document.getElementById("realBtn");

        // Aktifkan tombol affiliate setelah follow Facebook
        followBtn?.addEventListener("click", function() {
            document.getElementById("lockIcon").className = "bi bi-check-circle-fill me-2";
            document.getElementById("followText").innerText = "Sudah Diikuti";

            followBtn.classList.add("disabled");
            followBtn.setAttribute("aria-disabled", "true");
            followBtn.setAttribute("tabindex", "-1");
            followBtn.style.pointerEvents = "none";

            affiliateBtn.classList.remove("disabled");
            affiliateBtn.removeAttribute("aria-disabled");
            affiliateBtn.removeAttribute("tabindex");
        });

        // Setelah klik tombol affiliate → sembunyikan tombol affiliate & munculkan tombol final (2/2)
        affiliateBtn.addEventListener("click", function() {
            setTimeout(() => {
                affiliateBtn.classList.add("d-none");
                realBtn.classList.remove("d-none");
            }, 500);
        });

        // Track ketika user klik link asli (Tuntas)
        realBtn.addEventListener("click", function() {
            fetch("/share/{{ $shareLink->id }}/complete", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            });
        });
    </script>

</body>

</html>
