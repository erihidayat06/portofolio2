@extends('home.layouts.main')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #chat-response ul {
            padding-left: 20px;
            /* Memberi jarak list ke kanan */
            margin: 5px 0;
        }

        #chat-response li {
            margin-bottom: 5px;
            /* Jarak antar proyek */
        }

        #chat-response strong {
            color: #0d6efd;
            /* Memberi warna pada teks bold agar menonjol */
        }

        .chat-window-open {
            display: flex !important;
        }
    </style>

    <nav class="navbar navbar-expand-lg bg-main fixed-top" id="mainNav">
        <div class="container">
            <div class="navbar-collapse float-end" id="navbarNavAltMarkup">
                <div class="nav nav-underline ms-auto">
                    <a class="nav-link" aria-current="page" href="#">Home</a>
                    <a class="nav-link" href="#profil">Profil</a>
                    <a class="nav-link" href="#project">Project</a>
                    <a class="nav-link" href="#contact">Contact</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row row-cols-1 row-cols-lg-2 g-1 g-lg-2 mt-5">
            <div class="col" data-aos="fade-up" data-aos-duration="1000">
                <h1 data-aos="fade-up" data-aos-duration="500" style="margin-top: 150px" class="fw-bold">
                    {{ strtoupper($profilWeb->judul ?? 'PORTOFOLIO') }}
                </h1>
                <p class="text-white fs-5">
                <div data-aos="fade-up" data-aos-duration="500" style="color: #fff !important">{!! $profilWeb->deskripsi ?? '' !!}
                </div>
                </p>



                @if (isset($profilWeb->cv))
                    <a data-aos="fade-up" data-aos-duration="3000" class="mt-5 text-white fw-bold" target="_blank"
                        href="{{ asset('storage/' . $profilWeb->cv) }}">
                        CV
                    </a>
                @endif

                @if (isset($profilWeb->sertifikat))
                    <a class="mt-5 ms-3 text-white fw-bold" target="_blank" href="{{ $profilWeb->sertifikat }}">
                        <i class="bi bi-file-earmark-text-fill"></i> Sertifikat
                    </a>
                @endif

                <div class="mt-5">
                    <a href="#profil" class="btn btn-warning fw-bold">Telusuri</a>
                </div>
                <div style="margin-top: 100px">
                    <img class="foto-hp shadow d-block d-lg-none" src="/assets/img/foto.png" alt="Foto HP" />
                </div>
            </div>

            <div class="col d-none d-lg-block" data-aos="zoom-in" data-aos-duration="2000">
                <div style="margin-top: 100px">
                    <div class="bulat"></div>
                    <img class="foto shadow" src="/assets/img/foto.png" alt="Foto Desktop" />
                </div>
            </div>
        </div>

        <!-- Profil -->
        <div id="profil"></div>

        <div class="profil text-center">
            <h1 data-aos="fade-up">Profil</h1>
            <p>
            <div data-aos="fade-up">{!! $profilWeb->deskripsi_profil ?? 'Deskripsi profil belum tersedia.' !!}</div>
            </p>
        </div>
    </div>



    <!-- End Profil -->

    <!-- Project -->
    <div id="project"></div>

    <div class="project container">
        <h1 class="text-center mb-5" data-aos="fade-up">Project</h1>

        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($portofolios->take(4) as $index => $portofolio)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="card h-100" data-bs-toggle="modal" data-bs-target="#projekModalUtama{{ $portofolio->id }}"
                        style="cursor: pointer">
                        @php $imgs = json_decode($portofolio->gambar, true); @endphp
                        @if ($imgs && count($imgs))
                            <img src="{{ asset('storage/' . $imgs[0]) }}" alt="gambar" height="150px"
                                class="rounded-top w-100 object-fit-cover">
                        @endif
                        @php
                            $deskripsi = Str::limit($portofolio->deskripsi, 50);
                            $containsOpeningDiv = Str::contains($deskripsi, '<div');
                            $containsClosingDiv = Str::contains($deskripsi, '</div>');

                            if ($containsOpeningDiv && !$containsClosingDiv) {
                                $deskripsi .= '</div>';
                            }
                        @endphp
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-truncate">{{ $portofolio->nm_projek }}</h6>
                            <p class="card-text">{!! $deskripsi !!}

                            </p>

                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="projekModalUtama{{ $portofolio->id }}" tabindex="-1"
                    aria-labelledby="projekModalUtama{{ $portofolio->id }}Label" aria-hidden="true">
                    <div class="modal-dialog ">
                        <div class="modal-content" style="background-color: #191d88">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="projekModalUtama{{ $portofolio->id }}Label">
                                    {{ $portofolio->nm_projek }}
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                @php $gambars = json_decode($portofolio->gambar, true); @endphp

                                @if ($gambars && count($gambars))
                                    {{-- Carousel Utama --}}
                                    <div id="carouselGambar{{ $portofolio->id }}" class="carousel slide mb-3"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner rounded shadow-sm" style="height: 250px;">
                                            @foreach ($gambars as $index => $img)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <div class="position-relative h-100">
                                                        <img src="{{ asset('storage/' . $img) }}"
                                                            class="d-block w-100 h-100 object-fit-cover rounded gambar-slide"
                                                            alt="Slide {{ $index + 1 }}" style="cursor: zoom-in;"
                                                            onclick="previewFull('{{ asset('storage/' . $img) }}')">

                                                        <div
                                                            class="position-absolute top-0 start-0 bg-dark bg-opacity-50 text-white px-2 py-1 small rounded-bottom-end">
                                                            {{ $index + 1 }} / {{ count($gambars) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if (count($gambars) > 1)
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#carouselGambar{{ $portofolio->id }}"
                                                data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                                <span class="visually-hidden">Sebelumnya</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#carouselGambar{{ $portofolio->id }}"
                                                data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                                <span class="visually-hidden">Berikutnya</span>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Preview Thumbnail --}}
                                    <div class="d-flex gap-2 flex-wrap justify-content-center mb-3">
                                        @foreach ($gambars as $index => $img)
                                            <img src="{{ asset('storage/' . $img) }}" class="rounded border shadow-sm"
                                                style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                onclick="bootstrap.Carousel.getInstance(document.querySelector('#carouselGambar{{ $portofolio->id }}')).to({{ $index }})"
                                                alt="Preview {{ $index + 1 }}">
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Deskripsi --}}
                                <div class="pt-2 text-white">{!! $portofolio->deskripsi !!}</div>

                                @php $bahasa = json_decode($portofolio->bahasa_id, true); @endphp
                                @if (is_array($bahasa) && count($bahasa))
                                    <h5 class="fw-bold mt-3 text-main">Bahasa Pemprograman</h5>
                                    @foreach ($bahasa as $id)
                                        @php $bhs = $bahasas->where('id', $id)->first(); @endphp
                                        @if ($bhs)
                                            <img src="{{ asset('storage/' . $bhs->gambar) }}" alt="{{ $bhs->nama }}"
                                                height="30" class="rounded">
                                        @endif
                                    @endforeach
                                @endif

                                @php $framework = json_decode($portofolio->framework_id, true); @endphp
                                @if (is_array($framework) && count($framework))
                                    <h5 class="fw-bold mt-3 text-main">Framework</h5>
                                    @foreach ($framework as $id)
                                        @php $frame = $frameworks->where('id', $id)->first(); @endphp
                                        @if ($frame)
                                            <img src="{{ asset('storage/' . $frame->gambar) }}"
                                                alt="{{ $frame->nama }}" height="30" class="rounded">
                                        @endif
                                    @endforeach
                                @endif

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <a href="{{ $portofolio->link }}" class="btn btn-warning">Lihat
                                    projek</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lightbox-style Preview -->
                <div id="lightbox" onclick="this.style.display='none'"
                    style="display: none; position: fixed; z-index: 1056; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); align-items: center; justify-content: center;">
                    <img id="lightbox-img" src=""
                        style="max-width: 90vw; max-height: 90vh; border-radius: .5rem;">
                </div>

                <script>
                    function previewFull(src) {
                        const lightbox = document.getElementById('lightbox');
                        const img = document.getElementById('lightbox-img');
                        img.src = src;
                        lightbox.style.display = 'flex';
                    }
                </script>
            @endforeach
        </div>
    </div>





    <!-- Projek Lainnya -->
    <div class="text-center mt-5">
        <a href="" class="text-white" data-bs-target="#projekLainToggle" data-bs-toggle="modal">
            Project Lainnya >>
        </a>
    </div>

    <div class="modal fade" id="projekLainToggle" aria-hidden="true" aria-labelledby="projekLainToggleLabel"
        tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header modal-project">
                    <h1 class="modal-title fs-5" id="projekLainToggleLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-project">
                    <div class="row row-cols-1 row-cols-md-4 g-4">
                        @foreach ($portofolios->skip(4) as $portofolio)
                            <div class="col">
                                <div class="card h-100" data-bs-toggle="modal"
                                    data-bs-target="#projekModal{{ $portofolio->id }}" style="cursor: pointer">
                                    @php $imgs = json_decode($portofolio->gambar, true); @endphp
                                    @if ($imgs && count($imgs))
                                        <img src="{{ asset('storage/' . $imgs[0]) }}" alt="gambar" height="150px"
                                            class="rounded-top w-100 object-fit-cover">
                                    @endif
                                    @php
                                        $deskripsi = Str::limit($portofolio->deskripsi, 50);
                                        $containsOpeningDiv = Str::contains($deskripsi, '<div');
                                        $containsClosingDiv = Str::contains($deskripsi, '</div>');

                                        if ($containsOpeningDiv && !$containsClosingDiv) {
                                            $deskripsi .= '</div>';
                                        }
                                    @endphp
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-truncate">{{ $portofolio->nm_projek }}</h6>
                                        <p class="card-text">{!! $deskripsi !!}

                                        </p>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    @foreach ($portofolios->skip(4) as $portofolio)
        <!-- Modal -->
        <div class="modal fade" id="projekModal{{ $portofolio->id }}" tabindex="-1"
            aria-labelledby="projekModal{{ $portofolio->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-sm-down">
                <div class="modal-content" style="background-color: #191d88">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="projekModal{{ $portofolio->id }}Label">
                            {{ $portofolio->nm_projek }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        @php $gambars = json_decode($portofolio->gambar, true); @endphp

                        @if ($gambars && count($gambars))
                            {{-- Carousel Utama --}}
                            <div id="carouselGambar{{ $portofolio->id }}" class="carousel slide mb-3"
                                data-bs-ride="carousel">
                                <div class="carousel-inner rounded shadow-sm" style="height: 250px;">
                                    @foreach ($gambars as $index => $img)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <div class="position-relative h-100">
                                                <img src="{{ asset('storage/' . $img) }}"
                                                    class="d-block w-100 h-100 object-fit-cover rounded gambar-slide"
                                                    alt="Slide {{ $index + 1 }}" style="cursor: zoom-in;"
                                                    onclick="previewFull('{{ asset('storage/' . $img) }}')">

                                                <div
                                                    class="position-absolute top-0 start-0 bg-dark bg-opacity-50 text-white px-2 py-1 small rounded-bottom-end">
                                                    {{ $index + 1 }} / {{ count($gambars) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if (count($gambars) > 1)
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselGambar{{ $portofolio->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                        <span class="visually-hidden">Sebelumnya</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselGambar{{ $portofolio->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                        <span class="visually-hidden">Berikutnya</span>
                                    </button>
                                @endif
                            </div>

                            {{-- Preview Thumbnail --}}
                            <div class="d-flex gap-2 flex-wrap justify-content-center mb-3">
                                @foreach ($gambars as $index => $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="rounded border shadow-sm"
                                        style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                        onclick="bootstrap.Carousel.getInstance(document.querySelector('#carouselGambar{{ $portofolio->id }}')).to({{ $index }})"
                                        alt="Preview {{ $index + 1 }}">
                                @endforeach
                            </div>
                        @endif

                        {{-- Deskripsi --}}
                        <div class="pt-2 text-white">{!! $portofolio->deskripsi !!}</div>

                        @php $bahasa = json_decode($portofolio->bahasa_id, true); @endphp
                        @if (is_array($bahasa) && count($bahasa))
                            <h5 class="fw-bold mt-3 text-main">Bahasa Pemprograman</h5>
                            @foreach ($bahasa as $id)
                                @php $bhs = $bahasas->where('id', $id)->first(); @endphp
                                @if ($bhs)
                                    <img src="{{ asset('storage/' . $bhs->gambar) }}" alt="{{ $bhs->nama }}"
                                        height="30" class="rounded">
                                @endif
                            @endforeach
                        @endif

                        @php $framework = json_decode($portofolio->framework_id, true); @endphp
                        @if (is_array($framework) && count($framework))
                            <h5 class="fw-bold mt-3 text-main">Framework</h5>
                            @foreach ($framework as $id)
                                @php $frame = $frameworks->where('id', $id)->first(); @endphp
                                @if ($frame)
                                    <img src="{{ asset('storage/' . $frame->gambar) }}" alt="{{ $frame->nama }}"
                                        height="30" class="rounded">
                                @endif
                            @endforeach
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ $portofolio->link }}" class="btn btn-warning">Lihat projek</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox-style Preview -->
        <div id="lightbox" onclick="this.style.display='none'"
            style="display: none; position: fixed; z-index: 1056; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); align-items: center; justify-content: center;">
            <img id="lightbox-img" src="" style="max-width: 90vw; max-height: 90vh; border-radius: .5rem;">
        </div>

        <script>
            function previewFull(src) {
                const lightbox = document.getElementById('lightbox');
                const img = document.getElementById('lightbox-img');
                img.src = src;
                lightbox.style.display = 'flex';
            }
        </script>
    @endforeach
    </div>

    <!-- Contact -->
    <div id="contact"></div>
    <div style="margin-bottom: 200px" class="contact text-center" data-aos="fade-up">
        <h1>Contact</h1>

        <div class="d-flex justify-content-center">
            <div class="row mt-5">
                @php $delay = 0; @endphp

                @if (isset($profilWeb->instagram))
                    <div style="margin: 0 30px" class="col fs-1" data-aos="fade-up"
                        data-aos-delay="{{ $delay }}">
                        <a href="{{ $profilWeb->instagram }}" class="text-white" target="_blank">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                    @php $delay += 100; @endphp
                @endif

                @if (isset($profilWeb->youtube))
                    <div style="margin: 0 30px" class="col fs-1" data-aos="fade-up"
                        data-aos-delay="{{ $delay }}">
                        <a href="{{ $profilWeb->youtube }}" class="text-white" target="_blank">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                    @php $delay += 100; @endphp
                @endif

                @if (isset($profilWeb->tiktok))
                    <div style="margin: 0 30px" class="col fs-1" data-aos="fade-up"
                        data-aos-delay="{{ $delay }}">
                        <a href="{{ $profilWeb->tiktok }}" class="text-white" target="_blank">
                            <i class="bi bi-tiktok"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div id="chat-widget" style="position: fixed; bottom: 25px; right: 25px; z-index: 9999;">

        <div id="chat-widget" style="position: fixed; bottom: 25px; right: 25px; z-index: 9999;">

            <div id="chat-alert" class="alert alert-primary shadow-sm p-2 px-3 animate__animated animate__fadeInUp"
                style="position: absolute; bottom: 70px; right: 0; width: 220px; border-radius: 20px; font-size: 12px; cursor: pointer; display: none; white-space: nowrap;">
                <span class="d-flex align-items-center">
                    👋 Ada yang ingin ditanyakan?
                    <i class="bi bi-x-circle ms-auto" onclick="closeAlert(event)"></i>
                </span>
            </div>

            <button id="toggle-chat" class="btn btn-primary rounded-circle shadow-lg" style="width: 60px; height: 60px;">
                <i class="bi bi-chat-dots-fill fs-4"></i>
            </button>
        </div>

        <!-- Ubah bagian ini -->
        <div id="chat-window" class="card shadow-lg border-0"
            style="display: none; width: 350px; height: 500px; position: fixed; bottom: 100px; right: 25px; flex-direction: column;">
            <!-- Header dengan tombol minimize -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Asisten AI</span>
                <button onclick="toggleSize()" class="btn btn-sm btn-light">⇱</button>
            </div>

            <!-- Area Chat (Pesan muncul di sini) -->
            <div id="chat-messages" class="card-body overflow-auto" style="flex-grow: 1;">
                <!-- Pesan akan masuk ke sini -->
            </div>

            <!-- Input Area -->
            <div class="card-footer">
                <input type="text" id="user-input" class="form-control" placeholder="Tanya sesuatu...">
                <button onclick="sendMessage()" class="btn btn-primary mt-2 w-100">Kirim</button>
            </div>
        </div>
    </div>

    <style>
        /* Container utama pesan */
        #chat-messages {
            padding: 15px;
            background-color: #f8f9fa;
            /* Warna latar belakang area chat */
        }

        /* Style Bubble Chat */
        .chat-bubble {
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
            max-width: 85%;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Style Pesan User (Kanan) */
        .user-msg {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }

        /* Style Pesan AI (Kiri) */
        .ai-msg {
            background-color: #e9ecef;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
        }
    </style>

    <!-- End Container -->

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatAlert = document.getElementById('chat-alert');
            const toggleBtn = document.getElementById('toggle-chat');
            const chatWindow = document.getElementById('chat-window');
            const notificationSound = new Audio('/assets/audio/notification.mp3');

            // Fungsi putar yang mencoba memutar audio
            const playSound = () => {
                notificationSound.play().catch(e => {
                    console.warn("Autoplay diblokir, menunggu interaksi user...");
                });
            };

            // KUNCI: Pemicu suara harus melalui event user (Click/Touch)
            // Kita pasang ini agar begitu user menyentuh layar/klik,
            // suara langsung siap digunakan untuk notifikasi
            const enableAudio = () => {
                notificationSound.play().then(() => {
                    notificationSound.pause();
                    notificationSound.currentTime = 0;
                    document.removeEventListener('click', enableAudio);
                    document.removeEventListener('touchstart', enableAudio);
                }).catch(() => {});
            };

            document.addEventListener('click', enableAudio);
            document.addEventListener('touchstart', enableAudio);

            // 1. Logika Notifikasi Alert
            setTimeout(() => {
                chatAlert.style.display = 'block';
                playSound(); // Suara akan bunyi jika user sudah klik/sentuh layar
            }, 1000);

            setTimeout(() => {
                chatAlert.style.display = 'none';
            }, 6000);

            // 2. Fungsi Tutup Alert Manual
            window.closeAlert = function(e) {
                e.stopPropagation();
                chatAlert.style.display = 'none';
            };

            // 3. Toggle Chat
            // 3. Toggle Chat (Perbaikan Logika)
            toggleBtn.addEventListener('click', () => {
                // Gunakan style.display = '' agar mengikuti pengaturan CSS atau inline yang benar
                if (chatWindow.style.display === 'none') {
                    chatWindow.style.display = 'flex';
                } else {
                    chatWindow.style.display = 'none';
                }
                chatAlert.style.display = 'none';
            });
        });

        // Variabel untuk state ukuran
        let isLarge = false;

        function toggleSize() {
            const win = document.getElementById('chat-window');
            isLarge = !isLarge;
            win.style.width = isLarge ? '90vw' : '350px';
            win.style.height = isLarge ? '90vh' : '500px';
        }

        async function sendMessage() {
            const input = document.getElementById('user-input');
            const msgContainer = document.getElementById('chat-messages');

            if (!input.value.trim()) return;

            // 1. Tampilkan pesan user dengan class 'chat-bubble user-msg'
            msgContainer.innerHTML += `<div class="chat-bubble user-msg ms-auto"><b>Anda:</b><br>${input.value}</div>`;

            const tempId = 'loading-' + Date.now();
            // Tampilkan loading dengan class 'chat-bubble ai-msg'
            msgContainer.innerHTML +=
                `<div id="${tempId}" class="chat-bubble ai-msg"><em>AI sedang mengetik...</em></div>`;

            const message = input.value;
            input.value = '';
            msgContainer.scrollTop = msgContainer.scrollHeight;

            try {
                const response = await fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message
                    })
                });

                const data = await response.json();

                // 2. Ganti isi loading dengan class 'chat-bubble ai-msg'
                const aiResponse = document.getElementById(tempId);
                aiResponse.classList.remove('ai-msg'); // Reset jika perlu
                aiResponse.innerHTML = `<div><b>AI:</b><br>${marked.parse(data.reply)}</div>`;
                aiResponse.classList.add('chat-bubble', 'ai-msg');

                msgContainer.scrollTop = msgContainer.scrollHeight;
            } catch (error) {
                document.getElementById(tempId).innerHTML = "Maaf, terjadi kesalahan.";
            }
        }
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pilih hanya modal yang ID-nya diawali dengan "projekModal" tapi bukan "projekModalUtama"
            document.querySelectorAll('[id^="projekModal"]').forEach(modal => {
                // Lewati modal utama
                if (modal.id.startsWith('projekModalUtama')) return;

                modal.addEventListener('hidden.bs.modal', function() {
                    const projekLainModal = document.getElementById('projekLainToggle');
                    const instance = bootstrap.Modal.getInstance(projekLainModal) || new bootstrap
                        .Modal(projekLainModal);

                    if (!projekLainModal.classList.contains('show')) {
                        instance.show();
                    }
                });

                modal.addEventListener('show.bs.modal', function() {
                    const projekLainModal = bootstrap.Modal.getInstance(document.getElementById(
                        'projekLainToggle'));
                    if (projekLainModal) {
                        projekLainModal.hide();
                    }
                });
            });
        });
    </script>

    </script>
@endsection
