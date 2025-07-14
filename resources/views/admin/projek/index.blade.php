@extends('layouts.main')

@section('content')
    <div class="container mt-4">

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5>Data Projek</h5>
                </div>
                <a href="{{ route('projek.create') }}" class="btn btn-sm btn-success mb-3">Tambah Projek</a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="projekTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Gambar</th>
                                <th>Deskripsi</th>
                                <th>Bahasa</th>
                                <th>Framework</th>
                                <th>Link</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->nm_projek }}</td>
                                    <td>
                                        @php $imgs = json_decode($item->gambar, true); @endphp
                                        @if ($imgs && count($imgs))
                                            <img src="{{ asset('storage/' . $imgs[0]) }}" alt="gambar" width="80">
                                        @endif
                                    </td>

                                    <td>


                                        {!! Str::limit($item->deskripsi, 50) !!}
                                        @if (Str::length($item->deskripsi) > 50)
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#modalDeskripsi{{ $item->id }}">Selengkapnya</a>
                                        @endif

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalDeskripsi{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="modalDeskripsi{{ $item->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5"
                                                            id="modalDeskripsi{{ $item->id }}Label">Modal title</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! $item->deskripsi !!}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        @php $bahasa = json_decode($item->bahasa_id, true); @endphp
                                        <ul class="list-unstyled mb-0 d-flex flex-column gap-1">
                                            @foreach ($bahasa as $id)
                                                @php $bhs = $bahasas->where('id', $id)->first(); @endphp
                                                @if ($bhs)
                                                    <li class="d-flex align-items-center gap-2">
                                                        <img src="{{ asset('storage/' . $bhs->gambar) }}"
                                                            alt="{{ $bhs->nama }}" width="30" class="rounded">
                                                        {{ $bhs->nama }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td>
                                        @php $framework = json_decode($item->framework_id, true); @endphp
                                        <ul class="list-unstyled mb-0 d-flex flex-column gap-1">
                                            @foreach ($framework as $id)
                                                @php $fw = $frameworks->where('id', $id)->first(); @endphp
                                                @if ($fw)
                                                    <li class="d-flex align-items-center gap-2">
                                                        <img src="{{ asset('storage/' . $fw->gambar) }}"
                                                            alt="{{ $fw->nama }}" width="30" class="rounded">
                                                        {{ $fw->nama }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td>
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" target="_blank">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('projek.edit', $item->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('projek.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
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
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#projekTable').DataTable();

            // Auto dismiss alert
            setTimeout(() => {
                $('.alert-dismissible').fadeOut('slow');
            }, 3000);
        });
    </script>
@endpush
