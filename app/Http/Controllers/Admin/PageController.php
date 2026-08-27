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
        ];

        foreach (['privacy', 'terms', 'delivery', 'returns'] as $page) {
            $pages = array_merge($pages, $this->bannerSettings($page));
        }

        return view('admin.pages.index', compact('pages'));
    }

    public function about()
    {
        $pages = array_merge([
            'about_title' => Homepage::get('about_title') ?: 'About '.site_name(),
            'about_content' => Setting::get('about_content', ''),
        ], $this->bannerSettings('about'));

        return view('admin.pages.about', compact('pages'));
    }

    public function shop()
    {
        $pages = $this->bannerSettings('shop');

        return view('admin.pages.shop', compact('pages'));
    }

    public function product()
    {
        $pages = [
            'product_banner_enabled' => PageBanner::get('product_banner_enabled'),
            'product_banner_subtitle' => PageBanner::get('product_banner_subtitle'),
            'product_banner_image' => PageBanner::get('product_banner_image'),
        ];

        return view('admin.pages.product', compact('pages'));
    }

    public function contact()
    {
        $pages = $this->bannerSettings('contact');

        return view('admin.pages.contact', compact('pages'));
    }

    public function cart()
    {
        $pages = $this->bannerSettings('cart');

        return view('admin.pages.cart', compact('pages'));
    }

    public function checkout()
    {
        $pages = $this->bannerSettings('checkout');

        return view('admin.pages.checkout', compact('pages'));
    }

    public function update(Request $request)
    {
        $rules = [
            'active_tab' => 'nullable|string|in:privacy,terms,delivery,returns',
            'privacy_content' => 'nullable|string',
            'terms_content' => 'nullable|string',
            'delivery_content' => 'nullable|string',
            'returns_content' => 'nullable|string',
        ];

        foreach (['privacy', 'terms', 'delivery', 'returns'] as $page) {
            $rules = array_merge($rules, $this->bannerValidationRules($page));
        }

        $validated = $request->validate($rules);

        foreach (['privacy', 'terms', 'delivery', 'returns'] as $page) {
            Setting::set("{$page}_content", sanitize_rich_text($validated["{$page}_content"] ?? null) ?? '');
            $this->saveBannerFields($request, $page, $validated);
        }

        return redirect()->route('admin.pages.index', ['tab' => $request->input('active_tab', 'privacy')])
            ->with('success', 'Pages updated successfully.');
    }

    public function updateAbout(Request $request)
    {
        $validated = $request->validate(array_merge([
            'about_title' => 'nullable|string|max:255',
            'about_content' => 'nullable|string',
        ], $this->bannerValidationRules('about')));

        Setting::set('about_title', $validated['about_title'] ?? '');
        Setting::set('about_content', sanitize_rich_text($validated['about_content'] ?? null) ?? '');
        $this->saveBannerFields($request, 'about', $validated);

        return redirect()->route('admin.about.index')
            ->with('success', 'About page updated successfully.');
    }

    public function updateShop(Request $request)
    {
        $validated = $request->validate($this->bannerValidationRules('shop'));
        $this->saveBannerFields($request, 'shop', $validated);

        return redirect()->route('admin.shop-page.index')
            ->with('success', 'Shop page updated successfully.');
    }

    public function updateProduct(Request $request)
    {
        $validated = $request->validate([
            'product_banner_enabled' => 'nullable|boolean',
            'product_banner_subtitle' => 'nullable|string|max:255',
            'product_banner_image' => image_upload_rules(),
            'remove_product_banner_image' => 'nullable|boolean',
        ]);

        Setting::set('product_banner_enabled', $request->boolean('product_banner_enabled') ? '1' : '0');
        Setting::set('product_banner_subtitle', $validated['product_banner_subtitle'] ?? '');
        $this->storeBannerImage($request, 'product_banner_image', 'product-banner');

        return redirect()->route('admin.product-page.index')
            ->with('success', 'Product page updated successfully.');
    }

    public function updateContact(Request $request)
    {
        $validated = $request->validate($this->bannerValidationRules('contact'));
        $this->saveBannerFields($request, 'contact', $validated);

        return redirect()->route('admin.contact-page.index')
            ->with('success', 'Contact page updated successfully.');
    }

    public function updateCart(Request $request)
    {
        $validated = $request->validate($this->bannerValidationRules('cart'));
        $this->saveBannerFields($request, 'cart', $validated);

        return redirect()->route('admin.cart-page.index')
            ->with('success', 'Cart page updated successfully.');
    }

    public function updateCheckout(Request $request)
    {
        $validated = $request->validate($this->bannerValidationRules('checkout'));
        $this->saveBannerFields($request, 'checkout', $validated);

        return redirect()->route('admin.checkout-page.index')
            ->with('success', 'Checkout page updated successfully.');
    }

    /**
     * @return array<string, string>
     */
    protected function bannerSettings(string $page): array
    {
        return [
            "{$page}_banner_enabled" => PageBanner::get("{$page}_banner_enabled"),
            "{$page}_banner_title" => PageBanner::get("{$page}_banner_title"),
            "{$page}_banner_subtitle" => PageBanner::get("{$page}_banner_subtitle"),
            "{$page}_banner_image" => PageBanner::get("{$page}_banner_image"),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function bannerValidationRules(string $page): array
    {
        return [
            "{$page}_banner_enabled" => 'nullable|boolean',
            "{$page}_banner_title" => 'nullable|string|max:120',
            "{$page}_banner_subtitle" => 'nullable|string|max:255',
            "{$page}_banner_image" => image_upload_rules(),
            "remove_{$page}_banner_image" => 'nullable|boolean',
        ];
    }

    protected function saveBannerFields(Request $request, string $page, array $validated): void
    {
        Setting::set("{$page}_banner_enabled", $request->boolean("{$page}_banner_enabled") ? '1' : '0');
        Setting::set("{$page}_banner_title", $validated["{$page}_banner_title"] ?? '');
        Setting::set("{$page}_banner_subtitle", $validated["{$page}_banner_subtitle"] ?? '');
        $this->storeBannerImage($request, "{$page}_banner_image", "{$page}-banner");
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
        $img->scaleDown(1920, 440);
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
