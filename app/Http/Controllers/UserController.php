<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('portal.user.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('portal.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            // Validate request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'nationality' => 'nullable|string|max:100',
                'hair_color' => 'nullable|string|max:50',
                'eye_color' => 'nullable|string|max:50',
                'bust' => 'nullable|numeric|min:20',
                'waist' => 'nullable|numeric|min:20',
                'hips' => 'nullable|numeric|min:20',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'about' => 'nullable|string|max:2000',
                'languages' => 'nullable|array',
                'languages.*' => 'string|max:50',
                'tags' => 'nullable|array',
                'hourly_rate' => 'nullable|numeric|min:0',
                'availability' => 'nullable|in:available,not_available,limited',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Prepare measurements array
            $measurements = [
                'bust' => $validatedData['bust'] ?? null,
                'waist' => $validatedData['waist'] ?? null,
                'hips' => $validatedData['hips'] ?? null,
            ];

            // Update user attributes
            $user->update([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'] ?? null,
                'whatsapp' => $validatedData['whatsapp'] ?? null,
                'dob' => $validatedData['dob'] ?? null,
                'gender' => $validatedData['gender'] ?? null,
                'nationality' => $validatedData['nationality'] ?? null,
                'hair_color' => $validatedData['hair_color'] ?? null,
                'eye_color' => $validatedData['eye_color'] ?? null,
                'measurements' => $measurements,
                'city' => $validatedData['city'] ?? null,
                'country' => $validatedData['country'] ?? null,
                'about' => $validatedData['about'] ?? null,
                'languages' => $validatedData['languages'] ?? null,
                'tags' => $validatedData['tags'] ?? null,
                'hourly_rate' => $validatedData['hourly_rate'] ?? null,
                'availability' => $validatedData['availability'] ?? 'not_available',
            ]);

            // Handle profile image upload
            $this->handleProfileImageUpload($request, $user);

            // Handle gallery images upload
            $this->handleGalleryImagesUpload($request, $user);

            return redirect()->route('profile.show', $user->id)
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $exception) {
            Log::error('Error in ' . __METHOD__ . ' - File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ', Message: ' . $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    /**
     * Handle profile image upload.
     */
    protected function handleProfileImageUpload(Request $request, User $user)
    {
        if (!$request->hasFile('profile_image')) {
            return;
        }

        // Create profile directory if it doesn't exist
        $profilePath = public_path('assets/images/profile');
        if (!file_exists($profilePath)) {
            mkdir($profilePath, 0755, true);
        }

        // Delete old profile image if exists
        if ($user->primaryImage()) {
            $oldImagePath = public_path(str_replace(url('/'), '', $user->primaryImage()->image_url));
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $user->primaryImage()->delete();
        }

        // Store new profile image
        $image = $request->file('profile_image');
        $imageName = 'profile_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move($profilePath, $imageName);

        // Create gallery record
        $user->gallery()->create([
            'image_url' => url('/assets/images/profile/' . $imageName),
            'image_path' => 'assets/images/profile/' . $imageName,
            'is_primary' => true
        ]);
    }

    /**
     * Handle gallery images upload.
     */
    protected function handleGalleryImagesUpload(Request $request, User $user)
    {
        if (!$request->hasFile('gallery_images')) {
            return;
        }

        // Create gallery directory if it doesn't exist
        $galleryPath = public_path('assets/images/gallery');
        if (!file_exists($galleryPath)) {
            mkdir($galleryPath, 0755, true);
        }

        foreach ($request->file('gallery_images') as $image) {
            $imageName = 'gallery_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($galleryPath, $imageName);

            $user->gallery()->create([
                'image_url' => url('/assets/images/gallery/' . $imageName),
                'image_path' => 'assets/images/gallery/' . $imageName,
                'is_primary' => false
            ]);
        }
    }
}
