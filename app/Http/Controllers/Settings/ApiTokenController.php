<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ApiTokenController extends Controller
{
    public function index()
    {
        $tokens = auth()->user()->tokens()->orderBy('created_at', 'desc')->get();

        return Inertia::render('settings/ApiTokens', [
            'tokens' => $tokens,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'nullable|array',
        ]);

        $abilities = $validated['abilities'] ?? ['*'];

        $token = auth()->user()->createToken(
            $validated['name'],
            $abilities
        );

        return back()->with([
            'success' => 'Token creado exitosamente',
            'newToken' => $token->plainTextToken,
        ]);
    }

    public function destroy($tokenId)
    {
        auth()->user()->tokens()->where('id', $tokenId)->delete();

        return redirect()->back()->with('success', 'Token eliminado exitosamente');
    }
}
