<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            'footer_logo' => Setting::get('footer_logo'),
            'favicon' => Setting::get('favicon'),
            'footer_text' => Setting::get('footer_text', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'contact_hours' => Setting::get('contact_hours', ''),
            'contact_intro' => Setting::get('contact_intro', ''),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
            'currency_code' => Setting::get('currency_code', 'USD'),
            'tax_rate' => Setting::get('tax_rate', '0'),
            'vat_rate' => Setting::get('vat_rate', '0'),
            'category_max_depth' => Setting::get('category_max_depth', '3'),
            'enable_compare_price' => Setting::get('enable_compare_price', '1'),
            'about_content' => Setting::get('about_content', ''),
            'privacy_content' => Setting::get('privacy_content', ''),
            'terms_content' => Setting::get('terms_content', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => image_upload_rules(),
            'footer_logo' => image_upload_rules(),
            'favicon' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,ico|max:1024',
            'remove_site_logo' => 'nullable|boolean',
            'remove_footer_logo' => 'nullable|boolean',
            'remove_favicon' => 'nullable|boolean',
            'footer_text' => 'nullable|string|max:2000',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:2000',
            'contact_hours' => 'nullable|string|max:255',
            'contact_intro' => 'nullable|string|max:2000',
            'currency_symbol' => 'required|string|max:8',
            'currency_code' => 'nullable|string|max:8',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'category_max_depth' => 'required|integer|in:0,2,3,4,5,6',
            'enable_compare_price' => 'nullable|in:0,1',
            'about_content' => 'nullable|string',
            'privacy_content' => 'nullable|string',
            'terms_content' => 'nullable|string',
        ]);

        $newMax = (int) $validated['category_max_depth'];
        $currentMax = Category::currentTreeMaxDepth();
        if ($newMax > 0 && $newMax < $currentMax) {
            return redirect()->route('admin.settings.index')
                ->withInput()
                ->withErrors([
                    'category_max_depth' => "Cannot set max depth to {$newMax}. The current category tree already goes {$currentMax} levels deep.",
                ]);
        }

        Setting::set('site_name', $validated['site_name']);
        Setting::set('footer_text', $validated['footer_text'] ?? '');
        Setting::set('contact_phone', $validated['contact_phone'] ?? '');
        Setting::set('contact_email', $validated['contact_email'] ?? '');
        Setting::set('contact_address', $validated['contact_address'] ?? '');
        Setting::set('contact_hours', $validated['contact_hours'] ?? '');
        Setting::set('contact_intro', $validated['contact_intro'] ?? '');
        Setting::set('currency_symbol', $validated['currency_symbol']);
        Setting::set('currency_code', strtoupper($validated['currency_code'] ?? 'USD'));
        Setting::set('tax_rate', $validated['tax_rate'] ?? '0');
        Setting::set('vat_rate', $validated['vat_rate'] ?? '0');
        Setting::set('enable_compare_price', $request->boolean('enable_compare_price') ? '1' : '0');
        Setting::set('category_max_depth', (string) $newMax);
        Setting::set('about_content', sanitize_rich_text($validated['about_content'] ?? null) ?? '');
        Setting::set('privacy_content', sanitize_rich_text($validated['privacy_content'] ?? null) ?? '');
        Setting::set('terms_content', sanitize_rich_text($validated['terms_content'] ?? null) ?? '');

        $this->storeBrandImage($request, 'site_logo', 'logo', 400, 160);
        $this->storeBrandImage($request, 'footer_logo', 'footer-logo', 480, 160);
        $this->storeBrandImage($request, 'favicon', 'favicon', 64, 64);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    protected function storeBrandImage(Request $request, string $field, string $prefix, int $maxWidth, int $maxHeight): void
    {
        if ($request->boolean('remove_'.$field) && ! $request->hasFile($field)) {
            $this->deleteLogoFile(Setting::get($field));
            Setting::set($field, '');

            return;
        }

        if (! $request->hasFile($field)) {
            return;
        }

        $this->deleteLogoFile(Setting::get($field));

        $file = $request->file($field);
        $extension = strtolower($file->getClientOriginalExtension() ?: 'png');
        $filename = $prefix.'_'.time().'.'.$extension;

        if (! Storage::disk('public')->exists('uploads/settings')) {
            Storage::disk('public')->makeDirectory('uploads/settings');
        }

        if ($this->shouldProcessImage($file, $extension)) {
            $manager = new ImageManager(new Driver());
            $img = $manager->read($file->getRealPath());
            $img->scaleDown(width: $maxWidth, height: $maxHeight);
            Storage::disk('public')->put("uploads/settings/{$filename}", $img->encode());
        } else {
            Storage::disk('public')->putFileAs('uploads/settings', $file, $filename);
        }

        Setting::set($field, $filename);
    }

    protected function shouldProcessImage(UploadedFile $file, string $extension): bool
    {
        if (in_array($extension, ['ico', 'svg'], true)) {
            return false;
        }

        return (bool) $file->getRealPath();
    }

    protected function deleteLogoFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = "uploads/settings/{$filename}";
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
