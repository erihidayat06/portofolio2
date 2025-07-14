@extends('layouts.main')

@section('content')
    <style>
        #preview-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: flex-start;
        }

        .gambar-box {
            width: 100px;
            height: 100px;
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #ccc;
        }

        .gambar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hapus-btn {
            position: absolute;
            top: 0;
            right: 0;
            background-color: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            border-radius: 0 0 0 5px;
            padding: 2px 6px;
            cursor: pointer;
        }

        .add-box {
            width: 100px;
            height: 100px;
            border: 2px dashed #aaa;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 0.5rem;
            cursor: pointer;
            color: #888;
            font-size: 2rem;
        }
    </style>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header fw-bold">Edit Portofolio</div>
            <div class="card-body">
                <form action="{{ route('projek.update', $projek->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Projek --}}
                    <div class="mb-3">
                        <label for="nm_projek" class="form-label">Nama Projek</label>
                        <input type="text" class="form-control @error('nm_projek') is-invalid @enderror" id="nm_projek"
                            name="nm_projek" value="{{ old('nm_projek', $projek->nm_projek) }}">
                        @error('nm_projek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gambar --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar (maksimal 5 gambar)</label>
                        <div id="preview-wrapper">
                            @php $gambarLama = json_decode($projek->gambar, true); @endphp
                            @foreach ($gambarLama as $i => $img)
                                <div class="gambar-box">
                                    <img src="{{ asset('storage/' . $img) }}" alt="gambar">
                                    <button type="button" class="hapus-btn"
                                        onclick="hapusGambarLama(this)">&times;</button>

                                    <input type="hidden" name="gambar_lama[]" value="{{ $img }}">
                                </div>
                            @endforeach

                            {{-- Tombol + --}}
                            <div id="add-box" class="add-box" onclick="document.getElementById('gambar-input').click()">+
                            </div>
                        </div>

                        <input type="file" id="gambar-input" name="gambar[]" multiple accept="image/*" hidden
                            onchange="handleFiles(this.files)">

                        @error('gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('gambar.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input id="deskripsi" class="@error('deskripsi') is-invalid @enderror"
                            value="{{ old('deskripsi', $projek->deskripsi) }}" type="hidden" name="deskripsi">
                        <trix-editor input="deskripsi" required></trix-editor>
                        {{-- <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $projek->deskripsi) }}</textarea> --}}
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bahasa --}}
                    <div class="mb-3">
                        <label for="bahasa_id" class="form-label">Bahasa</label>
                        <select name="bahasa_id[]" id="bahasa_id" multiple
                            class="form-select @error('bahasa_id') is-invalid @enderror bahasa-multiple">
                            @foreach ($bahasas as $bahasa)
                                <option value="{{ $bahasa->id }}"
                                    {{ collect(json_decode($projek->bahasa_id))->contains($bahasa->id) ? 'selected' : '' }}>
                                    {{ $bahasa->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('bahasa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Framework --}}
                    <div class="mb-3">
                        <label for="framework_id" class="form-label">Framework</label>
                        <select name="framework_id[]" id="framework_id" multiple
                            class="form-select @error('framework_id') is-invalid @enderror framework-multiple">
                            @foreach ($frameworks as $framework)
                                <option value="{{ $framework->id }}"
                                    {{ collect(json_decode($projek->framework_id))->contains($framework->id) ? 'selected' : '' }}>
                                    {{ $framework->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('framework_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Link --}}
                    <div class="mb-3">
                        <label for="link" class="form-label">Link (opsional)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" id="link"
                            name="link" value="{{ old('link', $projek->link) }}">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let fileList = [];
        const maxGambar = 5;

        function hapusGambarLama(button) {
            const gambarBox = button.closest('.gambar-box');
            if (gambarBox) {
                const hiddenInput = gambarBox.querySelector('input[name="gambar_lama[]"]');
                if (hiddenInput) hiddenInput.remove();
                gambarBox.remove();
            }
        }

        function handleFiles(files) {
            const wrapper = document.getElementById('preview-wrapper');
            const addBox = document.getElementById('add-box');

            const currentGambarCount = wrapper.querySelectorAll('.gambar-box').length;
            const newFiles = Array.from(files);

            if ((currentGambarCount + newFiles.length) > maxGambar) {
                alert('Maksimal 5 gambar diperbolehkan.');
                return;
            }

            newFiles.forEach((file) => {
                fileList.push(file);

                const reader = new FileReader();
                reader.onload = function(e) {
                    const box = document.createElement('div');
                    box.className = 'gambar-box';

                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const delBtn = document.createElement('button');
                    delBtn.type = 'button';
                    delBtn.className = 'hapus-btn';
                    delBtn.innerHTML = '&times;';
                    delBtn.onclick = () => {
                        fileList = fileList.filter(f => f !== file);
                        box.remove();
                        updateInputFiles();
                    };

                    box.appendChild(img);
                    box.appendChild(delBtn);
                    wrapper.insertBefore(box, addBox);
                };
                reader.readAsDataURL(file);
            });

            updateInputFiles();
        }

        function updateInputFiles() {
            const dt = new DataTransfer();
            fileList.forEach(file => dt.items.add(file));
            document.getElementById('gambar-input').files = dt.files;
        }

        $(document).ready(function() {
            $('.bahasa-multiple').select2();
            $('.framework-multiple').select2();
        });
    </script>
@endsection
