@extends('layouts.main')

@section('content')
    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5>Profil Website</h5>
                </div>
                <form action="{{ route('admin.dashboard.update', $profilWeb->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control"
                            value="{{ old('judul', $profilWeb->judul) }}">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input id="deskripsi" type="hidden" name="deskripsi"
                            value="{{ old('deskripsi', $profilWeb->deskripsi) }}">
                        <trix-editor input="deskripsi"></trix-editor>
                    </div>

                    <div class="mb-3">
                        <label for="cv" class="form-label">CV (PDF)</label>
                        <input type="file" name="cv" class="form-control">
                        @if ($profilWeb->cv)
                            <small class="text-muted">CV saat ini: <a href="{{ asset('storage/' . $profilWeb->cv) }}"
                                    target="_blank">Lihat</a></small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="sertifikat" class="form-label">Link Sertifikat</label>
                        <input type="text" name="sertifikat" class="form-control"
                            value="{{ old('sertifikat', $profilWeb->sertifikat) }}">
                        @if ($profilWeb->sertifikat)
                            <small class="text-muted">Link saat ini: <a href="{{ $profilWeb->sertifikat }}"
                                    target="_blank">Lihat Sertifikat</a></small>
                        @endif
                    </div>


                    <div class="mb-3">
                        <label for="deskripsi_profil" class="form-label">Deskripsi Profil</label>
                        <input id="deskripsi_profil" type="hidden" name="deskripsi_profil"
                            value="{{ old('deskripsi_profil', $profilWeb->deskripsi_profil) }}">
                        <trix-editor input="deskripsi_profil"></trix-editor>
                    </div>

                    <div class="mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" name="instagram" class="form-control"
                            value="{{ old('instagram', $profilWeb->instagram) }}">
                    </div>

                    <div class="mb-3">
                        <label for="youtube" class="form-label">YouTube</label>
                        <input type="text" name="youtube" class="form-control"
                            value="{{ old('youtube', $profilWeb->youtube) }}">
                    </div>

                    <div class="mb-3">
                        <label for="tiktok" class="form-label">TikTok</label>
                        <input type="text" name="tiktok" class="form-control"
                            value="{{ old('tiktok', $profilWeb->tiktok) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

            </div>
        </div>
    </div>
@endsection
