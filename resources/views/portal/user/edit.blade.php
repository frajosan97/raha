@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow shadow-sm">
                <div class="card-header main-bg text-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i> Edit Profile</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Image Upload -->
                        <div class="mb-4">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image">

                            @if($user->primaryImage)
                            <div class="mt-2">
                                <img src="{{ $user->primaryImage->image_url }}" alt="Current Profile" class="img-thumbnail" width="150">
                            </div>
                            @endif
                        </div>

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Age</label>
                                    <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob', $user->dob) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $user->country) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Physical Attributes -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="height" class="form-label">Height (cm)</label>
                                    <input type="number" class="form-control" id="height" name="height" value="{{ old('height', $user->height) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight', $user->weight) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Measurements -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bust" class="form-label">Bust (inches)</label>
                                    <input type="number" class="form-control" id="bust" name="bust" value="{{ old('bust', $user->measurements['bust'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="waist" class="form-label">Waist (inches)</label>
                                    <input type="number" class="form-control" id="waist" name="waist" value="{{ old('waist', $user->measurements['waist'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="hips" class="form-label">Hips (inches)</label>
                                    <input type="number" class="form-control" id="hips" name="hips" value="{{ old('hips', $user->measurements['hips'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Appearance -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eye_color" class="form-label">Eye Color</label>
                                    <input type="text" class="form-control" id="eye_color" name="eye_color" value="{{ old('eye_color', $user->eye_color) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hair_color" class="form-label">Hair Color</label>
                                    <input type="text" class="form-control" id="hair_color" name="hair_color" value="{{ old('hair_color', $user->hair_color) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Professional Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hourly_rate" class="form-label">Hourly Rate (KES)</label>
                                    <input type="number" step="0.01" class="form-control" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $user->hourly_rate) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="availability" class="form-label">Availability</label>
                                    <select class="form-select" id="availability" name="availability">
                                        <option value="available" {{ old('availability', $user->availability) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="not_available" {{ old('availability', $user->availability) == 'not_available' ? 'selected' : '' }}>Not Available</option>
                                        <option value="limited" {{ old('availability', $user->availability) == 'limited' ? 'selected' : '' }}>Limited Availability</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- About Section -->
                        <div class="mb-4">
                            <label for="about" class="form-label">About Me</label>
                            <textarea class="form-control" id="about" name="about" rows="5">{{ old('about', $user->about) }}</textarea>
                        </div>

                        <!-- Languages -->
                        <div class="mb-4">
                            <label class="form-label">Languages (Select multiple)</label>
                            <select class="form-select" multiple name="languages[]">
                                @php
                                $userLanguages = $user->languages ?? [];
                                $allLanguages = ['English', 'Swahili', 'French', 'Spanish', 'German', 'Chinese', 'Arabic', 'Hindi', 'Portuguese', 'Russian'];
                                @endphp
                                @foreach($allLanguages as $language)
                                <option value="{{ $language }}" {{ in_array($language, old('languages', $userLanguages)) ? 'selected' : '' }}>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gallery Upload -->
                        <div class="mb-4">
                            <label for="gallery_images" class="form-label">Gallery Images</label>
                            <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple>
                            <small class="text-muted">You can upload multiple images at once</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-outline-custom p-1">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('profile.show', $user->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection