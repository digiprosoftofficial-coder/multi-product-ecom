<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_logo' => Setting::get('site_logo'),
            'footer_text' => Setting::get('footer_text', ''),
            'tax_rate' => Setting::get('tax_rate', '0'),
            'vat_rate' => Setting::get('vat_rate', '0'),
            'category_max_depth' => Setting::get('category_max_depth', '3'),
            'enable_compare_price' => Setting::get('enable_compare_price', '1'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'footer_text' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'category_max_depth' => 'required|integer|in:0,2,3,4,5,6',
            'enable_compare_price' => 'nullable|in:0,1',
        ]);

        Setting::set('site_name', $validated['site_name']);
        Setting::set('footer_text', $validated['footer_text'] ?? '');
        Setting::set('tax_rate', $validated['tax_rate'] ?? '0');
        Setting::set('vat_rate', $validated['vat_rate'] ?? '0');
        Setting::set('enable_compare_price', $request->boolean('enable_compare_price') ? '1' : '0');

        $newMax = (int) $validated['category_max_depth'];
        $currentMax = Category::currentTreeMaxDepth();
        if ($newMax > 0 && $newMax < $currentMax) {
            return redirect()->route('admin.settings.index')
                ->withInput()
                ->withErrors([
                    'category_max_depth' => "Cannot set max depth to {$newMax}. The current category tree already goes {$currentMax} levels deep.",
                ]);
        }
        Setting::set('category_max_depth', (string) $newMax);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists("uploads/settings/{$oldLogo}")) {
                Storage::disk('public')->delete("uploads/settings/{$oldLogo}");
            }

            $logo = $request->file('site_logo');
            $filename = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            
            if (!Storage::disk('public')->exists('uploads/settings')) {
                Storage::disk('public')->makeDirectory('uploads/settings');
            }

            $manager = new ImageManager(new Driver());
            $img = $manager->read($logo->getRealPath());
            $img->scale(width: 200, height: 200);
            Storage::disk('public')->put("uploads/settings/{$filename}", $img->encode());

            Setting::set('site_logo', $filename);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}

