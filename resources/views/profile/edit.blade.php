@extends('layouts.main') {{-- Ganti dengan layout Bootstrap kamu --}}

@section('content')
    <div class="container py-5">
        <h2 class="fw-semibold h4 text-dark mb-4">
            {{ __('Profile') }}
        </h2>

        <div class="row gy-4">
            {{-- Update Profile Info --}}
            <div class="col-12 col-md-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        {{ __('Update Profile Information') }}
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="col-12 col-md-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        {{ __('Update Password') }}
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="col-12 col-md-8 mx-auto">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white fw-semibold">
                        {{ __('Delete Account') }}
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
