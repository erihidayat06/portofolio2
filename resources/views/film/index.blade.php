<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineStream - Streaming Portal Movie & Drama</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#e50914',
                        brandHover: '#b80710',
                        darkBg: '#0f0f0f',
                        darkCard: '#181818',
                        darkNav: '#121212'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f0f0f;
        }

        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #e50914;
        }

        /* Modal Backdrop Blur */
        .backdrop-blur-md {
            backdrop-filter: blur(12px);
        }

        /* Card Aspect Ratio & Hover Effects */
        .card-poster {
            aspect-ratio: 2 / 3;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-poster:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 24px rgba(229, 9, 20, 0.25);
        }

        /* Responsive Iframe Container */
        .responsive-iframe {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .responsive-iframe iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>

<body class="bg-darkBg text-gray-100 font-sans min-h-screen flex flex-col selection:bg-brand selection:text-white">

    <!-- HEADER & NAVBAR -->
    <header class="sticky top-0 z-40 bg-darkNav/90 backdrop-blur-md border-b border-gray-800/80 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

            <!-- Logo -->
            <a href="#" onclick="resetFilters()" class="flex items-center gap-2 group">
                <i class="fa-solid fa-play-circle text-brand text-3xl group-hover:scale-110 transition-transform"></i>
                <span
                    class="text-2xl font-extrabold tracking-wider bg-gradient-to-r from-brand to-red-500 bg-clip-text text-transparent">CINESTREAM</span>
            </a>

            <!-- Search Input -->
            <div class="flex-1 max-w-md relative">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari film, drama Korea, anime..."
                        class="w-full bg-darkCard text-white placeholder-gray-400 text-sm rounded-full pl-10 pr-10 py-2.5 border border-gray-700/60 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-gray-400 text-sm"></i>
                    <button id="clearSearchBtn" onclick="clearSearch()"
                        class="hidden absolute right-3.5 top-3 text-gray-400 hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-grow w-full">

        <!-- FILTERS & SORTING BAR -->
        <section class="bg-darkCard/80 border border-gray-800 p-4 rounded-2xl mb-8 shadow-lg backdrop-blur-sm">
            <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">

                <!-- Type Tabs -->
                <div class="flex bg-darkBg p-1 rounded-xl border border-gray-800 self-start md:self-auto">
                    <button onclick="setFilterType('all')" id="tab-all"
                        class="filter-tab px-4 py-1.5 rounded-lg text-xs font-semibold transition-all bg-brand text-white shadow">
                        Semua
                    </button>
                    <button onclick="setFilterType('movie')" id="tab-movie"
                        class="filter-tab px-4 py-1.5 rounded-lg text-xs font-semibold text-gray-400 hover:text-white transition-all">
                        <i class="fa-solid fa-film mr-1"></i> Movie
                    </button>
                    <button onclick="setFilterType('tv')" id="tab-tv"
                        class="filter-tab px-4 py-1.5 rounded-lg text-xs font-semibold text-gray-400 hover:text-white transition-all">
                        <i class="fa-solid fa-tv mr-1"></i> Drama / TV
                    </button>
                </div>

                <!-- Dropdown Filters -->
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-3">

                    <!-- Genre Selector -->
                    <div class="relative">
                        <select id="genreSelect" onchange="applyFilters()"
                            class="w-full sm:w-auto appearance-none bg-darkBg border border-gray-700 text-gray-200 text-xs rounded-xl px-3.5 py-2 pr-8 focus:outline-none focus:border-brand cursor-pointer">
                            <option value="">Semua Genre</option>
                            <!-- Filled via JS -->
                        </select>
                        <i
                            class="fa-solid fa-chevron-down absolute right-3 top-3 text-xs text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Sort Selector -->
                    <div class="relative">
                        <select id="sortSelect" onchange="applyFilters()"
                            class="w-full sm:w-auto appearance-none bg-darkBg border border-gray-700 text-gray-200 text-xs rounded-xl px-3.5 py-2 pr-8 focus:outline-none focus:border-brand cursor-pointer">
                            <option value="popularity.desc">Paling Populer</option>
                            <option value="vote_average.desc">Rating Tertinggi</option>
                            <option value="primary_release_date.desc">Rilis Terbaru</option>
                        </select>
                        <i
                            class="fa-solid fa-chevron-down absolute right-3 top-3 text-xs text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Dropdown Filter Negara -->
                    <div class="filter-group">
                        <select id="countrySelect" onchange="applyFilters()"
                            class="w-full sm:w-auto appearance-none bg-darkBg border border-gray-700 text-gray-200 text-xs rounded-xl px-3.5 py-2 pr-8 focus:outline-none focus:border-brand cursor-pointer">
                            <option value="">Semua Negara</option>
                        </select>
                    </div>

                    <!-- Reset Filter Button -->
                    <button onclick="resetFilters()"
                        class="col-span-2 sm:col-span-1 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs px-3.5 py-2 rounded-xl border border-gray-700 transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-rotate-left text-gray-400"></i>
                        <span>Reset</span>
                    </button>
                </div>

            </div>
        </section>

        <!-- SECTION TITLE -->
        <div class="flex items-center justify-between mb-6">
            <h1 id="sectionTitle"
                class="text-xl sm:text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span class="w-2 h-6 bg-brand rounded-full inline-block"></span>
                Konten Populer
            </h1>
            <span id="resultsCount" class="text-xs text-gray-400 font-medium"></span>
        </div>

        <!-- CONTENT GRID -->
        <section id="mediaGrid"
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
            <!-- Media Cards inserted via JavaScript -->
        </section>

        <!-- LOADING & ERROR STATES -->
        <div id="loadingSpinner" class="hidden my-12 flex flex-col items-center justify-center">
            <div class="w-10 h-10 border-4 border-brand border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs text-gray-400 mt-3 font-medium">Memuat konten...</p>
        </div>

        <div id="emptyState" class="hidden my-16 text-center py-8">
            <i class="fa-solid fa-film-slash text-5xl text-gray-600 mb-3"></i>
            <h3 class="text-lg font-semibold text-gray-300">Tidak Ada Hasil Ditemukan</h3>
            <p class="text-xs text-gray-500 mt-1">Coba kata kunci lain atau ubah filter pencarian Anda.</p>
        </div>

        <!-- LOAD MORE BUTTON -->
        <div id="loadMoreContainer" class="hidden my-12 text-center">
            <button id="loadMoreBtn" onclick="loadMore()"
                class="px-8 py-3 bg-darkCard hover:bg-brand text-white text-sm font-semibold rounded-full border border-gray-700 hover:border-brand shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                <i class="fa-solid fa-circle-chevron-down mr-2"></i> Muat Lebih Banyak
            </button>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-darkNav border-t border-gray-800/80 mt-auto py-8 text-center text-xs text-gray-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>© 2026 CineStream Portal. All rights reserved.</p>
            <p class="mt-2 text-gray-600">Aplikasi ini menggunakan API TMDB dan embed provider eksternal untuk tujuan
                pengujian media.</p>
        </div>
    </footer>

    <!-- MEDIA DETAIL & PLAYER MODAL -->
    <div id="detailModal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/80 backdrop-blur-md flex items-center justify-center p-3 sm:p-6 transition-opacity duration-300">

        <div
            class="bg-darkCard border border-gray-800 w-full max-w-4xl rounded-2xl overflow-hidden shadow-2xl relative my-auto text-gray-200">

            <!-- Close Button -->
            <button onclick="closeModal()"
                class="absolute top-3 right-3 z-20 w-9 h-9 bg-black/60 hover:bg-brand text-white rounded-full flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="flex flex-col md:flex-row">

                <!-- Poster Left -->
                <div class="w-full md:w-1/3 relative bg-black/40 flex items-center justify-center">
                    <img id="modalPoster" src="" alt="Poster"
                        class="w-full h-full object-cover max-h-[350px] md:max-h-full">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-darkCard via-transparent to-transparent md:hidden">
                    </div>
                </div>





            </div>

        </div>

    </div>
    </div>



    <!-- JAVASCRIPT LOGIC -->
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

                // Buat elemen utama sebagai elemen <a>
                const card = document.createElement('a');
                card.href = `/film/show?type=${mediaType}&id=${item.id}`;
                card.className =
                    'card-poster bg-darkCard border border-gray-800 rounded-xl overflow-hidden cursor-pointer flex flex-col relative group block';

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




        window.onload = async () => {
            await Promise.all([fetchGenres(), fetchCountries()]);
            fetchContent(true);
            setupSearchDebounce();
        };

        // Fungsi untuk mengambil daftar negara dari API TMDB
        // Fungsi untuk mengambil dan mengurutkan daftar negara dari API TMDB
        async function fetchCountries() {
            try {
                const response = await fetch(
                    `${BASE_URL}/configuration/countries?api_key=${activeApiKey}&language=id-ID`);
                const countries = await response.json();

                // Urutkan alfabetis dari A ke Z berdasarkan nama negara
                countries.sort((a, b) => {
                    const nameA = a.native_name || a.english_name;
                    const nameB = b.native_name || b.english_name;
                    return nameA.localeCompare(nameB);
                });

                const countrySelect = document.getElementById('countrySelect');
                countrySelect.innerHTML = '<option value="">Semua Negara</option>';

                countries.forEach(c => {
                    const name = c.native_name || c.english_name;
                    countrySelect.innerHTML +=
                        `<option value="${c.iso_3166_1}">${name} (${c.iso_3166_1})</option>`;
                });
            } catch (err) {
                console.error("Gagal memuat daftar negara:", err);
            }
        }
    </script>
</body>

</html>
