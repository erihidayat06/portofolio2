<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stream Player Dedicated</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #0d0d0d;
            color: #fff;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        h1 {
            color: #e50914;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Control Panel */
        .control-panel {
            background-color: #181818;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #2a2a2a;
            display: flex;
            gap: 15px;
            justify-content: flex-start;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .input-group label {
            font-size: 0.85em;
            color: #aaa;
        }

        .control-panel select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #333;
            background-color: #262626;
            color: #fff;
            font-size: 0.95em;
            outline: none;
        }

        .control-panel select:focus {
            border-color: #e50914;
        }

        .btn-play {
            padding: 8px 20px;
            background-color: #e50914;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 0.95em;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-play:hover {
            background-color: #b80710;
        }

        /* Layout Grid Utama (Kiri & Kanan) */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
        }

        @media (max-width: 900px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Video & Details Section (Kiri) */
        .left-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .player-card {
            background-color: #181818;
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #2a2a2a;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7);
        }

        .iframe-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            background-color: #000;
            border-radius: 8px;
            overflow: hidden;
        }

        .iframe-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Episode Grid System */
        .episode-panel {
            background-color: #181818;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #2a2a2a;
        }

        .episode-panel h3 {
            margin: 0 0 12px 0;
            font-size: 1em;
            color: #ddd;
        }

        .episode-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            max-height: 150px;
            overflow-y: auto;
        }

        .btn-ep {
            padding: 8px 14px;
            background-color: #262626;
            border: 1px solid #333;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn-ep:hover {
            background-color: #333;
            border-color: #e50914;
        }

        .btn-ep.active {
            background-color: #e50914;
            border-color: #e50914;
        }

        /* Detail Informasi Film / Series */
        .details-card {
            background-color: #181818;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #2a2a2a;
            display: flex;
            gap: 20px;
        }

        .details-poster {
            width: 140px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .details-info {
            flex-grow: 1;
        }

        .details-info h2 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #fff;
        }

        .meta-tags {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.85em;
            color: #aaa;
            flex-wrap: wrap;
        }

        .badge {
            background-color: #e50914;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
        }

        .cast-info {
            font-size: 0.9em;
            color: #ccc;
            margin-bottom: 12px;
        }

        .overview-text {
            font-size: 0.9em;
            color: #aaa;
            line-height: 1.5;
            margin: 0;
        }

        /* Section Rekomendasi di Bawah Player (Sejenis / Related) */
        .related-section {
            background-color: #181818;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #2a2a2a;
        }

        .section-title {
            font-size: 1.2em;
            margin: 0 0 15px 0;
            color: #fff;
            border-left: 4px solid #e50914;
            padding-left: 10px;
        }

        .horizontal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
        }

        .movie-card {
            background-color: #262626;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s;
            border: 1px solid transparent;
        }

        .movie-card:hover {
            transform: translateY(-4px);
            border-color: #e50914;
        }

        .movie-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .movie-card .title {
            font-size: 0.8em;
            padding: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }

        /* Sidebar Rekomendasi Bulan Ini (Kanan) */
        .sidebar {
            background-color: #181818;
            border-radius: 10px;
            padding: 15px;
            border: 1px solid #2a2a2a;
            height: fit-content;
        }

        .sidebar-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .sidebar-item {
            display: flex;
            gap: 10px;
            background-color: #262626;
            border-radius: 6px;
            padding: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar-item:hover {
            background-color: #333;
        }

        .sidebar-item img {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
        }

        .sidebar-item-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .sidebar-item-info .title {
            font-size: 0.85em;
            font-weight: bold;
            color: #fff;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sidebar-item-info .rating {
            font-size: 0.75em;
            color: #f39c12;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Stream Player Dedicated</h1>

        <!-- Control Panel -->
        <div class="control-panel">
            <div class="input-group movie-only">
                <label for="movie-server">Server Movie</label>
                <select id="movie-server" onchange="loadPlayer()">
                    <option value="2embed-vcr" selected>2Embed (VCR)</option>
                    <option value="2embed-videm">2Embed (VidEm)</option>
                    <option value="vidsrcme">VidSrc.me</option>
                    <option value="vidsrcto">VidSrc.to</option>
                </select>
            </div>

            <div class="input-group tv-only">
                <label for="tv-server">Server TV</label>
                <select id="tv-server" onchange="loadPlayer()">
                    <option value="2embed-vcr" selected>2Embed (VCR)</option>
                </select>
            </div>

            <div class="input-group tv-only">
                <label for="season-select">Season</label>
                <select id="season-select" onchange="fetchEpisodes()">
                    <option value="1">Season 1</option>
                </select>
            </div>

            <button class="btn-play" onclick="loadPlayer()">Putar Video</button>
        </div>

        <!-- Main Layout (Kiri & Kanan) -->
        <div class="main-layout">
            <!-- Sisi Kiri: Video, Panel Episode, Info Cast/Judul, & Rekomendasi Terkait -->
            <div class="left-content">
                <div class="player-card">
                    <div class="iframe-wrapper">
                        <iframe id="stream-player" allowfullscreen scrolling="no"
                            allow="autoplay; encrypted-media; picture-in-picture"></iframe>
                    </div>
                </div>

                <div class="episode-panel tv-only" id="episode-panel">
                    <h3>Pilih Episode</h3>
                    <div class="episode-grid" id="episode-grid"></div>
                </div>

                <!-- Info Detail Film/Series (Judul, Cast, Sinopsis, dsb) -->
                <div class="details-card">
                    <img id="detail-poster" class="details-poster" src="" alt="Poster">
                    <div class="details-info">
                        <h2 id="detail-title">Memuat Judul...</h2>
                        <div class="meta-tags">
                            <span class="badge" id="detail-rating">0.0</span>
                            <span id="detail-release">-</span>
                            <span id="detail-genres">-</span>
                        </div>
                        <div class="cast-info" id="detail-cast"><strong>Pemeran:</strong> Memuat data pemeran...</div>
                        <p class="overview-text" id="detail-overview">Memuat sinopsis...</p>
                    </div>
                </div>

                <!-- Rekomendasi Terkait (Sesuai dengan yang ditonton sekarang) -->
                <div class="related-section">
                    <h3 class="section-title">Rekomendasi Terkait</h3>
                    <div class="horizontal-grid" id="related-grid"></div>
                </div>
            </div>

            <!-- Sisi Kanan: Rekomendasi Bulan Ini -->
            <div class="sidebar">
                <h3 class="section-title">Rekomendasi Bulan Ini</h3>
                <div class="sidebar-list" id="monthly-grid"></div>
            </div>
        </div>
    </div>

    <script>
        const TMDB_API_KEY = "dc2c8e573930c0284ae0de941ca207dc";
        let currentTmdbId = "";
        let currentEpisode = 1;
        let targetEpisodeFromUrl = null;
        let contentType = "movie";

        function initFromUrlParams() {
            const urlParams = new URLSearchParams(window.location.search);
            const typeParam = urlParams.get("type");
            const idParam = urlParams.get("id") || urlParams.get("tmdb");
            const seasonParam = urlParams.get("season") || urlParams.get("s");
            const episodeParam = urlParams.get("episode") || urlParams.get("e");

            if (typeParam) contentType = typeParam.toLowerCase();
            if (idParam) currentTmdbId = idParam;
            if (episodeParam) targetEpisodeFromUrl = parseInt(episodeParam, 10);

            toggleTypeInputs(seasonParam);
            loadMediaDetails();
            fetchMonthlyRecommendations();
            fetchRelatedRecommendations();
        }

        function toggleTypeInputs(targetSeason = null) {
            const tvInputs = document.querySelectorAll(".tv-only");
            const movieInputs = document.querySelectorAll(".movie-only");

            tvInputs.forEach((el) => el.style.display = contentType === "tv" ? "flex" : "none");
            movieInputs.forEach((el) => el.style.display = contentType === "movie" ? "flex" : "none");

            if (contentType === "tv") {
                fetchSeasons(targetSeason);
            } else {
                loadPlayer();
            }
        }

        /* Memuat Detail Informasi (Judul, Cast, Genre, Sinopsis, Poster) */
        async function loadMediaDetails() {
            if (!currentTmdbId) return;
            const endpoint =
                `https://api.themoviedb.org/3/${contentType}/${currentTmdbId}?api_key=${TMDB_API_KEY}&append_to_response=credits&language=id-ID`;

            try {
                const res = await fetch(endpoint);
                const data = await res.json();

                document.getElementById("detail-title").textContent = data.title || data.name || "Tanpa Judul";
                document.getElementById("detail-overview").textContent = data.overview || "Sinopsis tidak tersedia.";
                document.getElementById("detail-rating").textContent =
                    `⭐ ${data.vote_average ? data.vote_average.toFixed(1) : "N/A"}`;
                document.getElementById("detail-release").textContent = data.release_date || data.first_air_date || "-";
                document.getElementById("detail-poster").src = data.poster_path ?
                    `https://image.tmdb.org/t/p/w300${data.poster_path}` :
                    "https://via.placeholder.com/140x210?text=No+Image";

                const genres = data.genres ? data.genres.map(g => g.name).join(", ") : "-";
                document.getElementById("detail-genres").textContent = genres;

                if (data.credits && data.credits.cast) {
                    const castList = data.credits.cast.slice(0, 5).map(c => c.name).join(", ");
                    document.getElementById("detail-cast").innerHTML =
                        `<strong>Pemeran:</strong> ${castList || "Data pemeran tidak tersedia"}`;
                }
            } catch (err) {
                console.error("Gagal memuat detail TMDB:", err);
            }
        }

        /* Memuat Rekomendasi Terkait (Sesuai dengan yang ditonton) */
        async function fetchRelatedRecommendations() {
            if (!currentTmdbId) return;
            const endpoint =
                `https://api.themoviedb.org/3/${contentType}/${currentTmdbId}/recommendations?api_key=${TMDB_API_KEY}&language=id-ID`;

            try {
                const res = await fetch(endpoint);
                const data = await res.json();
                const grid = document.getElementById("related-grid");
                grid.innerHTML = "";

                if (data.results && data.results.length > 0) {
                    data.results.slice(0, 12).forEach(item => {
                        const title = item.title || item.name;
                        const poster = item.poster_path ? `https://image.tmdb.org/t/p/w185${item.poster_path}` :
                            "https://via.placeholder.com/130x180?text=No+Image";

                        const card = document.createElement("div");
                        card.className = "movie-card";
                        card.innerHTML = `
                            <img src="${poster}" alt="${title}">
                            <div class="title">${title}</div>
                        `;
                        card.onclick = () => {
                            window.location.search = `?type=${contentType}&id=${item.id}`;
                        };
                        grid.appendChild(card);
                    });
                } else {
                    grid.innerHTML = "<p style='color:#aaa; font-size:0.9em;'>Tidak ada rekomendasi terkait.</p>";
                }
            } catch (err) {
                console.error("Gagal memuat rekomendasi terkait:", err);
            }
        }

        /* Memuat Rekomendasi Bulan Ini (Trending/Popular) */
        async function fetchMonthlyRecommendations() {
            const endpoint =
                `https://api.themoviedb.org/3/trending/${contentType}/week?api_key=${TMDB_API_KEY}&language=id-ID`;

            try {
                const res = await fetch(endpoint);
                const data = await res.json();
                const sidebar = document.getElementById("monthly-grid");
                sidebar.innerHTML = "";

                if (data.results) {
                    data.results.slice(0, 7).forEach(item => {
                        const title = item.title || item.name;
                        const poster = item.poster_path ? `https://image.tmdb.org/t/p/w92${item.poster_path}` :
                            "https://via.placeholder.com/50x70?text=No+Image";
                        const rating = item.vote_average ? item.vote_average.toFixed(1) : "N/A";

                        const div = document.createElement("div");
                        div.className = "sidebar-item";
                        div.innerHTML = `
                            <img src="${poster}" alt="${title}">
                            <div class="sidebar-item-info">
                                <div class="title">${title}</div>
                                <div class="rating">⭐ ${rating}</div>
                            </div>
                        `;
                        div.onclick = () => {
                            window.location.search = `?type=${contentType}&id=${item.id}`;
                        };
                        sidebar.appendChild(div);
                    });
                }
            } catch (err) {
                console.error("Gagal memuat rekomendasi bulan ini:", err);
            }
        }

        /* Pengelolaan TV Seasons & Episodes */
        async function fetchSeasons(targetSeason = null) {
            const seasonSelect = document.getElementById("season-select");
            if (!currentTmdbId) return;

            try {
                const res = await fetch(
                    `https://api.themoviedb.org/3/tv/${currentTmdbId}?api_key=${TMDB_API_KEY}&language=id-ID`);
                if (!res.ok) throw new Error("Gagal mengambil data series");

                const data = await res.json();
                seasonSelect.innerHTML = "";

                const validSeasons = data.seasons.filter((s) => s.season_number > 0);
                validSeasons.forEach((s) => {
                    const option = document.createElement("option");
                    option.value = s.season_number;
                    option.textContent = `Season ${s.season_number} (${s.episode_count} Ep)`;
                    seasonSelect.appendChild(option);
                });

                if (validSeasons.length > 0) {
                    if (targetSeason && validSeasons.some(s => s.season_number == targetSeason)) {
                        seasonSelect.value = targetSeason;
                    } else {
                        seasonSelect.value = validSeasons[0].season_number;
                    }
                    fetchEpisodes();
                }
            } catch (err) {
                console.error("Error TMDB:", err);
                seasonSelect.innerHTML = '<option value="1">Season 1</option>';
                renderFallbackEpisodes(10);
            }
        }

        async function fetchEpisodes() {
            const season = document.getElementById("season-select").value;

            try {
                const res = await fetch(
                    `https://api.themoviedb.org/3/tv/${currentTmdbId}/season/${season}?api_key=${TMDB_API_KEY}&language=id-ID`
                );
                if (!res.ok) throw new Error("Gagal mengambil data episode");

                const data = await res.json();
                renderEpisodeButtons(data.episodes.length);
            } catch (err) {
                console.error("Error TMDB Episode:", err);
                renderFallbackEpisodes(10);
            }
        }

        function renderEpisodeButtons(totalEpisodes) {
            const grid = document.getElementById("episode-grid");
            grid.innerHTML = "";

            currentEpisode = (targetEpisodeFromUrl && targetEpisodeFromUrl <= totalEpisodes) ? targetEpisodeFromUrl : 1;
            targetEpisodeFromUrl = null;

            for (let i = 1; i <= totalEpisodes; i++) {
                const btn = document.createElement("button");
                btn.className = `btn-ep ${i === currentEpisode ? "active" : ""}`;
                btn.textContent = `Ep ${i}`;
                btn.onclick = () => selectEpisode(i, btn);
                grid.appendChild(btn);
            }

            loadPlayer();
        }

        function renderFallbackEpisodes(count) {
            const grid = document.getElementById("episode-grid");
            grid.innerHTML = "";
            currentEpisode = targetEpisodeFromUrl || 1;
            targetEpisodeFromUrl = null;

            for (let i = 1; i <= count; i++) {
                const btn = document.createElement("button");
                btn.className = `btn-ep ${i === currentEpisode ? "active" : ""}`;
                btn.textContent = `Ep ${i}`;
                btn.onclick = () => selectEpisode(i, btn);
                grid.appendChild(btn);
            }
            loadPlayer();
        }

        function selectEpisode(epNumber, btnElement) {
            currentEpisode = epNumber;
            document.querySelectorAll(".btn-ep").forEach((b) => b.classList.remove("active"));
            if (btnElement) btnElement.classList.add("active");
            loadPlayer();
        }

        /* Player Embed Logic */
        function loadPlayer() {
            if (!currentTmdbId) return;

            let playerUrl = "";

            if (contentType === "movie") {
                const movieServer = document.getElementById("movie-server").value;
                switch (movieServer) {
                    case "2embed-videm":
                        playerUrl = `https://videm.xyz/embed/movie/${currentTmdbId}`;
                        break;
                    case "vidsrcme":
                        playerUrl = `https://vidsrc.me/embed/movie?tmdb=${currentTmdbId}`;
                        break;
                    case "vidsrcto":
                        playerUrl = `https://vidsrc.to/embed/movie/${currentTmdbId}`;
                        break;
                    case "2embed-vcr":
                    default:
                        playerUrl = `https://streamsrcs.2embed.cc/vcr?tmdb=${currentTmdbId}`;
                        break;
                }
            } else {
                const season = document.getElementById("season-select").value || 1;
                const episode = currentEpisode;
                const tvServer = document.getElementById("tv-server").value;

                switch (tvServer) {
                    case "2embed-vcr":
                    default:
                        playerUrl = `https://streamsrcs.2embed.cc/vcr-tv?tmdb=${currentTmdbId}&s=${season}&e=${episode}`;
                        break;
                }
            }
            document.getElementById("stream-player").src = playerUrl;
        }

        window.addEventListener("DOMContentLoaded", initFromUrlParams);
    </script>
</body>

</html>
