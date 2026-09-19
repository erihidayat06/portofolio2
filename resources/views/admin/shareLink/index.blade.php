@extends('layouts.main')

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="success-alert">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        <!-- Card Filter Data -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('share-link.index') }}" method="GET" id="filterForm">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold mb-1"><small>Filter Cepat</small></label>
                            <select name="period" id="periodSelect" class="form-select form-select-sm">
                                <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Semua Waktu
                                </option>
                                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini
                                </option>
                                <option value="last_7_days"
                                    {{ request('period') == 'last_7_days' || !request('period') ? 'selected' : '' }}>7 Hari
                                    Terakhir</option>
                                <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan
                                    Ini</option>
                                <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>Tahun Ini
                                </option>
                                <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Tanggal
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3 custom-date-range {{ request('period') == 'custom' ? '' : 'd-none' }}">
                            <label class="form-label fw-bold mb-1"><small>Dari Tanggal</small></label>
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-3 custom-date-range {{ request('period') == 'custom' ? '' : 'd-none' }}">
                            <label class="form-label fw-bold mb-1"><small>Sampai Tanggal</small></label>
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                🔍 Filter
                            </button>
                            <a href="{{ route('share-link.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                                🔄 Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Rangkuman Statistik Keseluruhan -->
        <div class="row mb-4">
            <div class="col-md-4 mb-2">
                <div class="card bg-primary text-white shadow-sm border-0">
                    <div class="card-body text-center py-3">
                        <h6 class="mb-1 opacity-75">Hari Ini</h6>
                        <h4 class="fw-bold mb-0">
                            {{ $data->sum('views_today') }} <small class="fs-6 fw-normal">Views</small> |
                            {{ $data->sum('completed_today') }} <small class="fs-6 fw-normal">Selesai</small>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card bg-success text-white shadow-sm border-0">
                    <div class="card-body text-center py-3">
                        <h6 class="mb-1 opacity-75">Bulan Ini</h6>
                        <h4 class="fw-bold mb-0">
                            {{ $data->sum('views_month') }} <small class="fs-6 fw-normal">Views</small> |
                            {{ $data->sum('completed_month') }} <small class="fs-6 fw-normal">Selesai</small>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card bg-dark text-white shadow-sm border-0">
                    <div class="card-body text-center py-3">
                        <h6 class="mb-1 opacity-75">Tahun Ini</h6>
                        <h4 class="fw-bold mb-0">
                            {{ $data->sum('views_year') }} <small class="fs-6 fw-normal">Views</small> |
                            {{ $data->sum('completed_year') }} <small class="fs-6 fw-normal">Selesai</small>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Grafik -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3">📈 Grafik Aktivitas</h5>
                <canvas id="activityChart" style="max-height: 280px;"></canvas>
            </div>
        </div>

        <!-- Tabel Data Share Link -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-bold mb-0">Share Link</h5>
                    <a href="{{ route('share-link.create') }}" class="btn btn-sm btn-success">Tambah Link</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="projekTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nama Link</th>
                                <th>Link Share</th>
                                <th class="text-center">Hari Ini</th>
                                <th class="text-center">Bulan Ini</th>
                                <th class="text-center">Tahun Ini</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i => $share)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $share->nama }}</td>
                                    <td>{{ $share->link }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('share/' . $share->slug) }}" target="_blank"
                                                class="me-2 text-truncate" style="max-width: 180px;">
                                                {{ url('share/' . $share->slug) }}
                                            </a>

                                            <button type="button" class="btn btn-sm btn-outline-secondary copy-btn"
                                                data-link="{{ url('share/' . $share->slug) }}">
                                                Copy
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Statistik Hari Ini -->
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark" title="Views Hari Ini">👁️
                                            {{ $share->views_today ?? 0 }}</span>
                                        <span class="badge bg-success" title="Selesai Hari Ini">✅
                                            {{ $share->completed_today ?? 0 }}</span>
                                    </td>

                                    <!-- Statistik Bulan Ini -->
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark" title="Views Bulan Ini">👁️
                                            {{ $share->views_month ?? 0 }}</span>
                                        <span class="badge bg-success" title="Selesai Bulan Ini">✅
                                            {{ $share->completed_month ?? 0 }}</span>
                                    </td>

                                    <!-- Statistik Tahun Ini -->
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark" title="Views Tahun Ini">👁️
                                            {{ $share->views_year ?? 0 }}</span>
                                        <span class="badge bg-success" title="Selesai Tahun Ini">✅
                                            {{ $share->completed_year ?? 0 }}</span>
                                    </td>

                                    <!-- Total Keseluruhan -->
                                    <td class="text-center fw-bold">
                                        <span class="badge bg-secondary" title="Total Views">👁️
                                            {{ $share->total_views ?? 0 }}</span>
                                        <span class="badge bg-primary" title="Total Completed">✅
                                            {{ $share->total_completed ?? 0 }}</span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            <a href="{{ route('share-link.edit', $share->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>

                                            <form action="{{ route('share-link.destroy', $share->id) }}" method="POST"
                                                class="d-inline mb-0">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus?')">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables & Chart.js -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            $('#projekTable').DataTable();

            // Toggle Custom Date Range Input
            $('#periodSelect').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('.custom-date-range').removeClass('d-none');
                } else {
                    $('.custom-date-range').addClass('d-none');
                }
            });
        });

        // Copy Link Script
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".copy-btn");

            buttons.forEach(btn => {
                btn.addEventListener("click", function() {
                    const link = this.getAttribute("data-link");
                    navigator.clipboard.writeText(link).then(() => {
                        alert("Link berhasil dicopy!");
                    }).catch(err => {
                        console.error("Gagal copy link: ", err);
                    });
                });
            });
        });

        // Chart.js Script
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartDays ?? []) !!},
                datasets: [{
                        label: 'Pengunjung (Views)',
                        data: {!! json_encode($chartViews ?? []) !!},
                        borderColor: '#0dcaf0',
                        backgroundColor: 'rgba(13, 202, 240, 0.15)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Selesai Unlock (Completed)',
                        data: {!! json_encode($chartCompleted ?? []) !!},
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.15)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endpush
