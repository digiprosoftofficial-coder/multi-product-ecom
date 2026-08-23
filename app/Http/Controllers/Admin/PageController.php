<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Homepage;
use App\Support\PageBanner;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PageController extends Controller
{
    public function index()
    {
        $pages = [
            'privacy_content' => Setting::get('privacy_content', ''),
            'terms_content' => Setting::get('terms_content', ''),
            'delivery_content' => Setting::get('delivery_content', ''),
            'returns_content' => Setting::get('returns_content', ''),
            'privacy_banner_title' => PageBanner::get('privacy_banner_title'),
            'privacy_banner_subtitle' => PageBanner::get('privacy_banner_subtitle'),
            'privacy_banner_image' => PageBanner::get('privacy_banner_image'),
            'terms_banner_title' => PageBanner::get('terms_banner_title'),
            'terms_banner_subtitle' => PageBanner::get('terms_banner_subtitle'),
            'terms_banner_image' => PageBanner::get('terms_banner_image'),
            'delivery_banner_title' => PageBanner::get('delivery_banner_title'),
            'delivery_banner_subtitle' => PageBanner::get('delivery_banner_subtitle'),
            'delivery_banner_image' => PageBanner::get('delivery_banner_image'),
            'returns_banner_title' => PageBanner::get('returns_banner_title'),
            'returns_banner_subtitle' => PageBanner::get('returns_banner_subtitle'),
            'returns_banner_image' => PageBanner::get('returns_banner_image'),
        ];

        return view('admin.pages.index', compact('pages'));
    }

    public function about()
    {
        $pages = [
            'about_title' => Homepage::get('about_title') ?: 'About '.site_name(),
            'about_content' => Setting::get('about_content', ''),
            'about_banner_title' => PageBanner::get('about_banner_title'),
            'about_banner_subtitle' => PageBanner::get('about_banner_subtitle'),
            'about_banner_image' => PageBanner::get('about_banner_image'),
        ];

        return view('admin.pages.about', compact('pages'));
    }

    public function shop()
    {
        $pages = [
            'shop_banner_title' => PageBanner::get('shop_banner_title'),
            'shop_banner_subtitle' => PageBanner::get('shop_banner_subtitle'),
            'shop_banner_image' => PageBanner::get('shop_banner_image'),
        ];

        return view('admin.pages.shop', compact('pages'));
    }

    public function contact()
    {
        $pages = [
            'contact_banner_title' => PageBanner::get('contact_banner_title'),
            'contact_banner_subtitle' => PageBanner::get('contact_banner_subtitle'),
            'contact_banner_image' => PageBanner::get('contact_banner_image'),
        ];

        return view('admin.pages.contact', compact('pages'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'privacy_content' => 'nullable|string',
            'terms_content' => 'nullable|string',
            'delivery_content' => 'nullable|string',
            'returns_content' => 'nullable|string',
            'privacy_banner_title' => 'nullable|string|max:120',
            'privacy_banner_subtitle' => 'nullable|string|max:255',
            'privacy_banner_image' => image_upload_rules(),
            'remove_privacy_banner_image' => 'nullable|boolean',
            'terms_banner_title' => 'nullable|string|max:120',
            'terms_banner_subtitle' => 'nullable|string|max:255',
            'terms_banner_image' => image_upload_rules(),
            'remove_terms_banner_image' => 'nullable|boolean',
            'delivery_banner_title' => 'nullable|string|max:120',
            'delivery_banner_subtitle' => 'nullable|string|max:255',
            'delivery_banner_image' => image_upload_rules(),
            'remove_delivery_banner_image' => 'nullable|boolean',
            'returns_banner_title' => 'nullable|string|max:120',
            'returns_banner_subtitle' => 'nullable|string|max:255',
            'returns_banner_image' => image_upload_rules(),
            'remove_returns_banner_image' => 'nullable|boolean',
        ]);

        foreach (['privacy', 'terms', 'delivery', 'returns'] as $page) {
            Setting::set("{$page}_content", sanitize_rich_text($validated["{$page}_content"] ?? null) ?? '');
            Setting::set("{$page}_banner_title", $validated["{$page}_banner_title"] ?? '');
            Setting::set("{$page}_banner_subtitle", $validated["{$page}_banner_subtitle"] ?? '');
            $this->storeBannerImage($request, "{$page}_banner_image", "{$page}-banner");
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Pages updated successfully.');
    }

    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'about_title' => 'nullable|string|max:255',
            'about_content' => 'nullable|string',
            'about_banner_title' => 'nullable|string|max:120',
            'about_banner_subtitle' => 'nullable|string|max:255',
            'about_banner_image' => image_upload_rules(),
            'remove_about_banner_image' => 'nullable|boolean',
        ]);

        Setting::set('about_title', $validated['about_title'] ?? '');
        Setting::set('about_content', sanitize_rich_text($validated['about_content'] ?? null) ?? '');
        Setting::set('about_banner_title', $validated['about_banner_title'] ?? '');
        Setting::set('about_banner_subtitle', $validated['about_banner_subtitle'] ?? '');
        $this->storeBannerImage($request, 'about_banner_image', 'about-banner');

        return redirect()->route('admin.about.index')
            ->with('success', 'About page updated successfully.');
    }

    public function updateShop(Request $request)
    {
        $validated = $request->validate([
            'shop_banner_title' => 'nullable|string|max:120',
            'shop_banner_subtitle' => 'nullable|string|max:255',
            'shop_banner_image' => image_upload_rules(),
            'remove_shop_banner_image' => 'nullable|boolean',
        ]);

        Setting::set('shop_banner_title', $validated['shop_banner_title'] ?? '');
        Setting::set('shop_banner_subtitle', $validated['shop_banner_subtitle'] ?? '');
        $this->storeBannerImage($request, 'shop_banner_image', 'shop-banner');

        return redirect()->route('admin.shop-page.index')
            ->with('success', 'Shop page updated successfully.');
    }

    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'contact_banner_title' => 'nullable|string|max:120',
            'contact_banner_subtitle' => 'nullable|string|max:255',
            'contact_banner_image' => image_upload_rules(),
            'remove_contact_banner_image' => 'nullable|boolean',
        ]);

        Setting::set('contact_banner_title', $validated['contact_banner_title'] ?? '');
        Setting::set('contact_banner_subtitle', $validated['contact_banner_subtitle'] ?? '');
        $this->storeBannerImage($request, 'contact_banner_image', 'contact-banner');

        return redirect()->route('admin.contact-page.index')
            ->with('success', 'Contact page updated successfully.');
    }

    protected function storeBannerImage(Request $request, string $field, string $prefix): void
    {
        if ($request->boolean('remove_'.$field) && ! $request->hasFile($field)) {
            $this->deleteFile(Setting::get($field));
            Setting::set($field, '');

            return;
        }

        if (! $request->hasFile($field)) {
            return;
        }

        $this->deleteFile(Setting::get($field));

        /** @var UploadedFile $file */
        $file = $request->file($field);
        $filename = $prefix.'_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        Storage::disk('public')->makeDirectory('uploads/settings');

        $manager = new ImageManager(new Driver());
        $img = $manager->read($file->getRealPath());
        $img->scaleDown(1920, 600);
        Storage::disk('public')->put('uploads/settings/'.$filename, $img->encode());

        Setting::set($field, $filename);
    }

    protected function deleteFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        Storage::disk('public')->delete('uploads/settings/'.$filename);
    }
}
