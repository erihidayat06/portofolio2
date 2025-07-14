@extends('layouts.main')

@section('content')
    <style>
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


    </style>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header fw-bold">Tambah Portofolio</div>
            <div class="card-body">
                <form action="{{ route('projek.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Nama Projek --}}
                    <div class="mb-3">
                        <label for="nm_projek" class="form-label">Nama Projek</label>
                        <input type="text" class="form-control @error('nm_projek') is-invalid @enderror" id="nm_projek"
                            name="nm_projek" value="{{ old('nm_projek') }}">
                        @error('nm_projek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gambar --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar (maksimal 5 gambar)</label>

                        {{-- Preview container --}}
                        <div id="preview-wrapper" class="d-flex flex-row gap-2 flex-wrap align-items-start">
                            {{-- Tombol + (dimasukkan ulang via JS) --}}
                        </div>

                        {{-- Hidden input untuk trigger file select --}}
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
                        <input id="deskripsi" value="{{ old('deskripsi') }}" type="hidden" name="deskripsi">
                        <trix-editor input="deskripsi" required></trix-editor>
                        {{-- <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"></textarea> --}}
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bahasa --}}
                    <div class="mb-3">
                        <label for="bahasa_id" class="form-label">Bahasa</label>
                        <select name="bahasa_id[]" id="bahasa_id" multiple="multiple"
                            class="form-select @error('bahasa_id') is-invalid @enderror bahasa-multiple">
                            <option value="">Pilih Bahasa</option>
                            @foreach ($bahasas as $bahasa)
                                <option value="{{ $bahasa->id }}" {{ old('bahasa_id') == $bahasa->id ? 'selected' : '' }}>
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
                        <select name="framework_id[]" id="framework_id" multiple="multiple"
                            class="form-select @error('framework_id') is-invalid @enderror  framework-multiple">
                            <option value="">Pilih Framework</option>
                            @foreach ($frameworks as $framework)
                                <option value="{{ $framework->id }}"
                                    {{ old('framework_id') == $framework->id ? 'selected' : '' }}>
                                    {{ $framework->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('framework_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Link (Optional) --}}
                    <div class="mb-3">
                        <label for="link" class="form-label">Link (opsional)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" id="link"
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    {{-- Gambar Preview --}}
    <script>
        $(document).ready(function() {
            $('.bahasa-multiple').select2();
        });
        $(document).ready(function() {
            $('.framework-multiple').select2();
        });
        const wrapper = document.getElementById('preview-wrapper');
        const input = document.getElementById('gambar-input');
        let fileList = [];

        function handleFiles(files) {
            const newFiles = Array.from(files);

            if (fileList.length + newFiles.length > 5) {
                alert("Maksimal 5 gambar!");
                return input.value = "";
            }

            fileList.push(...newFiles);
            renderPreview();
        }

        function renderPreview() {
            wrapper.innerHTML = '';

            const fragment = document.createDocumentFragment();

            fileList.forEach((file, index) => {
                const box = document.createElement('div');
                box.className = 'gambar-box position-relative';

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const del = document.createElement('button');
                    del.className = 'hapus-btn';
                    del.innerHTML = '&times;';
                    del.type = 'button';
                    del.onclick = () => {
                        fileList.splice(index, 1);
                        renderPreview();
                    };

                    box.appendChild(img);
                    box.appendChild(del);
                };
                reader.readAsDataURL(file);

                fragment.appendChild(box);
            });

            // Tambahkan tombol "+"
            if (fileList.length < 5) {
                const addBox = document.createElement('div');
                addBox.className = 'add-box';
                addBox.innerHTML = '+';
                addBox.onclick = () => input.click();
                fragment.appendChild(addBox);
            }

            wrapper.appendChild(fragment);
            updateInputFiles();
        }

        function updateInputFiles() {
            const dt = new DataTransfer();
            fileList.forEach(file => dt.items.add(file));
            input.files = dt.files;
        }

        // Inisialisasi awal tombol + saat belum ada gambar
        renderPreview();
    </script>
@endsection
