<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PlatformController extends Controller
{
    public function edit()
    {
        return Inertia::render('settings/Platform', [
            'platformName' => Setting::get('platform_name', 'CRM landings.cl'),
            'platformLogo' => Setting::get('platform_logo', ''),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'platform_name' => 'required|string|max:255',
            'platform_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        Setting::set('platform_name', $validated['platform_name']);
        
        if ($request->hasFile('platform_logo')) {
            // Eliminar logo anterior si existe
            $oldLogo = Setting::get('platform_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Guardar nuevo logo
            $path = $request->file('platform_logo')->store('logos', 'public');
            Setting::set('platform_logo', $path);
        }

        return redirect()->back()->with('success', 'Configuración actualizada exitosamente');
    }

    public function deleteLogo()
    {
        $logo = Setting::get('platform_logo');
        
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }

        Setting::set('platform_logo', '');

        return redirect()->back()->with('success', 'Logo eliminado exitosamente');
    }
}
