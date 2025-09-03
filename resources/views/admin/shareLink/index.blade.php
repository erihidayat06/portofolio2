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
                        setTimeout(() => alert.remove(), 500); // hapus dari DOM setelah animasi
                    }
                }, 3000); // 3 detik
            </script>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5>Share Link</h5>
                </div>
                <a href="{{ route('share-link.create') }}" class="btn btn-sm btn-success mb-3">Tambah Link</a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="projekTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nama Link</th>
                                <th>Link share</th>
                                <th>Aksi</th>
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
                                            {{-- Link share --}}
                                            <a href="{{ url('share/' . $share->slug) }}" target="_blank" class="me-2">
                                                {{ url('share/' . $share->slug) }}
                                            </a>

                                            {{-- Tombol Copy --}}
                                            <button type="button" class="btn btn-sm btn-outline-secondary copy-btn"
                                                data-link="{{ url('share/' . $share->slug) }}">
                                                Copy
                                            </button>
                                        </div>
                                    </td>

                                    <td>
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('share-link.edit', $share->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>

                                        {{-- Form Hapus --}}
                                        <form action="{{ route('share-link.destroy', $share->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus?')">Hapus</button>
                                        </form>
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
    <!-- DataTables JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#projekTable').DataTable();
        });
    </script>

    <script>
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
    </script>
@endpush
