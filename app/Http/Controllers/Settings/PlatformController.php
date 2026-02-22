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
        
        $companyLogoPath = Setting::get('company_logo', '');
        $companyLogoUrl = $companyLogoPath ? asset('storage/' . $companyLogoPath) : '';
        
        return Inertia::render('settings/Platform', [
            'platformName' => Setting::get('platform_name', 'CRM landings.cl'),
            'platformLogo' => $logoUrl,
            'companyName' => Setting::get('company_name', ''),
            'companyRut' => Setting::get('company_rut', ''),
            'companyGiro' => Setting::get('company_giro', ''),
            'companyAddress' => Setting::get('company_address', ''),
            'companyEmail' => Setting::get('company_email', ''),
            'companyPhone' => Setting::get('company_phone', ''),
            'companyLogo' => $companyLogoUrl,
            'taxRate' => Setting::get('tax_rate', 19),
        ]);
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'platform_name' => 'required|string|max:255',
                'platform_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'company_name' => 'nullable|string|max:255',
                'company_rut' => 'nullable|string|max:20',
                'company_giro' => 'nullable|string|max:255',
                'company_address' => 'nullable|string|max:500',
                'company_email' => 'nullable|email|max:255',
                'company_phone' => 'nullable|string|max:20',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'tax_rate' => 'nullable|numeric|min:0|max:100',
            ]);

            Setting::set('platform_name', $validated['platform_name']);
            
            // Guardar datos de la empresa
            if (isset($validated['company_name'])) {
                Setting::set('company_name', $validated['company_name']);
            }
            if (isset($validated['company_rut'])) {
                Setting::set('company_rut', $validated['company_rut']);
            }
            if (isset($validated['company_giro'])) {
                Setting::set('company_giro', $validated['company_giro']);
            }
            if (isset($validated['company_address'])) {
                Setting::set('company_address', $validated['company_address']);
            }
            if (isset($validated['company_email'])) {
                Setting::set('company_email', $validated['company_email']);
            }
            if (isset($validated['company_phone'])) {
                Setting::set('company_phone', $validated['company_phone']);
            }
            if (isset($validated['tax_rate'])) {
                Setting::set('tax_rate', $validated['tax_rate']);
            }
            
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
            
            if ($request->hasFile('company_logo')) {
                // Verificar que el directorio existe
                if (!Storage::disk('public')->exists('logos')) {
                    Storage::disk('public')->makeDirectory('logos');
                }

                // Eliminar logo anterior si existe
                $oldLogo = Setting::get('company_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                // Guardar nuevo logo
                $path = $request->file('company_logo')->store('logos', 'public');
                
                if (!$path) {
                    throw new \Exception('No se pudo guardar el logo de la empresa');
                }
                
                Setting::set('company_logo', $path);
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
