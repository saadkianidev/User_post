<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary_color'   => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'bg_color'        => 'nullable|string|max:7',
        ]);

        $request->user()->theme()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json(['success' => true]);
    }
}