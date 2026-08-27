<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Homepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class HomepageController extends Controller
{
    public function index()
    {
        $settings = [];
        foreach (array_keys(Homepage::defaults()) as $key) {
            if (str_starts_with($key, 'about_')) {
                continue;
            }
            $settings[$key] = Homepage::get($key);
        }

        $heroSlides = Homepage::slidesForAdmin();

        return view('admin.homepage.index', compact('settings', 'heroSlides'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'home_hero_autoplay' => 'nullable|boolean',
            'home_hero_show_dots' => 'nullable|boolean',
            'home_hero_show_arrows' => 'nullable|boolean',
            'home_hero_show_overlay' => 'nullable|boolean',
            'home_hero_overlay_color' => 'nullable|string|max:7',
            'home_hero_overlay_opacity' => 'nullable|integer|min:0|max:100',
            'home_hero_interval' => 'required|integer|min:2|max:15',
            'home_hero_height_desktop' => 'required|integer|min:200|max:900',
            'home_hero_height_mobile' => 'required|integer|min:180|max:800',
            'slides' => 'required|array|min:1|max:5',
            'slides.*.enabled' => 'nullable|boolean',
            'slides.*.show_content' => 'nullable|boolean',
            'slides.*.title' => 'nullable|string|max:255',
            'slides.*.highlight' => 'nullable|string|max:80',
            'slides.*.subtitle' => 'nullable|string|max:500',
            'slides.*.title_color' => 'nullable|string|max:7',
            'slides.*.subtitle_color' => 'nullable|string|max:7',
            'slides.*.highlight_color' => 'nullable|string|max:7',
            'slides.*.btn1_text' => 'nullable|string|max:80',
            'slides.*.btn1_url' => 'nullable|string|max:255',
            'slides.*.btn2_text' => 'nullable|string|max:80',
            'slides.*.btn2_url' => 'nullable|string|max:255',
            'slides.*.image' => image_upload_rules(),
            'slides.*.remove_image' => 'nullable|boolean',
            'slides.*.image_mobile' => image_upload_rules(),
            'slides.*.remove_image_mobile' => 'nullable|boolean',
            'home_stat1_value' => 'nullable|string|max:40',
            'home_stat1_label' => 'nullable|string|max:80',
            'home_stat2_value' => 'nullable|string|max:40',
            'home_stat2_label' => 'nullable|string|max:80',
            'home_stat3_value' => 'nullable|string|max:40',
            'home_stat3_label' => 'nullable|string|max:80',
            'home_categories_title' => 'nullable|string|max:120',
            'home_best_selling_title' => 'nullable|string|max:120',
            'home_featured_title' => 'nullable|string|max:120',
            'home_popular_title' => 'nullable|string|max:120',
            'home_new_title' => 'nullable|string|max:120',
            'home_best_selling_limit' => 'required|integer|min:1|max:24',
            'home_featured_limit' => 'required|integer|min:1|max:24',
            'home_popular_limit' => 'required|integer|min:1|max:24',
            'home_new_limit' => 'required|integer|min:1|max:24',
            'home_banner1_title' => 'nullable|string|max:120',
            'home_banner1_text' => 'nullable|string|max:255',
            'home_banner1_url' => 'nullable|string|max:255',
            'home_banner2_title' => 'nullable|string|max:120',
            'home_banner2_text' => 'nullable|string|max:255',
            'home_banner2_url' => 'nullable|string|max:255',
            'home_banner3_title' => 'nullable|string|max:120',
            'home_banner3_text' => 'nullable|string|max:255',
            'home_banner3_url' => 'nullable|string|max:255',
            'home_newsletter_title' => 'nullable|string|max:255',
            'home_newsletter_text' => 'nullable|string|max:500',
            'home_feature_1_title' => 'nullable|string|max:80',
            'home_feature_1_text' => 'nullable|string|max:255',
            'home_feature_2_title' => 'nullable|string|max:80',
            'home_feature_2_text' => 'nullable|string|max:255',
            'home_feature_3_title' => 'nullable|string|max:80',
            'home_feature_3_text' => 'nullable|string|max:255',
            'home_feature_4_title' => 'nullable|string|max:80',
            'home_feature_4_text' => 'nullable|string|max:255',
            'home_feature_5_title' => 'nullable|string|max:80',
            'home_feature_5_text' => 'nullable|string|max:255',
        ]);

        foreach ([
            'home_show_categories',
            'home_show_best_selling',
            'home_show_banners',
            'home_show_featured',
            'home_show_popular',
            'home_show_new',
            'home_show_newsletter',
            'home_show_features',
        ] as $toggle) {
            Setting::set($toggle, $request->boolean($toggle) ? '1' : '0');
        }

        Setting::set('home_hero_autoplay', $request->boolean('home_hero_autoplay') ? '1' : '0');
        Setting::set('home_hero_show_dots', $request->boolean('home_hero_show_dots') ? '1' : '0');
        Setting::set('home_hero_show_arrows', $request->boolean('home_hero_show_arrows') ? '1' : '0');
        Setting::set('home_hero_show_overlay', $request->boolean('home_hero_show_overlay') ? '1' : '0');
        Setting::set('home_hero_overlay_color', Homepage::normalizeColor($validated['home_hero_overlay_color'] ?? '#ffffff'));
        Setting::set('home_hero_overlay_opacity', (string) ($validated['home_hero_overlay_opacity'] ?? 45));
        Setting::set('home_hero_interval', (string) $validated['home_hero_interval']);
        Setting::set('home_hero_height_desktop', (string) (int) $validated['home_hero_height_desktop']);
        Setting::set('home_hero_height_mobile', (string) (int) $validated['home_hero_height_mobile']);

        unset(
            $validated['home_hero_autoplay'],
            $validated['home_hero_show_dots'],
            $validated['home_hero_show_arrows'],
            $validated['home_hero_show_overlay'],
            $validated['home_hero_overlay_color'],
            $validated['home_hero_overlay_opacity'],
            $validated['home_hero_interval'],
            $validated['home_hero_height_desktop'],
            $validated['home_hero_height_mobile'],
            $validated['slides']
        );

        foreach ($validated as $key => $value) {
            Setting::set($key, (string) ($value ?? ''));
        }

        $this->storeHeroSlides($request);

        return redirect()->route('admin.homepage.index')
            ->with('success', 'Homepage updated successfully.');
    }

    protected function storeHeroSlides(Request $request): void
    {
        $existing = Homepage::slidesForAdmin();
        $submitted = $request->input('slides', []);
        $slides = [];

        foreach ($submitted as $index => $data) {
            $existingSlide = $existing[$index] ?? [];
            $image = (string) ($existingSlide['image'] ?? '');
            $imageMobile = (string) ($existingSlide['image_mobile'] ?? '');

            if ($request->boolean("slides.{$index}.remove_image") && ! $request->hasFile("slides.{$index}.image")) {
                $this->deleteFile($image);
                $image = '';
            }

            if ($request->boolean("slides.{$index}.remove_image_mobile") && ! $request->hasFile("slides.{$index}.image_mobile")) {
                $this->deleteFile($imageMobile);
                $imageMobile = '';
            }

            if ($request->hasFile("slides.{$index}.image")) {
                if ($image) {
                    $this->deleteFile($image);
                }
                $image = $this->storeSlideImage($request->file("slides.{$index}.image"), 'desktop');
            }

            if ($request->hasFile("slides.{$index}.image_mobile")) {
                if ($imageMobile) {
                    $this->deleteFile($imageMobile);
                }
                $imageMobile = $this->storeSlideImage($request->file("slides.{$index}.image_mobile"), 'mobile');
            }

            $slides[] = Homepage::normalizeSlide([
                'enabled' => $request->boolean("slides.{$index}.enabled"),
                'image' => $image,
                'image_mobile' => $imageMobile,
                'show_content' => $request->boolean("slides.{$index}.show_content"),
                'title' => $data['title'] ?? '',
                'highlight' => $data['highlight'] ?? '',
                'subtitle' => $data['subtitle'] ?? '',
                'title_color' => $data['title_color'] ?? '#ffffff',
                'subtitle_color' => $data['subtitle_color'] ?? '#ffffff',
                'highlight_color' => $data['highlight_color'] ?? '#22c55e',
                'btn1_text' => $data['btn1_text'] ?? '',
                'btn1_url' => $data['btn1_url'] ?? '',
                'btn2_text' => $data['btn2_text'] ?? '',
                'btn2_url' => $data['btn2_url'] ?? '',
            ]);
        }

        foreach (array_slice($existing, count($slides)) as $removedSlide) {
            if (! empty($removedSlide['image'])) {
                $this->deleteFile($removedSlide['image']);
            }
            if (! empty($removedSlide['image_mobile'])) {
                $this->deleteFile($removedSlide['image_mobile']);
            }
        }

        Setting::set('home_hero_slides', json_encode(array_values($slides)));
    }

    protected function storeSlideImage($file, string $variant = 'desktop'): string
    {
        $prefix = $variant === 'mobile' ? 'hero_m_' : 'hero_';
        $filename = $prefix.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        Storage::disk('public')->makeDirectory('uploads/settings');

        $manager = new ImageManager(new Driver());
        $img = $manager->read($file->getRealPath());

        if ($variant === 'mobile') {
            $img->scaleDown(1080, 1350);
        } else {
            $img->scaleDown(1920, 900);
        }

        Storage::disk('public')->put('uploads/settings/'.$filename, $img->encode());

        return $filename;
    }

    protected function deleteFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }
        Storage::disk('public')->delete('uploads/settings/'.$filename);
    }
}
