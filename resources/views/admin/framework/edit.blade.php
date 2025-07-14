@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header fw-bold">Edit framework</div>
            <div class="card-body">
                <form action="{{ route('framework.update', $framework->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama framework --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama framework</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama', $framework->nama) }}">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gambar --}}
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar (biarkan kosong jika tidak ingin mengubah)</label>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror" id="gambar"
                            name="gambar" accept="image/*" onchange="previewGambar(event)">
                        @error('gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        {{-- Gambar Lama --}}
                        <div id="preview" class="d-flex mt-2 gap-2 flex-wrap">
                            @if ($framework->gambar)
                                <img src="{{ asset('storage/' . $framework->gambar) }}" class="rounded shadow-sm"
                                    style="height: 100px;" id="gambar-lama">
                            @endif
                        </div>
                    </div>

                    <button class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Gambar Preview --}}
    <script>
        function previewGambar(event) {
            const previewContainer = document.getElementById('preview');
            previewContainer.innerHTML = '';

            const file = event.target.files[0];
            if (file) {
                const fileReader = new FileReader();
                fileReader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = "rounded shadow-sm";
                    img.style.height = '100px';
                    previewContainer.appendChild(img);
                };
                fileReader.readAsDataURL(file);
            }
        }
    </script>
@endsection
