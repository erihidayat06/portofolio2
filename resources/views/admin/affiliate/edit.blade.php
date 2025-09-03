@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header fw-bold">Update Link</div>
                <div class="card-body">
                    <form action="{{ route('affiliate.update', $affiliate->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="link" class="form-label">link</label>
                            <input type="text" class="form-control @error('link') is-invalid @enderror" id="link"
                                name="link" value="{{ old('link', $affiliate) }}">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary">Simpan</button>
                        <a href="{{ route('affiliate.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
