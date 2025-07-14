@extends('home.layouts.main')
@section('content')
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
            <div class="col">
                <h1 style="margin-top: 150px" class="fw-bold">
                    {{ strtoupper($profilWeb->judul ?? 'PORTOFOLIO') }}
                </h1>
                <p class="text-white fs-5">
                <div style="color: #fff !important">{!! $profilWeb->deskripsi !!}</div>
                </p>



                @if ($profilWeb->cv)
                    <a class="mt-5 text-white fw-bold" target="_blank" href="{{ asset('storage/' . $profilWeb->cv) }}">
                        CV
                    </a>
                @endif

                @if ($profilWeb->sertifikat)
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

            <div class="col d-none d-lg-block">
                <div style="margin-top: 100px">
                    <div class="bulat"></div>
                    <img class="foto shadow" src="/assets/img/foto.png" alt="Foto Desktop" />
                </div>
            </div>
        </div>

        <!-- Profil -->
        <div id="profil"></div>
        <div class="profil text-center">
            <h1>Profil</h1>
            <p>{!! $profilWeb->deskripsi_profil ?? 'Deskripsi profil belum tersedia.' !!}</p>
        </div>
    </div>



    <!-- End Profil -->

    <!-- Project -->
    <div id="project"></div>

    <div class="project container">
        <h1 class="text-center mb-5">Project</h1>

        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($portofolios->take(4) as $portofolio)
                <div class="col">
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
                    <div class="modal-dialog modal-fullscreen-sm-down">
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
                                @if ($bahasa)
                                    <h5 class="fw-bold mt-3 text-main">Bahasa Pemprograman</h5>
                                @endif

                                @foreach ($bahasa as $id)
                                    @php $bhs = $bahasas->where('id', $id)->first(); @endphp
                                    @if ($bhs)
                                        <img src="{{ asset('storage/' . $bhs->gambar) }}" alt="{{ $bhs->nama }}"
                                            height="30" class="rounded">
                                    @endif
                                @endforeach
                                @php $framework = json_decode($portofolio->framework_id, true); @endphp
                                @if ($framework)
                                    <h5 class="fw-bold mt-3 text-main">Framework</h5>
                                @endif

                                @foreach ($framework as $id)
                                    @php $frame = $frameworks->where('id', $id)->first(); @endphp
                                    @if ($frame)
                                        <img src="{{ asset('storage/' . $frame->gambar) }}" alt="{{ $frame->nama }}"
                                            height="30" class="rounded">
                                    @endif
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                        @if ($bahasa)
                            <h5 class="fw-bold mt-3 text-main">Bahasa Pemprograman</h5>
                        @endif

                        @foreach ($bahasa as $id)
                            @php $bhs = $bahasas->where('id', $id)->first(); @endphp
                            @if ($bhs)
                                <img src="{{ asset('storage/' . $bhs->gambar) }}" alt="{{ $bhs->nama }}"
                                    height="30" class="rounded">
                            @endif
                        @endforeach
                        @php $framework = json_decode($portofolio->framework_id, true); @endphp
                        @if ($framework)
                            <h5 class="fw-bold mt-3 text-main">Framework</h5>
                        @endif

                        @foreach ($framework as $id)
                            @php $frame = $frameworks->where('id', $id)->first(); @endphp
                            @if ($frame)
                                <img src="{{ asset('storage/' . $frame->gambar) }}" alt="{{ $frame->nama }}"
                                    height="30" class="rounded">
                            @endif
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
    <div style="margin-bottom: 200px" class="contact text-center">
        <h1>Contact</h1>

        <div class="d-flex justify-content-center">
            <div class="row mt-5">
                @if ($profilWeb->instagram)
                    <div style="margin: 0 30px" class="col fs-1">
                        <a href="{{ $profilWeb->instagram }}" class="text-white" target="_blank">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                @endif

                @if ($profilWeb->youtube)
                    <div style="margin: 0 30px" class="col fs-1">
                        <a href="{{ $profilWeb->youtube }}" class="text-white" target="_blank">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                @endif

                @if ($profilWeb->tiktok)
                    <div style="margin: 0 30px" class="col fs-1">
                        <a href="{{ $profilWeb->tiktok }}" class="text-white" target="_blank">
                            <i class="bi bi-tiktok"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- End Container -->
    </div>

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
