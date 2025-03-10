<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store the new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
            
            // Debug information
            \Log::info('Avatar upload processed', [
                'file' => $request->file('avatar'),
                'stored_path' => $avatarPath,
                'full_url' => Storage::url($avatarPath)
            ]);
        }
        
        $user->update($validated);
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('profile.show')->with('success', 'Password changed successfully.');
    }
    
    /**
     * Update the user's avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store the new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Update user with new avatar
            $user->update(['avatar' => $avatarPath]);
            
            // Debug information
            \Log::info('Avatar updated successfully', [
                'user_id' => $user->id,
                'stored_path' => $avatarPath,
                'full_url' => Storage::url($avatarPath)
            ]);
            
            return redirect()->route('profile.edit')->with('success', 'Avatar updated successfully.');
        }
        
        return redirect()->back()->with('error', 'No avatar file provided.');
    }
    
    /**
     * Remove the avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();
        
        // Delete avatar if it exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Update user record
        $user->update(['avatar' => null]);
        
        return redirect()->route('profile.edit')->with('success', 'Avatar removed successfully.');
    }
}
