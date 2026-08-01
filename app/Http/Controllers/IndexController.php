<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function settings()
    {
        $user = auth()->user();

        return view('settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // update or create profile row, in case some users don't have one yet
        $user->detail()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]
        );

        return back()->with('success', 'Settings updated successfully!');
    }
}
