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
        try {
            // Información de debug que se enviará al frontend
            $debugInfo = [
                'request_method' => $request->method(),
                'has_file' => $request->hasFile('platform_logo'),
                'content_type' => $request->header('Content-Type'),
                'file_info' => null,
                'storage_info' => [
                    'disk' => config('filesystems.default'),
                    'public_path' => storage_path('app/public'),
                    'public_exists' => is_dir(storage_path('app/public')),
                    'public_writable' => is_writable(storage_path('app/public')),
                    'logos_exists' => is_dir(storage_path('app/public/logos')),
                    'logos_writable' => is_writable(storage_path('app/public/logos')),
                ]
            ];
            
            if ($request->hasFile('platform_logo')) {
                $file = $request->file('platform_logo');
                $debugInfo['file_info'] = [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'error' => $file->getError(),
                    'error_message' => $this->getUploadErrorMessage($file->getError()),
                    'is_valid' => $file->isValid(),
                    'temp_path' => $file->getPathname(),
                    'temp_exists' => file_exists($file->getPathname()),
                    'temp_readable' => is_readable($file->getPathname()),
                ];
            }
            
            $validated = $request->validate([
                'platform_name' => 'required|string|max:255',
                'platform_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            Setting::set('platform_name', $validated['platform_name']);
            
            if ($request->hasFile('platform_logo')) {
                // Verificar que el directorio existe
                if (!Storage::disk('public')->exists('logos')) {
                    Storage::disk('public')->makeDirectory('logos');
                    $debugInfo['created_directory'] = true;
                }

                // Eliminar logo anterior si existe
                $oldLogo = Setting::get('platform_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                    $debugInfo['deleted_old_logo'] = $oldLogo;
                }

                // Guardar nuevo logo
                $path = $request->file('platform_logo')->store('logos', 'public');
                
                if (!$path) {
                    return redirect()->back()
                        ->with('error', 'No se pudo guardar el archivo')
                        ->with('debug', $debugInfo);
                }
                
                $debugInfo['saved_path'] = $path;
                $debugInfo['full_path'] = storage_path('app/public/' . $path);
                $debugInfo['file_exists_after_save'] = file_exists(storage_path('app/public/' . $path));
                
                Setting::set('platform_logo', $path);
            }

            return redirect()->back()
                ->with('success', 'Configuración actualizada exitosamente')
                ->with('debug', $debugInfo);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('debug', $debugInfo ?? [])
                ->withInput();
        } catch (\Exception $e) {
            $errorInfo = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'debug_info' => $debugInfo ?? []
            ];
            
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->with('debug', $errorInfo)
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
