<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $shareLink->nama }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Monetag Multitag Script -->
    <script src="https://nap5k.com/tag.min.js" data-zone="11838458" async data-cfasync="false"></script>
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="col-md-7 mx-auto">

            <!-- Slot 1: Banner Iklan Atas Halaman -->
            <div class="text-center mb-3">
                <div class="p-2 bg-white rounded border shadow-sm d-inline-block w-100" style="min-height: 90px;">
                    <small class="text-muted d-block mb-1" style="font-size: 10px;">IKLAN</small>
                    <!-- Masukkan Script In-Page Push / Banner Atas di sini (opsional) -->
                </div>
            </div>

            <!-- Card Utama Unlock Link -->
            <div class="card shadow-lg p-4 text-center mb-4">
                <h4 class="card-title fw-bold">{{ $shareLink->nama }}</h4>
                <div class="text-muted small mb-3">
                    <i class="bi bi-eye-fill me-1"></i> Dilihat {{ number_format($shareLink->views_count ?? 0) }}x
                    <span class="mx-1">•</span>
                    <i class="bi bi-check-circle-fill me-1 text-success"></i> Selesai
                    {{ number_format($shareLink->completed_count ?? 0) }}x
                </div>
                <h6 class="card-title mb-4 text-muted">Selesaikan langkah di bawah untuk membuka link</h6>

                <!-- Langkah 1: Ikuti Facebook -->
                <a href="{{ config('services.facebook.page_url') }}" target="_blank" id="followBtn"
                    class="btn btn-primary w-100 mb-3 d-flex justify-content-center align-items-center">
                    <i id="lockIcon" class="bi bi-facebook me-2"></i>
                    <span id="followText">1. Ikuti Halaman Facebook</span>
                </a>

                <!-- Langkah 2: Link Monetag Direct / Smart Link -->
                <a href="https://omg10.com/4/11838193" target="_blank" id="monetagBtn"
                    class="btn btn-warning w-100 mb-3 fw-bold d-none">
                    <i class="bi bi-unlock-fill me-2"></i>
                    <span>2. Klik untuk Menyiapkan Link</span>
                </a>

                <!-- Slot 2: Banner Iklan Tengah -->
                <div class="my-3 text-center">
                    <div class="p-2 bg-light border rounded" style="min-height: 250px;">
                        <small class="text-muted d-block mb-1" style="font-size: 10px;">IKLAN</small>
                        <!-- Masukkan Script Banner Tengah di sini (opsional) -->
                    </div>
                </div>

                <!-- Status Timer -->
                <button id="timerBtn" class="btn btn-secondary w-100 mb-2" disabled>
                    <span id="timerSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"
                        aria-hidden="true"></span>
                    <span id="timerMessage">🔒 Klik tombol Facebook terlebih dahulu</span>
                </button>

                <!-- Tombol Buka Link Tujuan -->
                <a href="{{ $shareLink->link }}" target="_blank" id="realBtn"
                    class="btn btn-success fw-bold w-100 d-none">
                    <span>🔓 Buka Link Tujuan</span>
                    <i class="bi bi-box-arrow-up-right ms-2"></i>
                </a>
            </div>

            <!-- Card Tabel Link Lainnya -->
            <div class="card shadow-sm p-3">
                <h6 class="fw-bold mb-3"><i class="bi bi-folder-symlink me-2 text-primary"></i>Link Lainnya yang
                    Tersedia</h6>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Link</th>
                                <th class="text-center" style="width: 130px;">Total Klik</th>
                                <th class="text-end" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($otherLinks ?? [] as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 250px;">
                                            {{ $item->nama }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-cursor-fill me-1 text-primary"></i>
                                            {{ number_format($item->completed_count ?? 0) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ url('/share/' . ($item->slug ?? $item->id)) }}"
                                            class="btn btn-sm btn-outline-primary fw-bold">
                                            Buka <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Belum ada link lainnya.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Navigasi Halaman Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $otherLinks->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Slot 3: Banner Iklan Bawah Halaman -->
            <div class="text-center mt-4">
                <div class="p-2 bg-white rounded border shadow-sm d-inline-block w-100" style="min-height: 90px;">
                    <small class="text-muted d-block mb-1" style="font-size: 10px;">IKLAN</small>
                    <!-- Masukkan Script Banner Bawah di sini (opsional) -->
                </div>
            </div>

        </div>
    </div>

    <script>
        const followBtn = document.getElementById("followBtn");
        const monetagBtn = document.getElementById("monetagBtn");
        const timerBtn = document.getElementById("timerBtn");
        const timerSpinner = document.getElementById("timerSpinner");
        const timerMessage = document.getElementById("timerMessage");
        const realBtn = document.getElementById("realBtn");

        let isFollowClicked = false;
        let isMonetagClicked = false;
        let isTimerRunning = false;

        // 1. Klik Tombol Facebook
        followBtn?.addEventListener("click", function() {
            isFollowClicked = true;

            document.getElementById("lockIcon").className = "bi bi-check-circle-fill me-2";
            document.getElementById("followText").innerText = "Facebook Sudah Diikuti";

            followBtn.classList.add("disabled");
            followBtn.style.pointerEvents = "none";

            // Tampilkan langkah 2 (Monetag)
            monetagBtn.classList.remove("d-none");
            timerMessage.innerText = "👉 Silakan klik tombol ke-2 di atas...";
        });

        // 2. Klik Tombol Monetag
        monetagBtn?.addEventListener("click", function() {
            if (isMonetagClicked) return;
            isMonetagClicked = true;

            setTimeout(() => {
                monetagBtn.classList.add("disabled");
                monetagBtn.style.pointerEvents = "none";
                monetagBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Langkah Selesai';
            }, 500);

            startCountdown();
        });

        // 3. Fungsi Hitung Mundur
        function startCountdown() {
            if (isTimerRunning) return;
            isTimerRunning = true;

            timerBtn.classList.remove("btn-secondary");
            timerBtn.classList.add("btn-info", "text-white");
            timerSpinner.classList.remove("d-none");

            let timeLeft = 10;
            timerMessage.innerHTML = `Harap tunggu <strong>${timeLeft}</strong> detik...`;

            const timer = setInterval(() => {
                timeLeft--;
                timerMessage.innerHTML = `Harap tunggu <strong>${timeLeft}</strong> detik...`;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    timerBtn.classList.add("d-none");
                    realBtn.classList.remove("d-none");
                }
            }, 1000);
        }

        // 4. Track ketika pengguna klik link asli
        realBtn?.addEventListener("click", function() {
            fetch("/share/{{ $shareLink->id }}/complete", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    "Content-Type": "application/json"
                },
                keepalive: true
            }).catch(err => console.error("Error logging completion:", err));
        });
    </script>

</body>

</html>
