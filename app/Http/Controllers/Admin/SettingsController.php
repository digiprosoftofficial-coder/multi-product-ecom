<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use App\Rules\BangladeshPhone;
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
            'logo_height_desktop' => Setting::get('logo_height_desktop', '48'),
            'logo_height_mobile' => Setting::get('logo_height_mobile', '50'),
            'footer_text' => Setting::get('footer_text', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'contact_hours' => Setting::get('contact_hours', ''),
            'contact_intro' => Setting::get('contact_intro', ''),
            'contact_map_url' => Setting::get('contact_map_url', ''),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
            'currency_code' => Setting::get('currency_code', 'USD'),
            'tax_rate' => Setting::get('tax_rate', '0'),
            'vat_rate' => Setting::get('vat_rate', '0'),
            'category_max_depth' => Setting::get('category_max_depth', '3'),
            'enable_compare_price' => Setting::get('enable_compare_price', '1'),
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_youtube' => Setting::get('social_youtube', ''),
            'social_whatsapp' => Setting::get('social_whatsapp', ''),
            'social_tiktok' => Setting::get('social_tiktok', ''),
            'header_bg_color' => Setting::get('header_bg_color', '#1f3b2c'),
            'header_text_color' => Setting::get('header_text_color', '#ffffff'),
            'footer_bg_color' => Setting::get('footer_bg_color', '#1f3b2c'),
            'footer_text_color' => Setting::get('footer_text_color', '#ffffff'),
            'footer_bottom_bg_color' => Setting::get('footer_bottom_bg_color', '#6bb252'),
            'footer_bottom_text_color' => Setting::get('footer_bottom_text_color', '#ffffff'),
            'payment_cod_enabled' => Setting::get('payment_cod_enabled', '1'),
            'payment_bkash_enabled' => Setting::get('payment_bkash_enabled', '1'),
            'payment_nagad_enabled' => Setting::get('payment_nagad_enabled', '1'),
            'payment_rocket_enabled' => Setting::get('payment_rocket_enabled', '1'),
            'payment_bkash_number' => Setting::get('payment_bkash_number', ''),
            'payment_nagad_number' => Setting::get('payment_nagad_number', ''),
            'payment_rocket_number' => Setting::get('payment_rocket_number', ''),
            'seo_meta_description' => Setting::get('seo_meta_description', ''),
            'seo_og_image' => Setting::get('seo_og_image'),
            'google_analytics_id' => Setting::get('google_analytics_id', ''),
            'google_tag_manager_id' => Setting::get('google_tag_manager_id', ''),
            'facebook_pixel_id' => Setting::get('facebook_pixel_id', ''),
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
            'logo_height_desktop' => 'required|integer|min:24|max:96',
            'logo_height_mobile' => 'required|integer|min:24|max:80',
            'footer_text' => 'nullable|string|max:2000',
            'contact_phone' => ['nullable', 'string', 'max:50', new BangladeshPhone],
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:2000',
            'contact_hours' => 'nullable|string|max:255',
            'contact_intro' => 'nullable|string|max:2000',
            'contact_map_url' => 'nullable|url|max:2000',
            'currency_symbol' => 'required|string|max:8',
            'currency_code' => 'nullable|string|max:8',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'category_max_depth' => 'required|integer|in:0,2,3,4,5,6',
            'enable_compare_price' => 'nullable|in:0,1',
            'social_facebook' => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
            'social_whatsapp' => ['nullable', 'string', 'max:30', new BangladeshPhone],
            'social_tiktok' => 'nullable|url|max:500',
            'header_bg_color' => 'nullable|string|max:7',
            'header_text_color' => 'nullable|string|max:7',
            'footer_bg_color' => 'nullable|string|max:7',
            'footer_text_color' => 'nullable|string|max:7',
            'footer_bottom_bg_color' => 'nullable|string|max:7',
            'footer_bottom_text_color' => 'nullable|string|max:7',
            'payment_cod_enabled' => 'nullable|boolean',
            'payment_bkash_enabled' => 'nullable|boolean',
            'payment_nagad_enabled' => 'nullable|boolean',
            'payment_rocket_enabled' => 'nullable|boolean',
            'payment_bkash_number' => ['nullable', 'string', 'max:30', new BangladeshPhone],
            'payment_nagad_number' => ['nullable', 'string', 'max:30', new BangladeshPhone],
            'payment_rocket_number' => ['nullable', 'string', 'max:30', new BangladeshPhone],
            'seo_meta_description' => 'nullable|string|max:320',
            'seo_og_image' => image_upload_rules(),
            'remove_seo_og_image' => 'nullable|boolean',
            'google_analytics_id' => ['nullable', 'string', 'max:30', 'regex:/^G-[A-Z0-9]+$/'],
            'google_tag_manager_id' => ['nullable', 'string', 'max:30', 'regex:/^GTM-[A-Z0-9]+$/'],
            'facebook_pixel_id' => ['nullable', 'string', 'max:30', 'regex:/^[0-9]+$/'],
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
        Setting::set('logo_height_desktop', (string) (int) $validated['logo_height_desktop']);
        Setting::set('logo_height_mobile', (string) (int) $validated['logo_height_mobile']);
        Setting::set('footer_text', $validated['footer_text'] ?? '');
        Setting::set('contact_phone', BangladeshPhone::normalize($validated['contact_phone'] ?? null) ?? '');
        Setting::set('contact_email', $validated['contact_email'] ?? '');
        Setting::set('contact_address', $validated['contact_address'] ?? '');
        Setting::set('contact_hours', $validated['contact_hours'] ?? '');
        Setting::set('contact_intro', $validated['contact_intro'] ?? '');
        Setting::set('contact_map_url', $validated['contact_map_url'] ?? '');
        Setting::set('currency_symbol', $validated['currency_symbol']);
        Setting::set('currency_code', strtoupper($validated['currency_code'] ?? 'USD'));
        Setting::set('tax_rate', $validated['tax_rate'] ?? '0');
        Setting::set('vat_rate', $validated['vat_rate'] ?? '0');
        Setting::set('enable_compare_price', $request->boolean('enable_compare_price') ? '1' : '0');
        Setting::set('category_max_depth', (string) $newMax);
        Setting::set('social_facebook', $validated['social_facebook'] ?? '');
        Setting::set('social_instagram', $validated['social_instagram'] ?? '');
        Setting::set('social_youtube', $validated['social_youtube'] ?? '');
        Setting::set('social_whatsapp', BangladeshPhone::toWhatsAppDigits($validated['social_whatsapp'] ?? null) ?? '');
        Setting::set('social_tiktok', $validated['social_tiktok'] ?? '');
        Setting::set('header_bg_color', \App\Support\Homepage::normalizeColor($validated['header_bg_color'] ?? '#1f3b2c', '#1f3b2c'));
        Setting::set('header_text_color', \App\Support\Homepage::normalizeColor($validated['header_text_color'] ?? '#ffffff', '#ffffff'));
        Setting::set('footer_bg_color', \App\Support\Homepage::normalizeColor($validated['footer_bg_color'] ?? '#1f3b2c', '#1f3b2c'));
        Setting::set('footer_text_color', \App\Support\Homepage::normalizeColor($validated['footer_text_color'] ?? '#ffffff', '#ffffff'));
        Setting::set('footer_bottom_bg_color', \App\Support\Homepage::normalizeColor($validated['footer_bottom_bg_color'] ?? '#6bb252', '#6bb252'));
        Setting::set('footer_bottom_text_color', \App\Support\Homepage::normalizeColor($validated['footer_bottom_text_color'] ?? '#ffffff', '#ffffff'));
        $paymentEnabled = [
            'payment_cod_enabled' => $request->boolean('payment_cod_enabled'),
            'payment_bkash_enabled' => $request->boolean('payment_bkash_enabled'),
            'payment_nagad_enabled' => $request->boolean('payment_nagad_enabled'),
            'payment_rocket_enabled' => $request->boolean('payment_rocket_enabled'),
        ];

        if (! in_array(true, $paymentEnabled, true)) {
            return redirect()->route('admin.settings.index')
                ->withInput()
                ->withErrors([
                    'payment_cod_enabled' => 'Enable at least one payment method.',
                ]);
        }

        foreach ($paymentEnabled as $key => $enabled) {
            Setting::set($key, $enabled ? '1' : '0');
        }

        Setting::set('payment_bkash_number', BangladeshPhone::normalize($validated['payment_bkash_number'] ?? null) ?? '');
        Setting::set('payment_nagad_number', BangladeshPhone::normalize($validated['payment_nagad_number'] ?? null) ?? '');
        Setting::set('payment_rocket_number', BangladeshPhone::normalize($validated['payment_rocket_number'] ?? null) ?? '');
        Setting::set('seo_meta_description', $validated['seo_meta_description'] ?? '');
        Setting::set('google_analytics_id', strtoupper(trim((string) ($validated['google_analytics_id'] ?? ''))));
        Setting::set('google_tag_manager_id', strtoupper(trim((string) ($validated['google_tag_manager_id'] ?? ''))));
        Setting::set('facebook_pixel_id', preg_replace('/\D+/', '', (string) ($validated['facebook_pixel_id'] ?? '')) ?: '');

        $this->storeBrandImage($request, 'site_logo', 'logo', 400, 160);
        $this->storeBrandImage($request, 'footer_logo', 'footer-logo', 480, 160);
        $this->storeBrandImage($request, 'favicon', 'favicon', 64, 64);
        $this->storeBrandImage($request, 'seo_og_image', 'seo-og', 1200, 630);

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
