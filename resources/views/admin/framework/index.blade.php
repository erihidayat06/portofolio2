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
                    <h5>framework Pemprograman</h5>
                </div>
                <a href="{{ route('framework.create') }}" class="btn btn-sm btn-success mb-3">Tambah framework</a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="projekTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama framework</th>
                                <th>Gambar</th>

                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i => $framework)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $framework->nama }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $framework->gambar) }}" alt="Gambar"
                                            width="150">
                                    </td>
                                    <td>
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('framework.edit', $framework->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>

                                        {{-- Form Hapus --}}
                                        <form action="{{ route('framework.destroy', $framework->id) }}" method="POST"
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
@endpush
