<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HospitalWebSettingsController extends Controller
{
    public function index()
    {
        return view('settings.hospital-web-settings');
    }

    public function update(Request $request)
    {

        $imageKeys = [
            'opd_header_image', 'opd_footer_image',
            'ipd_header_image', 'ipd_footer_image',
            'pathology_header_image', 'pathology_footer_image',
            'pharmacy_header_image', 'pharmacy_footer_image',
            'logo', 'hospital_qr', 'favicon'
        ];

        foreach ($request->except('_token') as $key => $value) {
            // Handle file uploads
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img'), $filename);
                $value = $filename;
            } elseif (in_array($key, $imageKeys)) {
                // Skip updating image keys when no new file is uploaded
                continue;
            }

            if (is_array($value)) {
                $value = json_encode($value);
            }

            // Update or create the setting
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
     

     
        return redirect()->route('admin.hospital-web-settings.index')->with('success', 'Settings updated successfully.');
    }

    public function printIndex()
    {
        return view('settings.hospital-print-settings');
    }

    public function printUpdate(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }

            // Update or create the setting
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.hospital-print-settings.index')->with('success', 'Print Settings updated successfully.');
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $key = $request->input('key');

        $allowedKeys = [
            'opd_header_image', 'opd_footer_image',
            'ipd_header_image', 'ipd_footer_image',
            'pathology_header_image', 'pathology_footer_image',
            'pharmacy_header_image', 'pharmacy_footer_image',
            'logo', 'hospital_qr', 'favicon'
        ];

        if (!in_array($key, $allowedKeys)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid image key.'], 400);
            }
            return redirect()->back()->with('error', 'Invalid image key.');
        }

        $setting = \App\Models\Setting::where('key', $key)->first();
        if ($setting) {
            $filename = $setting->value;
            if ($filename && file_exists(public_path('img/' . $filename))) {
                @unlink(public_path('img/' . $filename));
            }
            $setting->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
