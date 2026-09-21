<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Film & TV Show</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#e50914',
                        darkBg: '#141414',
                        darkCard: '#1f1f1f'
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .card-poster {
            aspect-ratio: 2 / 3;
        }
    </style>
</head>

<body class="bg-darkBg text-gray-100 min-h-screen font-sans antialiased selection:bg-brand selection:text-white">

    <!-- Header Nav -->
    <header class="sticky top-0 z-40 bg-black/80 backdrop-blur-md border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 cursor-pointer" onclick="resetFilters()">
                <i class="fa-solid fa-play-circle text-brand text-2xl"></i>
                <span class="font-black text-xl tracking-wider text-white">STREAM<span
                        class="text-brand">HUB</span></span>
            </div>

            <!-- Search Input -->
            <div class="relative flex-1 max-w-md">
                <input type="text" id="searchInput" placeholder="Cari film atau serial TV..."
                    class="w-full bg-darkCard border border-gray-700 rounded-full pl-10 pr-10 py-1.5 text-xs text-white placeholder-gray-400 focus:outline-none focus:border-brand transition">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-400 text-xs"></i>
                <button id="clearSearchBtn" onclick="clearSearch()"
                    class="hidden absolute right-3 top-2 text-gray-400 hover:text-white text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Settings Button -->
            <button onclick="openApiKeyModal()"
                class="p-2 bg-darkCard hover:bg-gray-800 border border-gray-700 rounded-full text-gray-300 transition"
                title="Pengaturan API Key">
                <i class="fa-solid fa-gear text-xs"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-6">

        <!-- Filter Bar -->
        <div
            class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-darkCard p-3 rounded-xl border border-gray-800">

            <!-- Type Tabs -->
            <div class="flex bg-black/40 p-1 rounded-lg border border-gray-800 text-xs">
                <button id="tab-all" onclick="setFilterType('all')"
                    class="filter-tab px-3 py-1.5 rounded-md font-bold bg-brand text-white shadow">Semua</button>
                <button id="tab-movie" onclick="setFilterType('movie')"
                    class="filter-tab px-3 py-1.5 rounded-md font-bold text-gray-400">Movie</button>
                <button id="tab-tv" onclick="setFilterType('tv')"
                    class="filter-tab px-3 py-1.5 rounded-md font-bold text-gray-400">TV Show</button>
            </div>

            <!-- Dropdown Filters -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Filter Negara Asal -->
                <select id="countrySelect" onchange="applyFilters()"
                    class="bg-black/50 border border-gray-700 rounded-lg px-2.5 py-1.5 text-xs text-gray-200 focus:outline-none focus:border-brand">
                    <option value="">Semua Negara</option>
                    <option value="KR">Korea Selatan (KR)</option>
                    <option value="CN">Cina (CN)</option>
                    <option value="JP">Jepang (JP)</option>
                    <option value="US">Amerika Serikat (US)</option>
                    <option value="ID">Indonesia (ID)</option>
                    <option value="TH">Thailand (TH)</option>
                    <option value="GB">Inggris (GB)</option>
                </select>

                <select id="genreSelect" onchange="applyFilters()"
                    class="bg-black/50 border border-gray-700 rounded-lg px-2.5 py-1.5 text-xs text-gray-200 focus:outline-none focus:border-brand">
                    <option value="">Semua Genre</option>
                </select>

                <select id="sortSelect" onchange="applyFilters()"
                    class="bg-black/50 border border-gray-700 rounded-lg px-2.5 py-1.5 text-xs text-gray-200 focus:outline-none focus:border-brand">
                    <option value="popularity.desc">Paling Populer</option>
                    <option value="vote_average.desc">Rating Tertinggi</option>
                    <option value="primary_release_date.desc">Terbaru</option>
                </select>
            </div>
        </div>

        <!-- Section Title & Counter -->
        <div class="flex items-center justify-between mb-4">
            <h2 id="sectionTitle" class="text-sm font-bold text-white flex items-center gap-2">
                <span class="w-2 h-6 bg-brand rounded-full inline-block"></span> Trending & Populer
            </h2>
            <span id="resultsCount" class="text-[11px] text-gray-400"></span>
        </div>

        <!-- Media Grid -->
        <div id="mediaGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden flex flex-col items-center justify-center py-16 text-center">
            <i class="fa-solid fa-film text-5xl text-gray-600 mb-3"></i>
            <h3 class="text-base font-bold text-gray-300">Konten tidak ditemukan</h3>
            <p class="text-xs text-gray-500 mt-1">Coba gunakan kata kunci atau filter yang berbeda.</p>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="hidden flex justify-center items-center py-12">
            <div class="w-8 h-8 border-2 border-brand border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Load More Button -->
        <div id="loadMoreContainer" class="hidden flex justify-center mt-8">
            <button id="loadMoreBtn" onclick="loadMore()"
                class="px-5 py-2 bg-darkCard hover:bg-gray-800 border border-gray-700 text-xs font-bold text-white rounded-lg transition">
                Muat Lebih Banyak
            </button>
        </div>
    </main>

    <!-- Detail Player Modal -->
    <div id="detailModal"
        class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-2 sm:p-4 overflow-y-auto">
        <div
            class="bg-darkCard border border-gray-800 rounded-2xl max-w-4xl w-full overflow-hidden shadow-2xl relative my-auto">

            <!-- Close Button -->
            <button onclick="closeModal()"
                class="absolute top-3 right-3 z-10 w-8 h-8 bg-black/60 hover:bg-black rounded-full text-white flex items-center justify-center transition border border-gray-700">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Video Embed Player Container -->
            <div class="relative w-full aspect-video bg-black">
                <iframe id="playerIframe" class="w-full h-full border-0" allowfullscreen
                    allow="autoplay; encrypted-media"></iframe>
            </div>

            <!-- Modal Info Details -->
            <div class="p-4 sm:p-6">

                <!-- Controls & Servers -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-4 border-b border-gray-800">

                    <!-- Server Selectors -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[11px] font-bold text-gray-400 mr-1"><i class="fa-solid fa-server"></i>
                            Server:</span>
                        <button id="server-embedsu" onclick="switchServer('embedsu')"
                            class="server-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-brand text-white border border-brand transition">Embed.su</button>
                        <button id="server-autoembed" onclick="switchServer('autoembed')"
                            class="server-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-gray-800 text-gray-300 hover:text-white border border-gray-700 transition">AutoEmbed</button>
                        <button id="server-vidsrc" onclick="switchServer('vidsrc')"
                            class="server-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-gray-800 text-gray-300 hover:text-white border border-gray-700 transition">VidSrc</button>
                        <button id="server-vidlink" onclick="switchServer('vidlink')"
                            class="server-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-gray-800 text-gray-300 hover:text-white border border-gray-700 transition">VidLink</button>
                    </div>

                    <!-- TV Series Controls (Season & Episode) -->
                    <div id="tvControls" class="hidden flex items-center gap-2">
                        <select id="seasonSelect" onchange="onSeasonEpisodeChange()"
                            class="bg-black/50 border border-gray-700 rounded-lg px-2 py-1 text-xs text-white focus:outline-none focus:border-brand"></select>
                        <select id="episodeSelect" onchange="onSeasonEpisodeChange()"
                            class="bg-black/50 border border-gray-700 rounded-lg px-2 py-1 text-xs text-white focus:outline-none focus:border-brand"></select>
                    </div>
                </div>

                <!-- Title & Metadata -->
                <div class="flex gap-4 items-start">
                    <img id="modalPoster" src="" alt="Poster"
                        class="w-20 sm:w-24 rounded-lg border border-gray-800 object-cover aspect-[2/3] hidden sm:block">

                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span id="modalTypeBadge"
                                class="bg-brand/20 text-brand text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase">MOVIE</span>
                            <span id="modalCountryBadge"
                                class="bg-gray-800 text-gray-300 text-[10px] font-bold px-2 py-0.5 rounded-md border border-gray-700"></span>
                            <span id="modalYear" class="text-xs text-gray-400 font-medium"></span>
                            <span class="text-xs text-yellow-400 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-star text-[10px]"></i> <span id="modalRating"></span>
                            </span>
                        </div>

                        <h2 id="modalTitle" class="text-lg sm:text-xl font-black text-white mb-2"></h2>

                        <div id="modalGenres" class="flex flex-wrap gap-1.5 mb-3"></div>

                        <p id="modalOverview" class="text-xs text-gray-300 leading-relaxed line-clamp-4"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Key Modal Settings -->
    <div id="apiKeyModal"
        class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-darkCard border border-gray-800 rounded-xl max-w-md w-full p-5 shadow-xl">
            <h3 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
                <i class="fa-solid fa-key text-brand"></i> Pengaturan TMDB API Key
            </h3>
            <p class="text-xs text-gray-400 mb-4">Masukan v3 API Key TMDB milik Anda jika kunci standar tidak berfungsi
                atau mencapai limit pemakaian.</p>

            <input type="text" id="customApiKeyInput" placeholder="Masukkan API Key (v3)..."
                class="w-full bg-black/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-brand mb-4">

            <div class="flex justify-end gap-2">
                <button onclick="closeApiKeyModal()"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-400 hover:text-white transition">Batal</button>
                <button onclick="saveCustomApiKey()"
                    class="px-3 py-1.5 bg-brand hover:bg-red-700 rounded-lg text-xs font-bold text-white transition">Simpan</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT JAVASCRIPT -->
    <script>
        const DEFAULT_API_KEY = "dc2c8e573930c0284ae0de941ca207dc";
        const BASE_URL = "https://api.themoviedb.org/3";
        const IMAGE_BASE_URL = "https://image.tmdb.org/t/p/w500";

        let activeApiKey = localStorage.getItem("custom_tmdb_api_key") || DEFAULT_API_KEY;

        let currentFilterType = 'all';
        let currentGenre = '';
        let currentCountry = '';
        let currentSort = 'popularity.desc';
        let searchQuery = '';
        let currentPage = 1;
        let totalPages = 1;
        let isLoading = false;

        let activeMedia = null;
        let activeServer = 'embedsu';
        let activeSeason = 1;
        let activeEpisode = 1;

        let genreListMap = {};

        const mediaGrid = document.getElementById('mediaGrid');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const emptyState = document.getElementById('emptyState');
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        const searchInput = document.getElementById('searchInput');
        const genreSelect = document.getElementById('genreSelect');
        const countrySelect = document.getElementById('countrySelect');
        const sortSelect = document.getElementById('sortSelect');
        const sectionTitle = document.getElementById('sectionTitle');
        const resultsCount = document.getElementById('resultsCount');

        window.onload = async () => {
            await fetchGenres();
            fetchContent(true);
            setupSearchDebounce();
        };

        async function fetchGenres() {
            try {
                const [movieGenreRes, tvGenreRes] = await Promise.all([
                    fetch(`${BASE_URL}/genre/movie/list?api_key=${activeApiKey}&language=id-ID`),
                    fetch(`${BASE_URL}/genre/tv/list?api_key=${activeApiKey}&language=id-ID`)
                ]);

                const movieGenres = await movieGenreRes.json();
                const tvGenres = await tvGenreRes.json();

                const combinedGenres = {};
                (movieGenres.genres || []).forEach(g => combinedGenres[g.id] = g.name);
                (tvGenres.genres || []).forEach(g => combinedGenres[g.id] = g.name);

                genreListMap = combinedGenres;

                genreSelect.innerHTML = '<option value="">Semua Genre</option>';
                Object.keys(combinedGenres).sort((a, b) => combinedGenres[a].localeCompare(combinedGenres[b])).forEach(
                    id => {
                        genreSelect.innerHTML += `<option value="${id}">${combinedGenres[id]}</option>`;
                    });
            } catch (err) {
                console.error("Gagal memuat genre:", err);
            }
        }

        async function fetchContent(reset = false) {
            if (isLoading) return;
            if (reset) {
                currentPage = 1;
                mediaGrid.innerHTML = '';
                emptyState.classList.add('hidden');
            }

            isLoading = true;
            loadingSpinner.classList.remove('hidden');
            if (reset) loadMoreContainer.classList.add('hidden');

            try {
                let endpoint = '';
                let params = `api_key=${activeApiKey}&language=id-ID&page=${currentPage}`;

                if (searchQuery.trim() !== '') {
                    const searchType = currentFilterType === 'all' ? 'multi' : currentFilterType;
                    endpoint = `${BASE_URL}/search/${searchType}?${params}&query=${encodeURIComponent(searchQuery)}`;
                    sectionTitle.innerHTML =
                        `<span class="w-2 h-6 bg-brand rounded-full inline-block"></span> Hasil Pencarian: "${searchQuery}"`;
                } else {
                    let extraParams = '';
                    if (currentGenre) extraParams += `&with_genres=${currentGenre}`;
                    if (currentCountry) extraParams += `&with_origin_country=${currentCountry}`;

                    if (currentFilterType === 'movie') {
                        endpoint = `${BASE_URL}/discover/movie?${params}&sort_by=${currentSort}${extraParams}`;
                        sectionTitle.innerHTML =
                            `<span class="w-2 h-6 bg-brand rounded-full inline-block"></span> Daftar Film (Movie)`;
                    } else if (currentFilterType === 'tv') {
                        endpoint = `${BASE_URL}/discover/tv?${params}&sort_by=${currentSort}${extraParams}`;
                        sectionTitle.innerHTML =
                            `<span class="w-2 h-6 bg-brand rounded-full inline-block"></span> Serial / Drama TV`;
                    } else {
                        if (currentCountry || currentGenre) {
                            endpoint = `${BASE_URL}/discover/movie?${params}&sort_by=${currentSort}${extraParams}`;
                        } else {
                            endpoint = `${BASE_URL}/trending/all/week?${params}`;
                        }
                        sectionTitle.innerHTML =
                            `<span class="w-2 h-6 bg-brand rounded-full inline-block"></span> Trending & Populer`;
                    }
                }

                const response = await fetch(endpoint);
                const data = await response.json();

                if (!data.results || data.results.length === 0) {
                    if (reset) emptyState.classList.remove('hidden');
                    loadMoreContainer.classList.add('hidden');
                    resultsCount.innerText = '';
                } else {
                    totalPages = data.total_pages || 1;
                    resultsCount.innerText = `Total: ${data.total_results || data.results.length} item`;
                    renderCards(data.results);

                    if (currentPage < totalPages) {
                        loadMoreContainer.classList.remove('hidden');
                    } else {
                        loadMoreContainer.classList.add('hidden');
                    }
                }

            } catch (error) {
                console.error("Error fetching content:", error);
                if (reset) emptyState.classList.remove('hidden');
            } finally {
                isLoading = false;
                loadingSpinner.classList.add('hidden');
            }
        }

        function renderCards(items) {
            items.forEach(item => {
                const mediaType = item.media_type || (currentFilterType !== 'all' ? currentFilterType : (item
                    .first_air_date ? 'tv' : 'movie'));

                if (mediaType !== 'movie' && mediaType !== 'tv') return;

                const title = item.title || item.name || 'Tanpa Judul';
                const releaseDate = item.release_date || item.first_air_date || '';
                const year = releaseDate ? releaseDate.split('-')[0] : 'N/A';
                const rating = item.vote_average ? item.vote_average.toFixed(1) : 'N/A';
                const posterSrc = item.poster_path ? `${IMAGE_BASE_URL}${item.poster_path}` :
                    'https://placehold.co/500x750/181818/ffffff?text=No+Poster';

                // Menentukan Kode Negara
                const countryCode = (item.origin_country && item.origin_country.length > 0) ?
                    item.origin_country[0] :
                    (item.original_language ? item.original_language.toUpperCase() : 'N/A');

                const card = document.createElement('div');
                card.className =
                    'card-poster bg-darkCard border border-gray-800 rounded-xl overflow-hidden cursor-pointer flex flex-col relative group';
                card.onclick = () => openModal(item, mediaType, countryCode);

                card.innerHTML = `
          <div class="relative w-full h-full overflow-hidden bg-black/50">
            <img src="${posterSrc}" alt="${title}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

            <!-- Type Badge -->
            <span class="absolute top-2 left-2 ${mediaType === 'movie' ? 'bg-brand' : 'bg-blue-600'} text-white text-[9px] font-extrabold px-2 py-0.5 rounded shadow">
              ${mediaType === 'movie' ? 'MOVIE' : 'TV SHOW'}
            </span>

            <!-- Rating Badge -->
            <span class="absolute top-2 right-2 bg-black/70 backdrop-blur-md text-yellow-400 text-[10px] font-bold px-1.5 py-0.5 rounded border border-yellow-500/30 flex items-center gap-1">
              <i class="fa-solid fa-star text-[9px]"></i> ${rating}
            </span>

            <!-- Country Badge -->
            <span class="absolute bottom-2 left-2 bg-black/70 backdrop-blur-md text-gray-200 text-[9px] font-bold px-1.5 py-0.5 rounded border border-gray-700">
              <i class="fa-solid fa-globe text-[8px] mr-1"></i>${countryCode}
            </span>

            <!-- Hover Play Overlay -->
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
              <div class="w-12 h-12 bg-brand text-white rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-play ml-1 text-lg"></i>
              </div>
            </div>
          </div>

          <div class="p-3 bg-darkCard flex flex-col justify-between flex-grow">
            <h3 class="text-xs font-bold text-white line-clamp-1 group-hover:text-brand transition-colors">${title}</h3>
            <p class="text-[10px] text-gray-400 mt-1 font-medium">${year}</p>
          </div>
        `;

                mediaGrid.appendChild(card);
            });
        }

        function setFilterType(type) {
            currentFilterType = type;

            document.querySelectorAll('.filter-tab').forEach(btn => {
                btn.classList.remove('bg-brand', 'text-white', 'shadow');
                btn.classList.add('text-gray-400');
            });

            const activeBtn = document.getElementById(`tab-${type}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-brand', 'text-white', 'shadow');
                activeBtn.classList.remove('text-gray-400');
            }

            fetchContent(true);
        }

        function applyFilters() {
            currentGenre = genreSelect.value;
            currentCountry = countrySelect.value;
            currentSort = sortSelect.value;
            fetchContent(true);
        }

        function resetFilters() {
            currentGenre = '';
            currentCountry = '';
            currentSort = 'popularity.desc';
            searchQuery = '';
            searchInput.value = '';
            document.getElementById('clearSearchBtn').classList.add('hidden');
            genreSelect.value = '';
            countrySelect.value = '';
            sortSelect.value = 'popularity.desc';
            setFilterType('all');
        }

        let searchTimeout;

        function setupSearchDebounce() {
            searchInput.addEventListener('input', (e) => {
                const val = e.target.value;
                const clearBtn = document.getElementById('clearSearchBtn');

                if (val.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = val;
                    fetchContent(true);
                }, 500);
            });
        }

        function clearSearch() {
            searchInput.value = '';
            searchQuery = '';
            document.getElementById('clearSearchBtn').classList.add('hidden');
            fetchContent(true);
        }

        function loadMore() {
            if (currentPage < totalPages) {
                currentPage++;
                fetchContent(false);
            }
        }

        async function openModal(item, type, countryCode) {
            activeMedia = item;
            activeMedia.type = type;
            activeSeason = 1;
            activeEpisode = 1;

            const title = item.title || item.name || 'Tanpa Judul';
            const releaseDate = item.release_date || item.first_air_date || '';
            const year = releaseDate ? releaseDate.split('-')[0] : 'N/A';
            const rating = item.vote_average ? item.vote_average.toFixed(1) : 'N/A';
            const posterSrc = item.poster_path ? `${IMAGE_BASE_URL}${item.poster_path}` :
                'https://placehold.co/500x750/181818/ffffff?text=No+Poster';

            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalYear').innerText = year;
            document.getElementById('modalRating').innerText = rating;
            document.getElementById('modalCountryBadge').innerText = countryCode || 'N/A';
            document.getElementById('modalOverview').innerText = item.overview ||
                'Deskripsi belum tersedia untuk judul ini.';
            document.getElementById('modalPoster').src = posterSrc;

            const typeBadge = document.getElementById('modalTypeBadge');
            typeBadge.innerText = type === 'movie' ? 'MOVIE' : 'TV SHOW';
            typeBadge.className = type === 'movie' ?
                'bg-brand/20 text-brand text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border border-brand/30' :
                'bg-blue-500/20 text-blue-400 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border border-blue-500/30';

            const modalGenres = document.getElementById('modalGenres');
            modalGenres.innerHTML = '';
            if (item.genre_ids) {
                item.genre_ids.forEach(gid => {
                    if (genreListMap[gid]) {
                        modalGenres.innerHTML +=
                            `<span class="bg-gray-800 text-gray-300 text-[10px] px-2 py-0.5 rounded-md border border-gray-700 font-medium">${genreListMap[gid]}</span>`;
                    }
                });
            }

            const tvControls = document.getElementById('tvControls');
            if (type === 'tv') {
                tvControls.classList.remove('hidden');
                await populateSeasons(item.id);
            } else {
                tvControls.classList.add('hidden');
            }

            switchServer('embedsu');

            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        async function populateSeasons(tvId) {
            const seasonSelect = document.getElementById('seasonSelect');
            const episodeSelect = document.getElementById('episodeSelect');
            seasonSelect.innerHTML = '';
            episodeSelect.innerHTML = '';

            try {
                const res = await fetch(`${BASE_URL}/tv/${tvId}?api_key=${activeApiKey}&language=id-ID`);
                const tvDetails = await res.json();

                const seasons = tvDetails.seasons || [];
                seasons.forEach(s => {
                    if (s.season_number > 0) {
                        seasonSelect.innerHTML +=
                            `<option value="${s.season_number}">Season ${s.season_number}</option>`;
                    }
                });

                if (seasons.length === 0) {
                    seasonSelect.innerHTML = `<option value="1">Season 1</option>`;
                }

                populateEpisodes(1);

            } catch (err) {
                seasonSelect.innerHTML = `<option value="1">Season 1</option>`;
                populateEpisodes(1);
            }
        }

        function populateEpisodes(seasonNum) {
            const episodeSelect = document.getElementById('episodeSelect');
            episodeSelect.innerHTML = '';
            for (let i = 1; i <= 24; i++) {
                episodeSelect.innerHTML += `<option value="${i}">Episode ${i}</option>`;
            }
        }

        function onSeasonEpisodeChange() {
            activeSeason = document.getElementById('seasonSelect').value || 1;
            activeEpisode = document.getElementById('episodeSelect').value || 1;
            updatePlayerUrl();
        }

        function switchServer(serverKey) {
            activeServer = serverKey;

            document.querySelectorAll('.server-btn').forEach(btn => {
                btn.className =
                    "server-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-gray-800 text-gray-300 hover:text-white border border-gray-700 transition";
            });

            const activeBtn = document.getElementById(`server-${serverKey}`);
            if (activeBtn) {
                activeBtn.className =
                    "server-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-brand text-white border border-brand transition";
            }

            updatePlayerUrl();
        }

        function updatePlayerUrl() {
            if (!activeMedia) return;

            const id = activeMedia.id;
            const type = activeMedia.type;
            const iframe = document.getElementById('playerIframe');
            let finalUrl = '';

            if (type === 'movie') {
                switch (activeServer) {
                    case 'embedsu':
                        finalUrl = `https://embed.su/embed/movie/${id}`;
                        break;
                    case 'autoembed':
                        finalUrl = `https://player.autoembed.cc/embed/movie/${id}`;
                        break;
                    case 'vidsrc':
                        finalUrl = `https://vidsrc.to/embed/movie/${id}`;
                        break;
                    case 'vidlink':
                        finalUrl = `https://vidlink.pro/movie/${id}`;
                        break;
                    default:
                        finalUrl = `https://embed.su/embed/movie/${id}`;
                }
            } else {
                switch (activeServer) {
                    case 'embedsu':
                        finalUrl = `https://embed.su/embed/tv/${id}/${activeSeason}/${activeEpisode}`;
                        break;
                    case 'autoembed':
                        finalUrl = `https://player.autoembed.cc/embed/tv/${id}/${activeSeason}/${activeEpisode}`;
                        break;
                    case 'vidsrc':
                        finalUrl = `https://vidsrc.to/embed/tv/${id}/${activeSeason}/${activeEpisode}`;
                        break;
                    case 'vidlink':
                        finalUrl = `https://vidlink.pro/tv/${id}/${activeSeason}/${activeEpisode}`;
                        break;
                    default:
                        finalUrl = `https://embed.su/embed/tv/${id}/${activeSeason}/${activeEpisode}`;
                }
            }

            iframe.src = finalUrl;
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('playerIframe').src = '';
            document.body.style.overflow = 'auto';
            activeMedia = null;
        }

        function openApiKeyModal() {
            document.getElementById('customApiKeyInput').value = localStorage.getItem("custom_tmdb_api_key") || '';
            document.getElementById('apiKeyModal').classList.remove('hidden');
        }

        function closeApiKeyModal() {
            document.getElementById('apiKeyModal').classList.add('hidden');
        }

        function saveCustomApiKey() {
            const val = document.getElementById('customApiKeyInput').value.trim();
            if (val) {
                localStorage.setItem("custom_tmdb_api_key", val);
                activeApiKey = val;
            } else {
                localStorage.removeItem("custom_tmdb_api_key");
                activeApiKey = DEFAULT_API_KEY;
            }
            closeApiKeyModal();
            fetchGenres();
            fetchContent(true);
        }
    </script>
</body>

</html>
