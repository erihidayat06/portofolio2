@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header fw-bold">Tambah Link</div>
                <div class="card-body">
                    <form action="{{ route('share-link.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Nama Link --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Link</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Link --}}
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control @error('link') is-invalid @enderror" id="link"
                                name="link" value="{{ old('link') }}">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
