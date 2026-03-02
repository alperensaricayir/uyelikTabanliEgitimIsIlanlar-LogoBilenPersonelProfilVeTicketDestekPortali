<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's own profile view.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
            'isOwner' => true,
        ]);
    }

    /**
     * Display the public profile view.
     */
    public function publicShow(User $user): View|RedirectResponse
    {
        // If the profile is not public, only the owner can see it.
        if (!$user->is_profile_public) {
            if (Auth::check() && Auth::id() === $user->id) {
                // Let the owner see it, but flag that it differs from what others see
                return view('profile.show', [
                    'user' => $user,
                    'isOwner' => true,
                ]);
            }
            abort(404);
        }

        return view('profile.show', [
            'user' => $user,
            'isOwner' => Auth::check() && Auth::id() === $user->id,
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle physical profile photo upload before filling the model
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Store new and set path string
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $validated['profile_photo_path'] = $path;
        }

        // Handle skills string to array conversion
        if (isset($validated['skills'])) {
            $skillsArray = collect(explode(',', $validated['skills']))
                ->map(fn($skill) => trim($skill))
                ->filter(fn($skill) => !empty($skill))
                ->take(20) // Limit to 20 skills
                ->map(fn($skill) => substr($skill, 0, 30)) // Max 30 chars per skill
                ->values()
                ->toArray();
            $validated['skills'] = $skillsArray;
        } else {
            $validated['skills'] = null; // Clear if empty
        }

        // Ensure is_profile_public is strictly boolean based on presence of the checkbox
        $validated['is_profile_public'] = $request->has('is_profile_public');

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
