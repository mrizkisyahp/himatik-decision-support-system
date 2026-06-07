<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileWebController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        
        // Return different views based on role, or one generic view
        // Since we are using role-based layouts, it might be better to have
        // one view that extends a dynamic layout based on role, or separate views.
        // Let's create a generic profile.edit view that dynamically extends the correct layout.
        
        $layout = 'candidate.layout';
        if ($user->role === 'admin') {
            $layout = 'admin.layout';
        } elseif ($user->role === 'interviewer') {
            $layout = 'interviewer.layout';
        }

        return view('profile.edit', compact('user', 'layout'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
        ];

        // Only validate these fields if they are submitted (mainly for candidate)
        // Or if user is candidate, make them required
        if ($user->role === 'candidate') {
            $rules['nickname'] = 'required|string|max:255';
            $rules['nim'] = 'required|digits:10|unique:users,nim,' . $user->id;
            $rules['prodi'] = 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital';
            $rules['kelas'] = 'required|string|max:50';
            $rules['phone'] = 'required|string|max:20';
            $rules['address'] = 'required|string';
        } else {
            $rules['nickname'] = 'nullable|string|max:255';
            $rules['phone'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
