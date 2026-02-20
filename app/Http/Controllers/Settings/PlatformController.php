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
        $logoPath = Setting::get('platform_logo', '');
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : '';
        
        return Inertia::render('settings/Platform', [
            'platformName' => Setting::get('platform_name', 'CRM landings.cl'),
            'platformLogo' => $logoUrl,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'platform_name' => 'required|string|max:255',
                'platform_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            Setting::set('platform_name', $validated['platform_name']);
            
            if ($request->hasFile('platform_logo')) {
                // Verificar que el directorio existe
                if (!Storage::disk('public')->exists('logos')) {
                    Storage::disk('public')->makeDirectory('logos');
                }

                // Eliminar logo anterior si existe
                $oldLogo = Setting::get('platform_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                // Guardar nuevo logo
                $path = $request->file('platform_logo')->store('logos', 'public');
                
                if (!$path) {
                    throw new \Exception('No se pudo guardar el archivo. Verifica los permisos del directorio storage/app/public/logos');
                }
                
                Setting::set('platform_logo', $path);
            }

            return redirect()->back()->with('success', 'Configuración actualizada exitosamente');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al actualizar configuración de plataforma', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al subir el logo: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    private function getUploadErrorMessage($errorCode)
    {
        $errors = [
            UPLOAD_ERR_OK => 'No hay error',
            UPLOAD_ERR_INI_SIZE => 'El archivo excede upload_max_filesize en php.ini',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede MAX_FILE_SIZE en el formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta el directorio temporal',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida',
        ];
        
        return $errors[$errorCode] ?? 'Error desconocido: ' . $errorCode;
    }

    public function deleteLogo()
    {
        try {
            $logo = Setting::get('platform_logo');
            
            if ($logo && Storage::disk('public')->exists($logo)) {
                Storage::disk('public')->delete($logo);
            }

            Setting::set('platform_logo', '');

            return redirect()->back()->with('success', 'Logo eliminado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar logo: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error al eliminar el logo: ' . $e->getMessage());
        }
    }
}
